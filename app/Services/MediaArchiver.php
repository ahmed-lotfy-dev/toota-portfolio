<?php

namespace App\Services;

use App\Models\Project;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use ZipArchive;

class MediaArchiver
{
  protected string $tempDir;

  public function __construct()
  {
    $this->tempDir = storage_path('app/backup-media-' . Str::random(8));
  }

  public function createArchive(string $zipFilename = 'media_backup.zip', ?string $sqlDumpPath = null): string
  {
    // 1. Prepare Directory
    if (!File::exists($this->tempDir)) {
      File::makeDirectory($this->tempDir);
    }

    $projects = Project::with('images')->get();
    $zipPath = $this->tempDir . '/' . $zipFilename;

    $zip = new ZipArchive();
    if ($zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) {
      throw new \Exception("Could not create ZIP file at $zipPath");
    }

    // Add a readme file to ensure zip is never empty (ZipArchive won't create empty zip files)
    $zip->addFromString('README.txt', "Media Archive for Toota Art Portfolio\nGenerated on " . date('Y-m-d H:i:s'));

    // Include SQL Dump if provided
    if ($sqlDumpPath && File::exists($sqlDumpPath)) {
      $zip->addFile($sqlDumpPath, 'database.sql');
    }

    foreach ($projects as $project) {
      // Sanitize project folder name
      $folderName = Str::slug($project->title); // or user Str::limit for length safety
      // If empty (edge case), fallback to ID
      if (empty($folderName)) {
        $folderName = 'project-' . $project->id;
      }

      foreach ($project->images as $image) {
        if (empty($image->image_path))
          continue;

        // Get file content from R2
        // We use stream to avoid loading huge files into memory if possible, 
        // but for ZipArchive we download to temp file usually easier or use addFromString

        // Let's check if file exists on disk "r2"
        if (!Storage::disk('r2')->exists($image->image_path))
          continue;

        $fileContent = Storage::disk('r2')->get($image->image_path);

        // Determine filename (keep original extension)
        $extension = pathinfo($image->image_path, PATHINFO_EXTENSION);
        $originalName = pathinfo($image->image_path, PATHINFO_FILENAME);

        // Maybe prepend 'primary-' if it is primary
        $prefix = $image->is_primary ? '00_primary_' : '';
        $fileName = $prefix . $originalName . '.' . $extension;

        // Add to Zip: Folder/Filename
        $zip->addFromString($folderName . '/' . $fileName, $fileContent);
      }
    }

    $zip->close();

    if (!file_exists($zipPath)) {
      throw new \Exception("Zip file was not created: $zipPath");
    }

    return $zipPath;
  }

  public function cleanup()
  {
    if (File::exists($this->tempDir)) {
      File::deleteDirectory($this->tempDir);
    }
  }

  public function getTempPath(): string
  {
    return $this->tempDir;
  }
}
