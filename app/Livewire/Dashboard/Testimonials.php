<?php

namespace App\Livewire\Dashboard;

use App\Livewire\Forms\TestimonialForm;
use App\Models\Testimonial;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('components.layouts.dashboard')]
class Testimonials extends Component
{
    use WithPagination;

    protected $paginationTheme = 'tailwind';
    public $paginate_count = 10;
    
    // Listen for events from TestimonialFormModalContent
    protected $listeners = [
        'testimonialSaved' => 'loadTestimonialsAndCloseModal',
        'closeModal' => 'resetModalState',
    ];

    public function updatedPaginateCount()
    {
        $this->resetPage();
    }

    public function loadTestimonialsAndCloseModal()
    {
        // This will trigger a re-render of the testimonials table
        $this->resetPage();
        session()->flash('message', 'Testimonial saved successfully.'); // Display success message
    }

    public function resetModalState()
    {
        // Reset any modal-related state if necessary
        // The shared modal already handles its own closing logic
    }

    public function edit(Testimonial $testimonial)
    {
        $this->dispatch('editTestimonialForm', $testimonial->id); // Dispatch event to form component
        $this->dispatch('open-modal', name: 'testimonial-modal');
    }

    public function delete(Testimonial $testimonial)
    {
        $testimonial->delete();
        session()->flash('message', 'Testimonial deleted successfully.');
        $this->resetPage();
    }

    public function togglePublished(Testimonial $testimonial)
    {
        $testimonial->update(['is_published' => !$testimonial->is_published]);
        session()->flash('message', 'Testimonial status updated.');
        $this->resetPage();
    }

    public function showModal()
    {
        $this->dispatch('resetTestimonialForm'); // Reset form for new testimonial
        $this->dispatch('open-modal', name: 'testimonial-modal');
    }
    
    public function render()
    {
        return view('livewire.dashboard.testimonials', [
            'testimonials' => Testimonial::latest()->get(),
        ]);
    }
}
