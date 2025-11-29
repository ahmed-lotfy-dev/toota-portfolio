<?php

namespace App\Livewire\Dashboard;

use App\Livewire\Forms\ProjectForm;
use App\Models\Category;
use App\Models\Project;
use App\Models\ProjectImage;
use App\Services\ProjectService;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\WithFileUploads;
use Illuminate\Support\Str;

#[Layout('components.layouts.dashboard')]
class Projects extends Component
{
    use WithFileUploads;

    public ProjectForm $form;

    public $projects;
    public $categories;
    public $showProjectImages = [];

    public $showAddProjectModal = false;
    public $showAddCategoryModal = false;

    public $newCategoryName = '';
    public $newCategoryDescription = '';

    public function mount()
    {
        $this->categories = Category::orderBy('name')->get();
        $this->loadProjects();
    }

    public function loadProjects()
    {
        $this->projects = Project::with('category', 'images')->orderBy('order')->get();
    }

    public function save(ProjectService $projectService)
    {
        $this->form->validate();
        $projectData = $this->form->all();

        try {
            if ($this->form->project) {
                $projectService->update($this->form->project, $projectData, $this->form->newImages);
                session()->flash('message', 'Project updated successfully.');
            } else {
                $projectService->create($projectData, $this->form->newImages);
                session()->flash('message', 'Project created successfully.');
            }

            $this->hideAddProject();
        } catch (\Exception $e) {
            logger()->error('Project save failed: ' . $e->getMessage());
            $this->addError('form.newImages', 'An error occurred during the process. Please try again.');
        }

        $this->loadProjects();
    }

    public function removeImage(ProjectImage $image, ProjectService $projectService)
    {
        try {
            $projectService->deleteImage($image);
            session()->flash('message', 'Image removed successfully.');
            if ($this->form->project) {
                $this->edit($this->form->project->id);
            }
        } catch (\Exception $e) {
            logger()->error('Image deletion failed: ' . $e->getMessage());
            session()->flash('message', 'Failed to delete image.');
        }
        $this->loadProjects();
    }

    public function delete(Project $project, ProjectService $projectService)
    {
        try {
            $projectService->delete($project);
            session()->flash('message', 'Project deleted successfully.');
        } catch (\Exception $e) {
            logger()->error('Project deletion failed: ' . $e->getMessage());
            session()->flash('message', 'Failed to delete project.');
        }
        $this->loadProjects();
    }

    public function edit($id)
    {
        $project = Project::with('images')->find($id);
        $this->form->setProject($project);
        $this->showAddProjectModal = true;
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

    public function showAddProject()
    {
        $this->form->resetForm();
        $this->showAddProjectModal = true;
    }

    public function hideAddProject()
    {
        $this->showAddProjectModal = false;
        $this->form->resetForm();
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
        $this->form->category_id = $category->id;
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
