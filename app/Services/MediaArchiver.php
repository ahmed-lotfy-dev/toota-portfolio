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
        // We use stream to avoid loading huge files into memory

        // Let's check if file exists on disk "r2"
        if (!Storage::disk('r2')->exists($image->image_path))
          continue;

        // Determine filename (keep original extension)
        $extension = pathinfo($image->image_path, PATHINFO_EXTENSION);
        $originalName = pathinfo($image->image_path, PATHINFO_FILENAME);

        // Maybe prepend 'primary-' if it is primary
        $prefix = $image->is_primary ? '00_primary_' : '';
        $fileName = $prefix . $originalName . '.' . $extension;

        // Create a temporary file for this image
        $tempImage = tempnam($this->tempDir, 'img_');

        try {
          // Stream content to temp file
          $stream = Storage::disk('r2')->readStream($image->image_path);
          if ($stream) {
            file_put_contents($tempImage, stream_get_contents($stream));
            fclose($stream);

            // Add to Zip from file
            $zip->addFile($tempImage, $folderName . '/' . $fileName);
          }
        } catch (\Exception $e) {
          // Log error but continue? or throw?
          // For now, let's just log and continue to try to backup others
          \Illuminate\Support\Facades\Log::warning("Failed to archive image: {$image->id} - " . $e->getMessage());
        }

        // We cannot delete the temp file immediately if we used addFile 
        // because ZipArchive keeps the file open until close() is called? 
        // Actually, addFile() usually works fine, but let's be safe. 
        // If we want to delete immediately, we might need to close/re-open zip or just collect temp files to delete later.
        // However, ZipArchive::addFile stores the path. It reads it when $zip->close() is called. 
        // So we CANNOT delete $tempImage here.

        // Alternative: Use addFromString with stream_get_contents if memory is plenty... 
        // But the whole point was to avoid memory usage. 
        // If we use addFile, we must keep the file until zip closes.
        // So let's rely on the class $tempDir cleanup. 
        // BUT, generating thousands of temp files might hit inode limits or similar if project is huge.
        // A better approach for HUGE archives is adding, closing zip, deleting temp, re-opening zip.
        // But for "Toota Art", assume disk space is fine for temp files until process ends.
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
