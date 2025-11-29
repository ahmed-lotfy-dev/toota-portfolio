<?php

namespace App\Livewire\Forms;

use App\Models\Project;
use Livewire\Attributes\Rule;
use Livewire\Form;

class ProjectForm extends Form
{
    public ?Project $project = null;
    
    public $title = '';
    public $category_id = '';
    public $description = '';
    public $is_featured = false;
    public $is_published = true;
    public $newImages = [];

    public function rules()
    {
        return [
            'title' => 'required|min:3',
            'category_id' => 'required|exists:categories,id',
            'description' => 'nullable|string',
            'is_featured' => 'boolean',
            'is_published' => 'boolean',
            'newImages.*' => 'nullable|image|max:2048', // Validate each image in the array
        ];
    }

    public function messages()
    {
        return [
            'newImages.*.image' => 'Each file must be an image.',
            'newImages.*.max' => 'Each image must not be greater than 2MB.',
        ];
    }

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
