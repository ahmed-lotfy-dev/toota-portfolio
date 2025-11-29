<?php

namespace App\Livewire\Forms;

use App\Models\Testimonial;
use Livewire\Attributes\Rule;
use Livewire\Form;

class TestimonialForm extends Form
{
    public ?Testimonial $testimonial = null;

    #[Rule('required|string|min:3')]
    public $name = '';

    #[Rule('nullable|string|min:3')]
    public $title = '';

    #[Rule('required|string|min:10')]
    public $body = '';

    #[Rule('boolean')]
    public $is_published = false;

    public function setTestimonial(Testimonial $testimonial)
    {
        $this->testimonial = $testimonial;
        $this->name = $testimonial->name;
        $this->title = $testimonial->title;
        $this->body = $testimonial->body;
        $this->is_published = $testimonial->is_published;
    }

    public function resetForm()
    {
        $this->reset();
        $this->is_published = false;
    }
}
