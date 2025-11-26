<?php

namespace App\Livewire\Dashboard;

use App\Models\Category;
use App\Models\Project;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Illuminate\Support\Str;

#[Layout('components.layouts.dashboard')]
class Projects extends Component
{
    public $projects;
    public $categories;

    public $title = '';
    public $category_id = '';
    public $description = '';
    public $is_featured = false;
    public $is_published = true;
    public $editingId = null;

    public $showAddProjectModal = false;
    public $showAddCategoryModal = false;
    public $newCategoryName = '';
    public $newCategoryDescription = '';


    protected $rules = [
        'title' => 'required|min:3',
        'category_id' => 'required|exists:categories,id',
        'description' => 'nullable|string',
        'is_featured' => 'boolean',
        'is_published' => 'boolean',
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
        } else {
            Project::create([
                'title' => $this->title,
                'slug' => Str::slug($this->title),
                'category_id' => $this->category_id,
                'description' => $this->description,
                'is_featured' => $this->is_featured,
                'is_published' => $this->is_published,
                'order' => Project::max('order') + 1,
            ]);
            session()->flash('message', 'Project created successfully.');
            $this->hideAddProject(); // Hide modal after creation
        }

        $this->loadProjects();
    }


    public function edit($id)
    {
        $project = Project::find($id);
        $this->editingId = $id;
        $this->title = $project->title;
        $this->category_id = $project->category_id;
        $this->description = $project->description;
        $this->is_featured = $project->is_featured;
        $this->is_published = $project->is_published;
    }

    public function cancelEdit()
    {
        $this->reset(['title', 'category_id', 'description', 'is_featured', 'is_published', 'editingId']);
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
        Project::find($id)->delete();
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
        $this->reset(['title', 'category_id', 'description', 'is_featured', 'is_published', 'editingId']);
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

    public function render()
    {
        return view('livewire.dashboard.projects');
    }
}
