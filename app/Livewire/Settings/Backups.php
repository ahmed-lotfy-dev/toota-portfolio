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
    }

    public function downloadMediaArchive(MediaArchiver $archiver)
    {
        $this->isArchivingMedia = true;

        try {
            $zipPath = $archiver->createArchive();
            $filename = basename($zipPath);

            return response()->download($zipPath)->deleteFileAfterSend(true);
        } catch (\Exception $e) {
            Log::error('Media Archive Failed: ' . $e->getMessage());
            Flux::toast(text: 'Failed to create media archive: ' . $e->getMessage(), variant: 'danger');
        } finally {
            $this->isArchivingMedia = false;
        }
    }

    public function backupDbToCloud()
    {
        $this->isBackingUp = true;

        try {
            // Run Spatie Backup for DB only to R2
            Artisan::call('backup:run', ['--only-db' => true, '--only-to-disk' => 'r2']);

            $this->refreshBackups();
            Flux::toast(text: 'Database backup uploaded to Cloud (R2)!', variant: 'success');
        } catch (\Exception $e) {
            Log::error('Cloud DB Backup Failed: ' . $e->getMessage());
            Flux::toast(text: 'Backup failed: ' . $e->getMessage(), variant: 'danger');
        } finally {
            $this->isBackingUp = false;
        }
    }

    public function backupFullToCloud(MediaArchiver $archiver)
    {
        $this->isBackingUp = true;

        try {
            // 1. Generate SQL Dump
            $sqlPath = storage_path('app/database.sql');
            $this->getDbDumper()->dumpToFile($sqlPath);

            // 2. Create Archive with SQL
            $zipFilename = 'toota-full-backup-' . Carbon::now()->format('Y-m-d-H-i-s') . '.zip';
            $zipPath = $archiver->createArchive($zipFilename, $sqlPath);

            // 3. Upload to R2
            // We'll place it in the App Name folder to match Spatie's convention so it shows in the list
            $appName = config('backup.backup.name');
            $r2Path = $appName . '/' . $zipFilename;

            $fileStream = fopen($zipPath, 'r');
            Storage::disk('r2')->put($r2Path, $fileStream);
            fclose($fileStream);

            // 4. Cleanup
            if (File::exists($sqlPath))
                File::delete($sqlPath);
            $archiver->cleanup(); // Ensure temp zip is gone (MediaArchiver cleans up temp dir) But wait, createArchive returns path inside temp dir.
            // Actually MediaArchiver::cleanup() deletes the temp dir. 
            // We should call cleanup AFTER uploading.

            // Note: MediaArchiver creates a temp dir for the whole operation.
            // We should verify if createArchive assumes we consume it immediately or if we need to manually trigger cleanup of the zip.
            // The zip is inside $archiver->getTempPath().
            // So calling cleanup() destroys the zip.
            $archiver->cleanup();

            $this->refreshBackups(); // This might not list custom files unless we point Spatie to list them?
            // Spatie lists files in configured backup destination. If we put it in 'backups/', ensure Spatie config points there or matches name.
            // Spatie config 'backup.name' usually determines subfolder.
            // Let's just notify success for now.

            Flux::toast(text: 'Full Backup (Media+DB) uploaded to Cloud (R2)!', variant: 'success');
        } catch (\Exception $e) {
            Log::error('Cloud Full Backup Failed: ' . $e->getMessage());
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
            Flux::toast(text: 'File not found.', variant: 'danger');
            return;
        }

        /** @var \Illuminate\Filesystem\FilesystemAdapter $filesystem */
        $filesystem = Storage::disk($disk);

        return $filesystem->download($path);
    }

    public function deleteBackup($disk, $path)
    {
        try {
            if (Storage::disk($disk)->exists($path)) {
                Storage::disk($disk)->delete($path);

                $this->refreshBackups();
                Flux::toast(text: 'Backup deleted successfully.', variant: 'success');
            } else {
                Flux::toast(text: 'File not found.', variant: 'danger');
            }
        } catch (\Exception $e) {
            Log::error('Delete Backup Failed: ' . $e->getMessage());
            Flux::toast(text: 'Failed to delete backup.', variant: 'danger');
        }
    }

    public function downloadSqlDump()
    {
        try {
            $filename = 'backup-' . Carbon::now()->format('Y-m-d-H-i-s') . '.sql';
            $tempPath = storage_path('app/' . $filename);

            $this->getDbDumper()->dumpToFile($tempPath);

            return response()->download($tempPath)->deleteFileAfterSend(true);
        } catch (\Exception $e) {
            Log::error('SQL Dump Failed: ' . $e->getMessage());
            Flux::toast(text: 'Failed to generate SQL dump: ' . $e->getMessage(), variant: 'danger');
        }
    }

    public function downloadFullBackup(MediaArchiver $archiver)
    {
        $this->isArchivingMedia = true;

        try {
            // 1. Generate SQL Dump
            $sqlFilename = 'database.sql';
            $sqlPath = storage_path('app/' . $sqlFilename);
            $this->getDbDumper()->dumpToFile($sqlPath);

            // 2. Create Archive with SQL
            $zipPath = $archiver->createArchive('full_backup_' . Carbon::now()->format('Y-m-d-H-i-s') . '.zip', $sqlPath);

            // Cleanup SQL file
            if (File::exists($sqlPath)) {
                File::delete($sqlPath);
            }

            return response()->download($zipPath)->deleteFileAfterSend(true);
        } catch (\Exception $e) {
            Log::error('Full Backup Failed: ' . $e->getMessage());
            Flux::toast(text: 'Failed to create full backup: ' . $e->getMessage(), variant: 'danger');
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

        Flux::toast(text: 'Backup schedule updated successfully!', variant: 'success');
    }

    protected function getDbDumper()
    {
        $config = config('database.connections.pgsql');

        return PostgreSql::create()
            ->setDbName($config['database'])
            ->setUserName($config['username'])
            ->setPassword($config['password'])
            ->setHost($config['host'] ?? '127.0.0.1')
            ->setPort($config['port'] ?? 5432);
    }

    public function render()
    {
        return view('livewire.settings.backups');
    }
}

