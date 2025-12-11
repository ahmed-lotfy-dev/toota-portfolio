<?php

namespace App\Livewire\Settings;

use App\Services\DataExportService;
use App\Services\MediaArchiver;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use App\Services\BackupSettings;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Flux\Flux;
use Spatie\Backup\BackupDestination\Backup;
use Spatie\Backup\BackupDestination\BackupDestination;
use Spatie\Backup\Tasks\Monitor\HealthCheckChecker;
use Spatie\DbDumper\Databases\PostgreSql;
use Spatie\DbDumper\Databases\MySql;
use Spatie\DbDumper\Databases\Sqlite;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\File;

#[Layout('components.layouts.dashboard')]
class Backups extends Component
{
    public $backups = [];
    public $isBackingUp = false;
    public $isArchivingMedia = false;
    public $schedule = [];
    public $selectedDownloadType = 'json'; // json, media, sql, full
    public $selectedCloudType = 'db'; // db, full

    public function mount(BackupSettings $settings)
    {
        $this->schedule = $settings->get();
        $this->refreshBackups();
    }

    public function refreshBackups()
    {
        try {
            // Get backups from R2
            // We manually load configured disks to ensure R2 is included
            $disks = config('backup.backup.destination.disks') ?? ['local'];
            $backupName = config('backup.backup.name');

            $backupDestinations = collect($disks)->map(function ($disk) use ($backupName) {
                return \Spatie\Backup\BackupDestination\BackupDestination::create($disk, $backupName);
            });

            $this->backups = $backupDestinations
                ->flatMap(function (BackupDestination $destination) {
                    return $destination->backups()->map(function (Backup $backup) use ($destination) {
                        return [
                            'path' => $backup->path(),
                            'date' => $backup->date()->format('Y-m-d H:i:s'),
                            'size' => $backup->sizeInBytes(),
                            'size_formatted' => $this->formatSize($backup->sizeInBytes()),
                            'disk' => $destination->diskName(),
                            'exists' => $backup->exists(),
                        ];
                    });
                })
                ->sortByDesc('date')
                ->values()
                ->toArray();
        } catch (\Exception $e) {
            Log::error('Failed to refresh backups: ' . $e->getMessage());
            // Optionally set an error state or notify
            $this->backups = [];
        }
        Log::info('Backups refreshed. Total backups found: ' . count($this->backups));
    }

    private function formatSize($bytes)
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        $bytes /= pow(1024, $pow);

        return round($bytes, 2) . ' ' . $units[$pow];
    }

    public function downloadJson(DataExportService $service)
    {
        $json = $service->exportToJson();
        $filename = 'toota-art-data-' . now()->format('Y-m-d-His') . '.json';

        return response()->streamDownload(function () use ($json) {
            echo $json;
        }, $filename);
        $this->dispatch('notify', message: 'JSON export initiated successfully!', type: 'success');
    }

    public function downloadMediaArchive(MediaArchiver $archiver)
    {
        $this->isArchivingMedia = true;

        try {
            $zipPath = $archiver->createArchive();
            $filename = basename($zipPath);

            return response()->download($zipPath)->deleteFileAfterSend(true);
            $this->dispatch('notify', message: 'Media archive download initiated!', type: 'success');
        } catch (\Exception $e) {
            Log::error('Media Archive Failed: ' . $e->getMessage());
            $this->dispatch('notify', message: 'Failed to create media archive: ' . $e->getMessage(), type: 'error');
        } finally {
            $this->isArchivingMedia = false;
        }
    }

    public function backupDbToCloud()
    {
        $this->isBackingUp = true;
        
        // Store original notification config
        $originalNotificationConfig = config('backup.notifications.notifications');

        try {
            // Temporarily disable mail notifications for this UI-triggered action
            config([
                'backup.notifications.notifications' => [
                    \Spatie\Backup\Notifications\Notifications\BackupHasFailedNotification::class => [],
                    \Spatie\Backup\Notifications\Notifications\UnhealthyBackupWasFoundNotification::class => [],
                    \Spatie\Backup\Notifications\Notifications\CleanupHasFailedNotification::class => [],
                    \Spatie\Backup\Notifications\Notifications\BackupWasSuccessfulNotification::class => [],
                    \Spatie\Backup\Notifications\Notifications\HealthyBackupWasFoundNotification::class => [],
                    \Spatie\Backup\Notifications\Notifications\CleanupWasSuccessfulNotification::class => [],
                ],
            ]);

            // Run Spatie Backup for DB only to R2
            Log::info('Attempting to run Spatie backup for DB only to R2.');
            Artisan::call('backup:run', ['--only-db' => true, '--only-to-disk' => 'r2']);
            Log::info('Spatie backup for DB only to R2 command executed. Output: ' . Artisan::output());

            $this->refreshBackups();
            Flux::toast(text: 'Database backup uploaded to Cloud (R2)!', variant: 'success');
        } catch (\Exception $e) {
            Log::error('Cloud DB Backup Failed: ' . $e->getMessage() . ' - Trace: ' . $e->getTraceAsString());
            Flux::toast(text: 'Backup failed: ' . $e->getMessage(), variant: 'danger');
        } finally {
            // Restore original notification config to not affect cron jobs
            config(['backup.notifications.notifications' => $originalNotificationConfig]);
            $this->isBackingUp = false;
        }
    }

    public function backupFullToCloud(MediaArchiver $archiver)
    {
        $this->isBackingUp = true;

        try {
            Log::info('Starting full cloud backup process.');

            // 1. Generate SQL Dump
            $sqlPath = storage_path('app/database.sql');
            Log::info('Generating SQL dump to temporary path: ' . $sqlPath);
            $this->getDbDumper()->dumpToFile($sqlPath);
            Log::info('SQL dump generated.');

            // 2. Create Archive with SQL
            $zipFilename = 'full_backup_' . Carbon::now()->format('Y-m-d-H-i-s') . '.zip';
            Log::info('Creating full archive (SQL + Media) at path: ' . $zipFilename);
            $zipPath = $archiver->createArchive($zipFilename, $sqlPath);
            Log::info('Full archive created at: ' . $zipPath);

            // 3. Upload to R2
            $appName = config('backup.backup.name');
            $r2Path = $appName . '/' . $zipFilename;
            Log::info('Uploading full archive to R2 at: ' . $r2Path);

            $fileStream = fopen($zipPath, 'r');
            Storage::disk('r2')->put($r2Path, $fileStream);
            fclose($fileStream);
            Log::info('Full archive uploaded to R2.');

            // 4. Cleanup
            Log::info('Starting local cleanup for full backup.');
            if (File::exists($sqlPath))
                File::delete($sqlPath);
            $archiver->cleanup();
            Log::info('Local cleanup for full backup completed.');

            $this->refreshBackups();
            $this->dispatch('notify', message: 'Full Backup (Media+DB) uploaded to Cloud (R2)!', type: 'success');
        } catch (\Exception $e) {
            Log::error('Cloud Full Backup Failed: ' . $e->getMessage() . ' - Trace: ' . $e->getTraceAsString());
            Flux::toast(text: 'Full Backup failed: ' . $e->getMessage(), variant: 'danger');
        } finally {
            $this->isBackingUp = false;
        }
    }

    public function downloadBackup($disk, $path)
    {
        // We shouldn't allow arbitrary file download, but here we trust the path comes from our valid list
        // Security check: ensure path is within our backup folders
        if (!Storage::disk($disk)->exists($path)) {
            Log::warning("Backup file not found on disk '{$disk}' at path '{$path}'.");
            $this->dispatch('notify', message: 'File not found.', type: 'error');
            return;
        }

        /** @var \Illuminate\Filesystem\FilesystemAdapter $filesystem */
        $filesystem = Storage::disk($disk);

        return $filesystem->download($path);
        $this->dispatch('notify', message: 'Backup download initiated!', type: 'success');
    }

    public function deleteBackup($disk, $path)
    {
        try {
            if (Storage::disk($disk)->exists($path)) {
                Storage::disk($disk)->delete($path);

                $this->refreshBackups();
                $this->dispatch('notify', message: 'Backup deleted successfully.', type: 'success');
            } else {
                $this->dispatch('notify', message: 'File not found.', type: 'error');
            }
        } catch (\Exception $e) {
            Log::error('Delete Backup Failed: ' . $e->getMessage());
            $this->dispatch('notify', message: 'Failed to delete backup.', type: 'error');
        }
    }

    public function downloadSqlDump()
    {
        try {
            $filename = 'backup-' . Carbon::now()->format('Y-m-d-H-i-s') . '.sql';
            $tempLocalPath = tempnam(sys_get_temp_dir(), 'sql_dump_'); // Create a unique temp file
            
            // Dump to a local temporary file first
            $this->getDbDumper()->dumpToFile($tempLocalPath);

            $r2Path = 'sql-dumps/' . $filename; // Store in a specific folder on R2
            
            // Upload to R2
            Storage::disk('r2')->put($r2Path, file_get_contents($tempLocalPath));

            // Clean up the local temporary file
            unlink($tempLocalPath);

            // Generate a temporary signed URL for download from R2
            $downloadUrl = Storage::disk('r2')->temporaryUrl($r2Path, now()->addMinutes(5), [
                'ResponseContentDisposition' => 'attachment; filename="' . $filename . '"',
            ]);

            $this->dispatch('notify', message: 'SQL dump successfully generated and available for download!', type: 'success');
            
            // Redirect the user to the generated download URL
            return $this->redirect($downloadUrl);

        } catch (\Exception $e) {
            Log::error('SQL Dump Failed: ' . $e->getMessage());
            $this->dispatch('notify', message: 'Failed to generate SQL dump: ' . $e->getMessage(), type: 'error');
        }
    }

    public function downloadFullBackup(MediaArchiver $archiver)
    {
        $this->isArchivingMedia = true;

        try {
            // 1. Generate SQL Dump to a temporary local file
            $sqlFilename = 'database.sql';
            $sqlTempPath = tempnam(sys_get_temp_dir(), 'sql_dump_');
            $this->getDbDumper()->dumpToFile($sqlTempPath);

            // 2. Create Archive with SQL and Media locally
            $zipFilename = 'full_backup_' . Carbon::now()->format('Y-m-d-H-i-s') . '.zip';
            $zipTempPath = $archiver->createArchive($zipFilename, $sqlTempPath); // archiver handles deletion of sqlTempPath

            // 3. Upload to R2
            $r2Path = 'full-backups/' . $zipFilename; // Store in a specific folder on R2
            Storage::disk('r2')->put($r2Path, file_get_contents($zipTempPath));

            // 4. Cleanup local temporary files
            if (File::exists($sqlTempPath)) { // Just in case archiver didn't delete it
                File::delete($sqlTempPath);
            }
            if (File::exists($zipTempPath)) {
                File::delete($zipTempPath);
            }

            // 5. Generate a temporary signed URL for download from R2
            $downloadUrl = Storage::disk('r2')->temporaryUrl($r2Path, now()->addMinutes(5), [
                'ResponseContentDisposition' => 'attachment; filename="' . $zipFilename . '"',
            ]);

            $this->dispatch('notify', message: 'Full backup successfully generated and available for download!', type: 'success');
            return $this->redirect($downloadUrl);

        } catch (\Exception $e) {
            Log::error('Full Backup Failed: ' . $e->getMessage());
            $this->dispatch('notify', message: 'Failed to create full backup: ' . $e->getMessage(), type: 'error');
        } finally {
            $this->isArchivingMedia = false;
        }
    }

    public function triggerDownload(DataExportService $jsonService, MediaArchiver $archiver)
    {
        return match ($this->selectedDownloadType) {
            'json' => $this->downloadJson($jsonService),
            'media' => $this->downloadMediaArchive($archiver),
            'sql' => $this->downloadSqlDump(),
            'full' => $this->downloadFullBackup($archiver),
            default => null,
        };
    }

    public function triggerCloudBackup(MediaArchiver $archiver)
    {
        return match ($this->selectedCloudType) {
            'db' => $this->backupDbToCloud(),
            'full' => $this->backupFullToCloud($archiver),
            default => null,
        };
    }

    public function saveSchedule(BackupSettings $settings)
    {
        $this->validate([
            'schedule.enabled' => 'boolean',
            'schedule.frequency' => 'required|in:daily,weekly,monthly',
            'schedule.time' => 'required',
            'schedule.keep_daily' => 'nullable|integer|min:1|max:365',
            'schedule.keep_weekly' => 'nullable|integer|min:1|max:52',
            'schedule.keep_monthly' => 'nullable|integer|min:1|max:24',
        ]);

        $settings->save($this->schedule);

        $this->dispatch('notify', message: 'Backup schedule updated successfully!', type: 'success');
    }

    protected function getDbDumper()
    {
        $connection = config('database.default');
        $config = config("database.connections.$connection");
        Log::info("Attempting to get DB Dumper for connection '{$connection}'. Config: " . json_encode($config));

        $dumper = match ($connection) {
            'pgsql' => PostgreSql::create()
                ->setDbName($config['database'])
                ->setUserName($config['username'])
                ->setPassword($config['password'])
                ->setHost($config['host'] ?? '127.0.0.1')
                ->setPort($config['port'] ?? 5432),
            'mysql' => MySql::create()
                ->setDbName($config['database'])
                ->setUserName($config['username'])
                ->setPassword($config['password'])
                ->setHost($config['host'] ?? '127.0.0.1')
                ->setPort($config['port'] ?? 3306),
            'sqlite' => Sqlite::create()
                ->setDbName($config['database']),
            default => throw new \Exception("Unsupported database driver: $connection"),
        };

        // Set binary path from config or auto-detect
        if ($connection === 'pgsql') {
            // First, try to get from config
            $configuredPath = $config['dump']['dump_binary_path'] ?? null;
            if ($configuredPath) {
                $dumper->setDumpBinaryPath($configuredPath);
            } else {
                // Fallback to auto-detection
                $pgDumpPath = $this->findBinary('pg_dump');
                if ($pgDumpPath) {
                    $dumper->setDumpBinaryPath(dirname($pgDumpPath));
                }
            }
        } elseif ($connection === 'mysql') {
            // First, try to get from config
            $configuredPath = $config['dump']['dump_binary_path'] ?? null;
            if ($configuredPath) {
                $dumper->setDumpBinaryPath($configuredPath);
            } else {
                // Fallback to auto-detection
                $mysqldumpPath = $this->findBinary('mysqldump');
                if ($mysqldumpPath) {
                    $dumper->setDumpBinaryPath(dirname($mysqldumpPath));
                }
            }
        }

        return $dumper;
    }

    protected function findBinary($binaryName)
    {
        // Log the attempt to find the binary
        Log::info("Attempting to find binary: {$binaryName}");

        // Try which command first
        $whichCommand = "which {$binaryName} 2>/dev/null";
        Log::info("Executing command: {$whichCommand}");
        $path = trim(shell_exec($whichCommand) ?? '');
        Log::info("Result of 'which' command: '{$path}'");

        if ($path && file_exists($path)) {
            Log::info("Binary found at: {$path}");
            return $path;
        }

        // For Nixpacks/NixOS, search in /nix/store
        if (file_exists('/nix/store')) {
            Log::info("Searching for binary in /nix/store");
            $findCommand = "find /nix/store -name {$binaryName} -type f 2>/dev/null | head -1";
            Log::info("Executing command: {$findCommand}");
            $path = trim(shell_exec($findCommand) ?? '');
            Log::info("Result of 'find' command: '{$path}'");

            if ($path && file_exists($path)) {
                Log::info("Binary found at: {$path}");
                return $path;
            }
        }

        Log::warning("Binary '{$binaryName}' not found.");
        return null;
    }

    public function render()
    {
        return view('livewire.settings.backups');
    }
}

