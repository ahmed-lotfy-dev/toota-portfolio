<?php

namespace App\Livewire\Dashboard;

use App\Models\Testimonial;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.dashboard')]
class Testimonials extends Component
{
    public $name, $title, $body, $is_published = false, $testimonialId;
    public $testimonials;
    public $showModal = false;
    public $isEditing = false;

    protected $rules = [
        'name' => 'required|string|min:3',
        'body' => 'required|string|min:10',
        'title' => 'nullable|string|min:3',
        'is_published' => 'boolean',
    ];

    public function mount()
    {
        $this->testimonials = Testimonial::all();
    }

    public function render()
    {
        return view('livewire.dashboard.testimonials', [
            'testimonials' => Testimonial::all(),
        ]);
    }

    public function resetInput()
    {
        $this->name = '';
        $this->title = '';
        $this->body = '';
        $this->is_published = false;
        $this->testimonialId = null;
        $this->showModal = false;
        $this->isEditing = false;
    }

    public function createTestimonial()
    {
        $this->resetInput();
        $this->showModal = true;
        $this->isEditing = false;
    }

    public function store()
    {
        $this->validate();

        Testimonial::create([
            'name' => $this->name,
            'title' => $this->title,
            'body' => $this->body,
            'is_published' => $this->is_published,
        ]);

        session()->flash('message', 'Testimonial added successfully.');
        $this->resetInput(); // This will also close the modal
        $this->testimonials = Testimonial::all();
    }

    public function edit($id)
    {
        $testimonial = Testimonial::findOrFail($id);
        $this->name = $testimonial->name;
        $this->title = $testimonial->title;
        $this->body = $testimonial->body;
        $this->is_published = $testimonial->is_published;
        $this->testimonialId = $testimonial->id;
        $this->showModal = true;
        $this->isEditing = true;
    }

    public function update()
    {
        $this->validate();

        if ($this->testimonialId) {
            $testimonial = Testimonial::findOrFail($this->testimonialId);
            $testimonial->update([
                'name' => $this->name,
                'title' => $this->title,
                'body' => $this->body,
                'is_published' => $this->is_published,
            ]);
            session()->flash('message', 'Testimonial updated successfully.');
            $this->resetInput(); // This will also close the modal
            $this->testimonials = Testimonial::all();
        }
    }

    public function delete($id)
    {
        Testimonial::findOrFail($id)->delete();
        session()->flash('message', 'Testimonial deleted successfully.');
        $this->testimonials = Testimonial::all();
    }

    public function togglePublished($id)
    {
        $testimonial = Testimonial::findOrFail($id);
        $testimonial->is_published = !$testimonial->is_published;
        $testimonial->save();
        session()->flash('message', 'Testimonial status updated.');
        $this->testimonials = Testimonial::all();
    }
}
