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
            'newImages' => 'nullable|array|max:10', // Limit number of files
            'newImages.*' => 'nullable|image|max:15360', // Validate each image in the array
        ];
    }

    public function messages()
    {
        return [
            'newImages.max' => 'You can upload a maximum of 10 images at once.',
            'newImages.*.image' => 'Each file must be an image.',
            'newImages.*.max' => 'Each image must not be greater than 15MB.',
        ];
    }

    public function attributes()
    {
        return [
            'newImages' => 'images',
            'newImages.*' => 'image',
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
