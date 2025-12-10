<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;

class BackupSettings
{
  protected string $path = 'backup-settings.json';

  public function get(): array
  {
    if (!Storage::exists($this->path)) {
      return $this->defaults();
    }

    return json_decode(Storage::get($this->path), true);
  }

  public function save(array $settings): void
  {
    $current = $this->get();
    $merged = array_merge($current, $settings);
    Storage::put($this->path, json_encode($merged, JSON_PRETTY_PRINT));
  }

  public function defaults(): array
  {
    return [
      'enabled' => false,
      'frequency' => 'weekly', // daily, weekly
      'time' => '01:00',
      'keep_weekly' => 3,
      'keep_monthly' => 3,
    ];
  }
}
