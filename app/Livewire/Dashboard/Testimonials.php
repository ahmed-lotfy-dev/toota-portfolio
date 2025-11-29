<?php

namespace App\Livewire\Dashboard;

use App\Livewire\Forms\TestimonialForm;
use App\Models\Testimonial;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.dashboard')]
class Testimonials extends Component
{
    public TestimonialForm $form;
    
    public $showModal = false;

    public function save()
    {
        $this->form->validate();

        if ($this->form->testimonial) {
            $this->form->testimonial->update($this->form->all());
            session()->flash('message', 'Testimonial updated successfully.');
        } else {
            Testimonial::create($this->form->all());
            session()->flash('message', 'Testimonial created successfully.');
        }

        $this->hideModal();
    }

    public function edit(Testimonial $testimonial)
    {
        $this->form->setTestimonial($testimonial);
        $this->showModal = true;
    }

    public function delete(Testimonial $testimonial)
    {
        $testimonial->delete();
        session()->flash('message', 'Testimonial deleted successfully.');
    }

    public function togglePublished(Testimonial $testimonial)
    {
        $testimonial->update(['is_published' => !$testimonial->is_published]);
        session()->flash('message', 'Testimonial status updated.');
    }

    public function showModal()
    {
        $this->form->resetForm();
        $this->showModal = true;
    }

    public function hideModal()
    {
        $this->showModal = false;
        $this->form->resetForm();
    }
    
    public function render()
    {
        return view('livewire.dashboard.testimonials', [
            'testimonials' => Testimonial::latest()->get(),
        ]);
    }
}
