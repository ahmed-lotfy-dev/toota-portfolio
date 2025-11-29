<?php

namespace App\Livewire\Dashboard;

use App\Livewire\Forms\CategoryForm;
use App\Models\Category;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Illuminate\Support\Str;

#[Layout('components.layouts.dashboard')]
class Categories extends Component
{
    public CategoryForm $form;

    public $categories;
    public $showCategoryModal = false;

    public function mount()
    {
        $this->loadCategories();
    }

    public function loadCategories()
    {
        $this->categories = Category::withCount('projects')->orderBy('order')->get();
    }

    public function save()
    {
        $this->form->validate();

        if ($this->form->category) {
            $this->form->category->update([
                'name' => $this->form->name,
                'slug' => Str::slug($this->form->name),
                'description' => $this->form->description,
            ]);
            session()->flash('message', 'Category updated successfully.');
        } else {
            Category::create([
                'name' => $this->form->name,
                'slug' => Str::slug($this->form->name),
                'description' => $this->form->description,
                'order' => Category::max('order') + 1,
            ]);
            session()->flash('message', 'Category created successfully.');
        }

        $this->hideModal();
        $this->loadCategories();
    }

    public function edit(Category $category)
    {
        $this->form->setCategory($category);
        $this->showCategoryModal = true;
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
        $this->form->resetForm();
        $this->showCategoryModal = true;
    }

    public function hideModal()
    {
        $this->showCategoryModal = false;
        $this->form->resetForm();
    }

    public function render()
    {
        return view('livewire.dashboard.categories');
    }
}
