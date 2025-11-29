<?php

namespace App\Livewire\Dashboard;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\WithPagination;
use App\Models\Project;
use App\Services\ProjectService;

#[Layout('components.layouts.dashboard')]
class Projects extends Component
{
    use WithPagination;

    protected $paginationTheme = 'tailwind';
    public $paginate_count = 10;
    
    // Listen for events from ProjectFormModalContent
    protected $listeners = [
        'projectSaved' => 'loadProjectsAndCloseModal',
        'closeModal' => 'resetModalState'
    ];

    public function mount()
    {
        // Initial setup for projects, if any
    }

    public function updatedPaginateCount()
    {
        $this->resetPage();
    }

    public function loadProjectsAndCloseModal()
    {
        // This will trigger a re-render of the projects table
        $this->resetPage();
        session()->flash('message', 'Project saved successfully.'); // Display success message
    }

    public function resetModalState()
    {
        // Reset any modal-related state if necessary
        // The shared modal already handles its own closing logic
    }

    public function edit(Project $project)
    {
        $this->dispatch('editProjectForm', $project->id); // Dispatch event to form component
        $this->dispatch('open-modal', name: 'project-modal');
    }
    
    public function showProjectModal()
    {
        $this->dispatch('resetProjectForm'); // Reset form for new project
        $this->dispatch('open-modal', name: 'project-modal');
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
        $this->resetPage(); // Reset pagination after deleting project
    }

    public function toggleFeatured($id)
    {
        $project = Project::find($id);
        $project->update(['is_featured' => !$project->is_featured]);
        $this->resetPage(); // Reset pagination after toggling featured status
    }

    public function togglePublished($id)
    {
        $project = Project::find($id);
        $project->update(['is_published' => !$project->is_published]);
        $this->resetPage(); // Reset pagination after toggling published status
    }

    public function toggleProjectImages($projectId)
    {
        $this->showProjectImages[$projectId] = !($this->showProjectImages[$projectId] ?? false);
    }
    
    public $showProjectImages = []; // Still needed for displaying existing images

    public function render()
    {
        $projects = Project::with('category', 'images')->orderBy('order')->get();
        return view('livewire.dashboard.projects', [
            'projects' => $projects,
        ]);
    }
}
