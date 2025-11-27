<?php

namespace App\Livewire\Dashboard;

use App\Models\Category;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Illuminate\Support\Str;

#[Layout('components.layouts.dashboard')]
class Categories extends Component
{
    public $categories;
    public $name = '';
    public $description = '';
    public $editingId = null;
    public $showAddCategoryModal = false;
    public $newCategoryName = '';
    public $newCategoryDescription = '';
    
    protected $rules = [
        'name' => 'required|min:2',
        'description' => 'nullable|string',
    ];

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
        $this->validate();

        if ($this->editingId) {
            $category = Category::find($this->editingId);
            $category->update([
                'name' => $this->name,
                'slug' => Str::slug($this->name),
                'description' => $this->description,
            ]);
            session()->flash('message', 'Category updated successfully.');
        } else {
            Category::create([
                'name' => $this->name,
                'slug' => Str::slug($this->name),
                'description' => $this->description,
                'order' => Category::max('order') + 1,
            ]);
            session()->flash('message', 'Category created successfully.');
        }

        $this->reset(['name', 'description', 'editingId']);
        $this->loadCategories();
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
        Category::create([
            'name' => $this->newCategoryName,
            'slug' => \Illuminate\Support\Str::slug($this->newCategoryName),
            'description' => $this->newCategoryDescription,
            'order' => Category::max('order') + 1,
        ]);
        $this->loadCategories();
        $this->hideAddCategory();
        session()->flash('message', 'Category added!');
    }

    public function edit($id)
    {
        $category = Category::find($id);
        $this->editingId = $id;
        $this->name = $category->name;
        $this->description = $category->description;
    }

    public function cancelEdit()
    {
        $this->reset(['name', 'description', 'editingId']);
    }

    public function delete($id)
    {
        Category::find($id)->delete();
        session()->flash('message', 'Category deleted successfully.');
        $this->loadCategories();
    }

    public function render()
    {
        return view('livewire.dashboard.categories');
    }
}
