<?php

namespace App\Livewire\Dashboard;

use App\Models\Project;
use App\Services\ProjectService;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.dashboard')]
class ProjectDetail extends Component
{
    public Project $project;

    public function mount(Project $project)
    {
        $this->project = $project;
    }

    public function edit()
    {
        $this->dispatch('editProjectForm', $this->project->id);
        $this->dispatch('open-modal', name: 'project-modal');
    }

    public function delete(ProjectService $projectService)
    {
        try {
            $projectService->delete($this->project);
            session()->flash('message', 'Project deleted successfully.');
            return redirect()->route('projects.index');
        } catch (\Exception $e) {
            logger()->error('Project deletion failed: ' . $e->getMessage());
            session()->flash('message', 'Failed to delete project.');
        }
    }

    public function render()
    {
        return view('livewire.dashboard.project-detail');
    }
}
