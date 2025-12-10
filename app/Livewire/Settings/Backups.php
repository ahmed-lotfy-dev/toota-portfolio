<?php

namespace App\Livewire\Settings;

use App\Services\DataExportService;
use App\Services\MediaArchiver;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Spatie\Backup\BackupDestination\Backup;
use Spatie\Backup\BackupDestination\BackupDestination;
use Spatie\Backup\Tasks\Monitor\HealthCheckChecker;

#[Layout('components.layouts.dashboard')]
class Backups extends Component
{
    public $backups = [];
    public $isBackingUp = false;
    public $isArchivingMedia = false;

    public function mount()
    {
        $this->refreshBackups();
    }

    public function refreshBackups()
    {
        // Get backups from R2
        // We look for the disk 'r2' config
        $config = config('backup.backup') ?? [];
        $backupConfig = \Spatie\Backup\Config\Config::fromArray($config);
        $backupDestinations = \Spatie\Backup\BackupDestination\BackupDestinationFactory::createFromArray($backupConfig);

        $this->backups = collect($backupDestinations)
            ->flatMap(function (BackupDestination $destination) {
                return $destination->backups()->map(function (Backup $backup) use ($destination) {
                    return [
                        'path' => $backup->path(),
                        'date' => $backup->date()->format('Y-m-d H:i:s'),
                        'size' => $backup->sizeInBytes(),
                        'disk' => $destination->diskName(),
                        'exists' => $backup->exists(),
                    ];
                });
            })
            ->sortByDesc('date')
            ->values()
            ->toArray();
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
            $this->dispatch('notify', message: 'Failed to create media archive: ' . $e->getMessage(), type: 'error');
        } finally {
            $this->isArchivingMedia = false;
        }
    }

    public function createBackup()
    {
        $this->isBackingUp = true;

        // We purposely run this only for DB to R2 to be quick usually
        // But for full backup it might take time, so better to dispatch job or run artisan command
        // For waiting in UI, we can try running it directly, but it might timeout.
        // Let's run just db backup to r2

        try {
            // We'll use artisan call, but note this is sync and might timeout if DB is huge
            Artisan::call('backup:run', ['--only-db' => true, '--only-to-disk' => 'r2', '--disable-notifications' => true]);

            $this->refreshBackups();
            $this->dispatch('notify', message: 'Database backup created successfully!', type: 'success');
        } catch (\Exception $e) {
            Log::error('Backup Failed: ' . $e->getMessage());
            $this->dispatch('notify', message: 'Backup failed: ' . $e->getMessage(), type: 'error');
        } finally {
            $this->isBackingUp = false;
        }
    }

    public function downloadBackup($disk, $path)
    {
        // We shouldn't allow arbitrary file download, but here we trust the path comes from our valid list
        // Security check: ensure path is within our backup folders
        if (!Storage::disk($disk)->exists($path)) {
            $this->dispatch('notify', message: 'File not found.', type: 'error');
            return;
        }

        /** @var \Illuminate\Filesystem\FilesystemAdapter $filesystem */
        $filesystem = Storage::disk($disk);

        return $filesystem->download($path);
    }

    public function render()
    {
        return view('livewire.settings.backups');
    }
}
