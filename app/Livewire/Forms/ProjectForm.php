<?php

namespace App\Livewire\Forms;

use App\Models\Project;
use Livewire\Attributes\Rule;
use Livewire\Form;

class ProjectForm extends Form
{
    public ?Project $project = null;

    #[Rule('required|min:3')]
    public $title = '';

    #[Rule('required|exists:categories,id')]
    public $category_id = '';

    #[Rule('nullable|string')]
    public $description = '';

    #[Rule('boolean')]
    public $is_featured = false;

    #[Rule('boolean')]
    public $is_published = true;

    #[Rule('nullable|array')]
    public $newImages = [];

    public function setProject(Project $project)
    {
        $this->project = $project;
        $this->title = $project->title;
        $this->category_id = $project->category_id;
        $this->description = $project->description;
        $this->is_featured = $project->is_featured;
        $this->is_published = $project->is_published;
    }

    public function resetForm()
    {
        $this->reset();
        $this->is_published = true; // a default value
    }
}
