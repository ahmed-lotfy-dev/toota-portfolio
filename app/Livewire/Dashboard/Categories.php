<?php

namespace App\Livewire\Dashboard;

use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Models\Category;

#[Layout('components.layouts.dashboard')]
class Categories extends Component
{
    public $categories;
    protected $listeners = ['categorySaved' => 'loadCategories'];

    public function mount()
    {
        $this->loadCategories();
    }

    public function loadCategories()
    {
        $this->categories = Category::withCount('projects')->orderBy('order')->get();
    }

    public function edit(Category $category)
    {
        $this->dispatch('editCategoryForm', $category->id); // Dispatch event to form component
        $this->dispatch('open-modal', name: 'category-modal');
    }

    public function delete(Category $category)
    {
        if ($category->projects()->count() > 0) {
            session()->flash('error', 'Cannot delete a category that has projects assigned to it.');
            return;
        }
        $category->delete();
        session()->flash('message', 'Category deleted successfully.');
        $this->loadCategories();
    }

    public function showModal()
    {
        $this->dispatch('resetCategoryForm');
        $this->dispatch('open-modal', name: 'category-modal');
    }

    public function render()
    {
        return view('livewire.dashboard.categories');
    }
}
