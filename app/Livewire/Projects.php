<?php

namespace App\Livewire;

use App\Models\Category;
use App\Models\Project;
use Livewire\Component;

class Projects extends Component
{
    public $activeCategory = 'all';

    public function filter($slug)
    {
        $this->activeCategory = $slug;
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

        return $query->get();
    }

    public function render()
    {
        return view('livewire.projects.index');
    }
}
