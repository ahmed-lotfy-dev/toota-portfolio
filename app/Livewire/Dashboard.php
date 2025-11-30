<?php

namespace App\Livewire;

use App\Models\Category;
use App\Models\Project;
use App\Models\Testimonial;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.dashboard')]
class Dashboard extends Component
{
    public function render()
    {
        $projectCount = Project::count();
        $categoryCount = Category::count();
        $testimonialCount = Testimonial::count();

        return view('livewire.pages.dashboard', [
            'projectCount' => $projectCount,
            'categoryCount' => $categoryCount,
            'testimonialCount' => $testimonialCount,
        ]);
    }
}

