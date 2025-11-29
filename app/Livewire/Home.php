<?php

namespace App\Livewire;

use App\Models\Testimonial;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout(name: 'components.layouts.landing')]
class Home extends Component
{
    public function render()
    {
        return view('livewire.pages.home', [
            'testimonials' => Testimonial::where('is_published', true)->get(),
        ]);
    }
}
