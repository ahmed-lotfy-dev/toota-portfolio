<?php

namespace App\Livewire\Dashboard\Forms;

use App\Livewire\Forms\CategoryForm;
use Livewire\Component;
use Illuminate\Support\Str;
use App\Models\Category; // Import Category model

class CategoryFormModalContent extends Component
{
    public CategoryForm $form;

    protected $listeners = [
        'editCategoryForm' => 'edit',
        'resetCategoryForm' => 'resetFormFields'
    ];

    public function mount()
    {
        // This will be called when the component is first mounted
        // No initial data needed here as 'edit' method will handle population
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

        $this->dispatch('close-modal'); // Close the shared modal
        $this->dispatch('categorySaved'); // Notify parent component (Categories) to reload categories
    }

    public function edit($categoryId)
    {
        $category = Category::find($categoryId);
        if ($category) {
            $this->form->setCategory($category);
        }
    }

    public function resetFormFields()
    {
        $this->form->resetForm();
    }

    public function render()
    {
        return view('livewire.dashboard.forms.category-form-modal-content');
    }
}
