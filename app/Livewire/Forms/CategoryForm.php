<?php

namespace App\Livewire\Forms;

use App\Models\Category;
use Livewire\Attributes\Rule;
use Livewire\Form;

class CategoryForm extends Form
{
    public ?Category $category = null;

    #[Rule('required|min:2')]
    public $name = '';

    #[Rule('nullable|string')]
    public $description = '';

    public function setCategory(Category $category)
    {
        $this->category = $category;
        $this->name = $category->name;
        $this->description = $category->description;
    }

    public function resetForm()
    {
        $this->reset();
    }
}
