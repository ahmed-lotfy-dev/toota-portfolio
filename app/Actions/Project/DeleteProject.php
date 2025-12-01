<?php

namespace App\Actions\Project;

use App\Models\Project;

class DeleteProject
{
  /**
   * Delete a project.
   *
   * @param Project $project
   * @return void
   */
  public function handle(Project $project): void
  {
    // The Project model's deleting event handles the deletion of images from storage.
    $project->delete();
  }
}
