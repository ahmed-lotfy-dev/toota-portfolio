<?php

namespace App\Livewire;

use App\Models\Category;
use App\Models\Project;
use Livewire\Component;

class Projects extends Component
{
    public $activeCategory = 'all';
    public $limit = 6;
    public $totalProjects;

    public function mount()
    {
        $this->totalProjects = Project::where('is_published', true)->count();
    }

    public function filter($slug)
    {
        $this->activeCategory = $slug;
    }

    public function loadMore()
    {
        $this->limit = 12;
    }

    public function getCategoriesProperty()
    {
        return Category::orderBy('order')->get();
    }

    public function getFilteredProjectsProperty()
    {
        $query = Project::with(['category', 'images'])
            ->where('is_published', true)
            ->orderBy('order');

        if ($this->activeCategory !== 'all') {
            $query->whereHas('category', function ($q) {
                $q->where('slug', $this->activeCategory);
            });
        }

        return $query->limit($this->limit)->get();
    }

    public function render()
    {
        return view('livewire.projects.index');
    }
}
