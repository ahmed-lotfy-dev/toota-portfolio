<?php

namespace App\Livewire\Dashboard\Forms;

use App\Livewire\Forms\ProjectForm;
use App\Models\Category;
use App\Models\ProjectImage;
use App\Models\Project;
use App\Services\ProjectService;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Str; // Needed for slug generation if category creation is kept here temporarily

class ProjectFormModalContent extends Component
{
    use WithFileUploads;

    public ProjectForm $form;
    public $categories;
    public $showProjectImages = []; // Still needed for displaying existing images

    protected $listeners = [
        'editProjectForm' => 'edit',
        'resetProjectForm' => 'resetForm',
    ];

    public function mount()
    {
        $this->loadCategories();
    }

    public function loadCategories()
    {
        $this->categories = Category::orderBy('name')->get();
    }

    public function resetForm()
    {
        $this->form->resetForm();
    }

    public function save(ProjectService $projectService)
    {
        $this->form->validate();
        $projectData = $this->form->only(['title', 'category_id', 'description', 'is_featured', 'is_published']);

        try {
            if ($this->form->project) {
                $projectService->update($this->form->project, $projectData, $this->form->newImages);
                session()->flash('message', 'Project updated successfully.');
            } else {
                $projectService->create($projectData, $this->form->newImages);
                session()->flash('message', 'Project created successfully.');
            }

            $this->dispatch('projectSaved'); // Notify parent component (Projects)
            $this->dispatch('close-modal'); // Close the modal
            $this->resetForm();
        } catch (\Exception $e) {
            logger()->error('Project save failed: ' . $e->getMessage());
            $this->addError('form.newImages', 'An error occurred during the process. Please try again.');
        }
    }

    public function removeImage(ProjectImage $image, ProjectService $projectService)
    {
        try {
            $projectService->deleteImage($image);
            session()->flash('message', 'Image removed successfully.');
            // Re-fetch project to update image list if it's an existing project
            if ($this->form->project) {
                $this->form->setProject($this->form->project->fresh());
            }
        } catch (\Exception $e) {
            logger()->error('Image deletion failed: ' . $e->getMessage());
            session()->flash('message', 'Failed to delete image.');
        }
    }
    
    public function setPrimary(ProjectImage $image, ProjectService $projectService)
    {
        try {
            $projectService->setPrimaryImage($image);
            session()->flash('message', 'Primary image updated.');
            // Re-fetch project to update image list
            if ($this->form->project) {
                $this->form->setProject($this->form->project->fresh());
            }
        } catch (\Exception $e) {
            logger()->error('Failed to set primary image: ' . $e->getMessage());
            session()->flash('message', 'Failed to set primary image.');
        }
    }

    public function edit($projectId)
    {
        $project = Project::with('images')->find($projectId);
        if ($project) {
            $this->form->setProject($project);
            $this->loadCategories(); // Ensure categories are loaded for the form
        } else {
            session()->flash('error', 'Project not found.');
            $this->dispatch('close-modal');
        }
    }



    public function render()
    {
        return view('livewire.dashboard.forms.project-form-modal-content');
    }
}
