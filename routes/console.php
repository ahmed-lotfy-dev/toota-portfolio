<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Scheduled Backups
try {
    $settings = (new \App\Services\BackupSettings)->get();

    if ($settings['enabled'] ?? false) {
        $frequency = $settings['frequency'] ?? 'daily';
        $time = $settings['time'] ?? '01:00';

        $task = Schedule::call(function () use ($settings) {
            // 1. Dynamic Retention Config
            // We override the config in-memory before running the clean command
            if (isset($settings['keep_daily'])) {
                config(['backup.cleanup.default_strategy.keep_daily_backups' => $settings['keep_daily']]);
            }
            if (isset($settings['keep_weekly'])) {
                config(['backup.cleanup.default_strategy.keep_weekly_backups' => $settings['keep_weekly']]);
            }
            if (isset($settings['keep_monthly'])) {
                config(['backup.cleanup.default_strategy.keep_monthly_backups' => $settings['keep_monthly']]);
            }

            // 2. Run Backup (DB + Files to R2)
            // Using 'backup:run' takes a full backup as per spatie config, sending to 'r2' if configured as default or specified
            // We force --only-to-disk=r2 to ensure cloud safety
            Artisan::call('backup:run', [
                '--only-to-disk' => 'r2',
                '--disable-notifications' => true
            ]);

            // 3. Run Cleanup
            Artisan::call('backup:clean', [
                '--disable-notifications' => true
            ]);

        });

        // Apply Frequency
        if ($frequency === 'daily') {
            $task->dailyAt($time);
        } elseif ($frequency === 'weekly') {
            $task->weeklyOn(1, $time); // Runs on Mondays
        } elseif ($frequency === 'monthly') {
            $task->monthlyOn(1, $time);
        }
    }
} catch (\Exception $e) {
    // Prevent scheduler crash if settings file is corrupt
    \Illuminate\Support\Facades\Log::error('Backup Scheduler Error: ' . $e->getMessage());
}
