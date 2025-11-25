<?php

namespace App\Livewire;

use Livewire\Component;

class Projects extends Component
{
    public $categories = [];
    public $projects = [];
    public $activeCategory = 'all';


    public function mount()
    {
        $json = json_decode(file_get_contents(resource_path('data/projects_data.json')), true);
        $this->categories = $json['categories'];
        $this->projects = $json['projects'];
    }


    public function filter($slug)
    {
        $this->activeCategory = $slug;
    }


    public function getFilteredProjectsProperty()
    {
        if ($this->activeCategory === 'all') {
            return $this->projects;
        }


        $category = collect($this->categories)->firstWhere('slug', $this->activeCategory);
        return collect($this->projects)->where('category_id', $category['id'])->values();
    }
    public function render()
    {
        return view('livewire.projects.index');
    }
}
