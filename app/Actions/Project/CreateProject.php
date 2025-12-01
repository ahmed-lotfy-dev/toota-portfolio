<?php

namespace App\Actions\Project;

use App\Models\Project;
use Illuminate\Support\Str;

class CreateProject
{
  /**
   * Create a new project.
   *
   * @param array $data
   * @param array $imagePaths
   * @return Project
   */
  public function handle(array $data, array $imagePaths = []): Project
  {
    $project = Project::create([
      'title' => $data['title'],
      'slug' => Str::slug($data['title']),
      'category_id' => $data['category_id'],
      'description' => $data['description'],
      'is_featured' => $data['is_featured'] ?? false,
      'is_published' => $data['is_published'] ?? true,
      'order' => Project::max('order') + 1,
    ]);

    if (!empty($imagePaths)) {
      $this->attachImages($project, $imagePaths);
    }

    return $project;
  }

  protected function attachImages(Project $project, array $imagePaths): void
  {
    foreach ($imagePaths as $index => $path) {
      // The first image of the upload batch becomes the primary.
      $is_primary = ($index === 0);

      $project->images()->create([
        'image_path' => $path,
        'order' => $project->images()->max('order') + 1,
        'is_primary' => $is_primary,
      ]);
    }
  }
}
