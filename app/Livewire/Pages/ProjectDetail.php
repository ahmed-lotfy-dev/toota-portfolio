<?php

namespace App\Livewire\Pages;

use App\Models\Project;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout(name: 'components.layouts.landing')]
class ProjectDetail extends Component
{
    public Project $project;

    public function mount($slug)
    {
        $this->project = Project::where('slug', $slug)->firstOrFail();
    }

    public function render()
    {
        return view('livewire.pages.project-detail');
    }
}