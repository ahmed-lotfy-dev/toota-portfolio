<?php

namespace App\Livewire\Dashboard;

use App\Models\Category;
use App\Models\Project;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\WithFileUploads;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use App\Models\ProjectImage;
use Illuminate\Support\Facades\Log;

#[Layout('components.layouts.dashboard')]
class Projects extends Component
{
    use WithFileUploads;

    public $projects;
    public $categories;

    public $title = '';
    public $category_id = '';
    public $description = '';
    public $is_featured = false;
    public $is_published = true;
    public $editingId = null;
    public $images = [];
    public $showProjectImages = []; // Tracks which project images are visible

    public $showAddProjectModal = false;
    public $showAddCategoryModal = false;
    public $newCategoryName = '';
    public $newCategoryDescription = '';
    public $newImages = []; // New property for uploaded images


    protected $rules = [
        'title' => 'required|min:3',
        'category_id' => 'required|exists:categories,id',
        'description' => 'nullable|string',
        'is_featured' => 'boolean',
        'is_published' => 'boolean',
        'newImages.*' => 'nullable|image|max:2048', // Validate each image in the array
    ];

    public function mount()
    {
        $this->categories = Category::orderBy('name')->get();
        $this->loadProjects();
    }

    public function loadProjects()
    {
        $this->projects = Project::with('category')->orderBy('order')->get();
    }


    public function save()
    {
        $this->validate();

        if ($this->editingId) {
            $project = Project::find($this->editingId);
            $project->update([
                'title' => $this->title,
                'slug' => Str::slug($this->title),
                'category_id' => $this->category_id,
                'description' => $this->description,
                'is_featured' => $this->is_featured,
                'is_published' => $this->is_published,
            ]);
            session()->flash('message', 'Project updated successfully.');
            $this->processImages($project->id);
            $this->hideAddProject(); // Hide modal after creation
        } else {
            $project = Project::create([
                'title' => $this->title,
                'slug' => Str::slug($this->title),
                'category_id' => $this->category_id,
                'description' => $this->description,
                'is_featured' => $this->is_featured,
                'is_published' => $this->is_published,
                'order' => Project::max('order') + 1,
            ]);
            session()->flash('message', 'Project created successfully.');
            $this->processImages($project->id);
            $this->hideAddProject(); // Hide modal after creation
        }

        $this->loadProjects();
    }

    public function processImages($projectId)
    {
        if (empty($this->newImages)) {
            return;
        }

        $project = Project::find($projectId);
        $isFirstImage = $project->images()->doesntExist();

        foreach ($this->newImages as $index => $newImage) {
            $imagePath = $newImage->store('projects/' . $project->slug, 'r2'); // Store in 'projects/{project_slug}' folder on 'r2' disk

            $is_primary = false;
            // If it's the very first image for this project, or if no primary image exists yet,
            // make this one primary.
            if ($isFirstImage && $index === 0) {
                $is_primary = true;
            } elseif (!$project->images()->where('is_primary', true)->exists()) {
                $is_primary = true;
            }

            // If a new image is set as primary, ensure old primary is demoted.
            if ($is_primary) {
                $project->images()->update(['is_primary' => false]);
            }

            $project->images()->create([
                'image_path' => $imagePath,
                'caption' => null, // Caption can be added later or via another input
                'order' => $project->images()->max('order') + 1,
                'is_primary' => $is_primary,
            ]);
        }
        $this->reset('newImages'); // Clear uploaded images after processing
    }

    public function removeImage($imageId)
    {
        $projectImage = ProjectImage::find($imageId);

        if (!$projectImage) {
            return;
        }

        $projectId = $projectImage->project_id;

        try {
            if (!Storage::disk('r2')->delete($projectImage->image_path)) {
                Log::error("Failed to delete image from R2: " . $projectImage->image_path);
                session()->flash('message', 'Failed to delete image from storage. Please check logs.'); // Changed from 'error' to 'message' to match existing flash message type
            }
        } catch (\Exception $e) {
            Log::error("Exception deleting image from R2: " . $e->getMessage() . " Path: " . $projectImage->image_path);
            session()->flash('message', 'An error occurred while deleting image from storage. Please check logs.'); // Changed from 'error' to 'message'
        }

        $projectImage->delete();

        // If the deleted image was primary, assign a new primary if other images exist
        if ($projectImage->is_primary) {
            $project = Project::find($projectId);
            $newPrimary = $project->images()->orderBy('order')->first();
            if ($newPrimary) {
                $newPrimary->update(['is_primary' => true]);
            }
        }

        // Refresh the images for the current project in the modal
        if ($this->editingId === $projectId) {
            $project = Project::with('images')->find($projectId);
            $this->images = $project->images->map(function ($image) {
                return [
                    'path' => $image->image_path,
                    'id' => $image->id,
                ];
            })->toArray();
        }
        session()->flash('message', 'Image removed successfully.');
        $this->loadProjects(); // Reload projects to reflect changes
    }

    public function edit($id)
    {
        $project = Project::with('images')->find($id);
        $this->editingId = $id;
        $this->title = $project->title;
        $this->category_id = $project->category_id;
        $this->description = $project->description;
        $this->is_featured = $project->is_featured;
        $this->is_published = $project->is_published;
        $this->images = $project->images->map(function ($image) {
            return [
                'path' => $image->image_path, // Changed to image_path
                'id' => $image->id,
            ];
        })->toArray();
        $this->showAddProjectModal = true;
    }

    public function cancelEdit()
    {
        $this->reset(['title', 'category_id', 'description', 'is_featured', 'is_published', 'editingId', 'newImages']); // Also reset newImages
        $this->is_published = true;
    }

    public function toggleFeatured($id)
    {
        $project = Project::find($id);
        $project->update(['is_featured' => !$project->is_featured]);
        $this->loadProjects();
    }

    public function togglePublished($id)
    {
        $project = Project::find($id);
        $project->update(['is_published' => !$project->is_published]);
        $this->loadProjects();
    }

    public function delete($id)
    {
        $project = Project::with('images')->find($id);
        foreach ($project->images as $image) {
            Storage::disk('r2')->delete($image->image_path); // Changed to image_path
        }
        $project->delete();
        session()->flash('message', 'Project deleted successfully.');
        $this->loadProjects();
    }

    public function toggleAddProject()
    {
        if ($this->showAddProjectModal) {
            $this->hideAddProject();
        } else {
            $this->showAddProject();
        }
    }

    public function showAddProject()
    {
        $this->resetProjectForm();
        $this->showAddProjectModal = true;
    }

    public function hideAddProject()
    {
        $this->showAddProjectModal = false;
        $this->resetProjectForm();
    }

    protected function resetProjectForm()
    {
        $this->reset(['title', 'category_id', 'description', 'is_featured', 'is_published', 'editingId', 'newImages']);
        $this->is_published = true;
    }

    public function showAddCategory()
    {
        $this->showAddCategoryModal = true;
    }

    public function hideAddCategory()
    {
        $this->showAddCategoryModal = false;
        $this->reset(['newCategoryName', 'newCategoryDescription']);
    }

    public function saveNewCategory()
    {
        $this->validate([
            'newCategoryName' => 'required|min:2',
            'newCategoryDescription' => 'nullable|string',
        ]);

        $category = Category::create([
            'name' => $this->newCategoryName,
            'slug' => Str::slug($this->newCategoryName),
            'description' => $this->newCategoryDescription,
            'order' => Category::max('order') + 1,
        ]);

        $this->categories = Category::orderBy('name')->get();
        $this->category_id = $category->id;
        $this->hideAddCategory();
        session()->flash('message', 'Category added!');
    }

    public function toggleProjectImages($projectId)
    {
        $this->showProjectImages[$projectId] = !($this->showProjectImages[$projectId] ?? false);
    }

    public function render()
    {
        return view('livewire.dashboard.projects');
    }
}
