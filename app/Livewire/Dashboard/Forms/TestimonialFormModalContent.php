<?php

namespace App\Livewire\Dashboard\Forms;

use App\Livewire\Forms\TestimonialForm;
use App\Models\Testimonial;
use Livewire\Component;

class TestimonialFormModalContent extends Component
{
    public TestimonialForm $form;

    protected $listeners = [
        'editTestimonialForm' => 'edit',
        'resetTestimonialForm' => 'resetForm',
    ];

    public function resetForm()
    {
        $this->form->resetForm();
    }

    public function edit($testimonialId)
    {
        $testimonial = Testimonial::find($testimonialId);
        if ($testimonial) {
            $this->form->setTestimonial($testimonial);
        } else {
            session()->flash('error', 'Testimonial not found.');
            $this->dispatch('close-modal');
        }
    }

    public function save()
    {
        $this->form->validate();

        if ($this->form->testimonial) {
            $this->form->testimonial->update($this->form->only(['name', 'title', 'body', 'is_published']));
            session()->flash('message', 'Testimonial updated successfully.');
        } else {
            Testimonial::create($this->form->only(['name', 'title', 'body', 'is_published']));
            session()->flash('message', 'Testimonial created successfully.');
        }

        $this->dispatch('testimonialSaved'); // Notify parent component (Testimonials)
        $this->dispatch('close-modal'); // Close the modal
        $this->resetForm();
    }

    public function render()
    {
        return view('livewire.dashboard.forms.testimonial-form-modal-content');
    }
}
