<?php

namespace App\View\Components;

use App\Models\HeroImage;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Hero extends Component
{
    public $heroImages;

    public function __construct()
    {
        $images = HeroImage::orderBy('position')->get()->keyBy('position');

        $this->heroImages = [
            1 => $images->get(1),
            2 => $images->get(2),
            3 => $images->get(3),
            4 => $images->get(4),
        ];
    }

    public function render(): View|Closure|string
    {
        return view('components.landing.hero');
    }
}
