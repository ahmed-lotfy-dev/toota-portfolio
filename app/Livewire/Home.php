<?php

namespace App\Livewire;

use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout(name: 'components.layouts.landing')]
class Home extends Component
{
    public function render()
    {
        return view('livewire.pages.home');
    }
}
