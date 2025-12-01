<?php

namespace App\Actions\Project;

use App\Models\Project;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class UpdateProject
{
  /**
   * Update an existing project.
   *
   * @param Project $project
   * @param array $data
   * @param array $newImagePaths
   * @return Project
   */
  public function handle(Project $project, array $data, array $newImagePaths = []): Project
  {
    $project->update([
      'title' => $data['title'],
      'slug' => Str::slug($data['title']),
      'category_id' => $data['category_id'],
      'description' => $data['description'],
      'is_featured' => $data['is_featured'] ?? false,
      'is_published' => $data['is_published'] ?? true,
    ]);

    if (!empty($newImagePaths)) {
      // Replace strategy: Delete all existing images and replace with new ones
      $this->replaceImages($project, $newImagePaths);
    }

    return $project;
  }

  protected function replaceImages(Project $project, array $imagePaths): void
  {
    // 1. Delete existing images from storage and DB
    foreach ($project->images as $image) {
      if ($image->image_path) {
        Storage::disk('r2')->delete($image->image_path);
      }
      $image->delete();
    }

    // 2. Attach new images
    foreach ($imagePaths as $index => $path) {
      // The first image of the new batch becomes the primary.
      $is_primary = ($index === 0);

      $project->images()->create([
        'image_path' => $path,
        'order' => $project->images()->max('order') + 1,
        'is_primary' => $is_primary,
      ]);
    }
  }
}
