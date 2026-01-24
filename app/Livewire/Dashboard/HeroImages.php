<?php

namespace App\Livewire\Dashboard;

use App\Models\HeroImage;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Layout('components.layouts.dashboard')]
class HeroImages extends Component
{
    use WithFileUploads;

    public $heroImages = [];

    public function mount()
    {
        $this->loadHeroImages();
    }

    public function loadHeroImages()
    {
        $images = HeroImage::orderBy('position')->get()->keyBy('position');

        $this->heroImages = [
            1 => $images->get(1),
            2 => $images->get(2),
            3 => $images->get(3),
            4 => $images->get(4),
        ];
    }

    public function saveImage($position, $fullPath, $width = null, $height = null)
    {
        $labels = [
            1 => HeroImage::LABEL_MASK_DETAIL,
            2 => HeroImage::LABEL_WORKSHOP_TOOLS,
            3 => HeroImage::LABEL_FINISHED_PROP,
            4 => HeroImage::LABEL_ARTISAN_HANDS,
        ];

        $aspectRatio = ($width && $height) ? round($width / $height, 4) : null;

        // If replacing, delete the old file
        if ($this->heroImages[$position]) {
            Storage::disk('r2')->delete($this->heroImages[$position]->image_path);

            $this->heroImages[$position]->update([
                'image_path' => $fullPath,
                'width' => $width,
                'height' => $height,
                'aspect_ratio' => $aspectRatio,
            ]);
        } else {
            HeroImage::create([
                'label' => $labels[$position],
                'image_path' => $fullPath,
                'position' => $position,
                'width' => $width,
                'height' => $height,
                'aspect_ratio' => $aspectRatio,
            ]);
        }

        $this->loadHeroImages();

        session()->flash('message', 'Hero image updated successfully!');
    }

    public function deleteImage($position)
    {
        if ($this->heroImages[$position]) {
            Storage::disk('r2')->delete($this->heroImages[$position]->image_path);
            $this->heroImages[$position]->delete();
            $this->loadHeroImages();

            session()->flash('message', 'Hero image deleted successfully!');
        }
    }

    public function render()
    {
        return view('livewire.dashboard.hero-images');
    }
}
