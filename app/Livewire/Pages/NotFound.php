<?php

namespace App\Livewire\Pages;

use Livewire\Component;

class NotFound extends Component
{
    public function render()
    {
        return view('livewire.pages.404')
            ->layout('components.layouts.landing')
            ->with(['title' => 'Page Not Found']);
    }
}
