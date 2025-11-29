<?php

namespace App\Services;

use App\Models\Project;
use App\Models\ProjectImage;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProjectService
{
    /**
     * Create a new project.
     *
     * @param array $data The project data (title, category_id, etc.).
     * @param UploadedFile[] $images An array of uploaded image files.
     * @return Project The newly created project.
     */
    public function create(array $data, array $images = []): Project
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

        if (!empty($images)) {
            $this->processImages($project, $images);
        }

        return $project;
    }

    /**
     * Update an existing project.
     *
     * @param Project $project The project model instance to update.
     * @param array $data The new data for the project.
     * @param UploadedFile[] $images New images to be added to the project.
     * @return Project The updated project.
     */
    public function update(Project $project, array $data, array $images = []): Project
    {
        $project->update([
            'title' => $data['title'],
            'slug' => Str::slug($data['title']),
            'category_id' => $data['category_id'],
            'description' => $data['description'],
            'is_featured' => $data['is_featured'] ?? false,
            'is_published' => $data['is_published'] ?? true,
        ]);

        if (!empty($images)) {
            $this->processImages($project, $images);
        }

        return $project;
    }

    /**
     * Delete a project and all its associated images.
     *
     * @param Project $project The project to delete.
     * @return void
     */
    public function delete(Project $project): void
    {
        // We iterate through the project's images and delete them one by one.
        // This ensures that our logic for deleting from storage is triggered for each image.
        foreach ($project->images as $image) {
            $this->deleteImage($image);
        }

        // After all images are gone, delete the project record itself.
        $project->delete();
    }

    /**
     * Delete a single project image.
     *
     * @param ProjectImage $image The image to delete.
     * @return void
     */
    public function deleteImage(ProjectImage $image): void
    {
        // First, delete the physical file from the 'r2' storage disk.
        Storage::disk('r2')->delete($image->image_path);

        $project = $image->project;
        $wasPrimary = $image->is_primary;

        // Then, delete the image record from the database.
        $image->delete();

        // If the image we just deleted was the primary one, we need to
        // assign a new primary image to the project to ensure it always has one.
        if ($wasPrimary) {
            $this->assignNewPrimaryImage($project);
        }
    }

    /**
     * Process and store uploaded images for a project.
     *
     * @param Project $project The project the images belong to.
     * @param UploadedFile[] $images The array of uploaded files.
     * @return void
     */
    protected function processImages(Project $project, array $images): void
    {
        // Check if the project already has any images. This helps determine
        // if the first new image should become the primary one.
        $isFirstUpload = $project->images()->doesntExist();

        foreach ($images as $index => $imageFile) {
            // Store the image in 'projects/{project_slug}' folder on the 'r2' disk.
            // The store() method returns the path to the stored file.
            $path = $imageFile->store('projects/' . $project->slug, 'r2');

            // The first image of the first upload batch automatically becomes the primary.
            $is_primary = ($isFirstUpload && $index === 0);

            $project->images()->create([
                'image_path' => $path,
                'order' => $project->images()->max('order') + 1,
                'is_primary' => $is_primary,
            ]);
        }
    }

    /**
     * If a project has images but no primary image, assign the first one as primary.
     *
     * @param Project $project
     * @return void
     */
    protected function assignNewPrimaryImage(Project $project): void
    {
        // Check if the project has any images left and if none of them are primary.
        if ($project->images()->count() > 0 && !$project->images()->where('is_primary', true)->exists()) {
            // Get the first available image (based on its order) and set it as primary.
            $newPrimary = $project->images()->orderBy('order')->first();
            if ($newPrimary) {
                $newPrimary->update(['is_primary' => true]);
            }
        }
    }
}
