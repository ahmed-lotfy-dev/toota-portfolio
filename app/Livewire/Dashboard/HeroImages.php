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
    public $uploadImages = [];

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

    public function updatedUploadImages($value, $key)
    {
        $this->validate([
            "uploadImages.{$key}" => 'image|max:15360',
        ]);
    }

    public function saveImage($position)
    {
        $labels = [
            1 => HeroImage::LABEL_MASK_DETAIL,
            2 => HeroImage::LABEL_WORKSHOP_TOOLS,
            3 => HeroImage::LABEL_FINISHED_PROP,
            4 => HeroImage::LABEL_ARTISAN_HANDS,
        ];

        $this->validate([
            "uploadImages.{$position}" => 'required|image|max:15360',
        ]);

        $file = $this->uploadImages[$position];

        $image = \Intervention\Image\Laravel\Facades\Image::read($file);
        if ($image->width() > 2500) {
            $image->scale(width: 2500);
        }
        $encoded = $image->toWebp(quality: 90);

        $filename = 'hero-' . $position . '-' . \Illuminate\Support\Str::random(8) . '.webp';
        $fullPath = 'hero-images/' . $filename;

        if ($this->heroImages[$position]) {
            Storage::disk('r2')->delete($this->heroImages[$position]->image_path);

            $this->heroImages[$position]->update([
                'image_path' => $fullPath,
            ]);
        } else {
            HeroImage::create([
                'label' => $labels[$position],
                'image_path' => $fullPath,
                'position' => $position,
            ]);
        }

        Storage::disk('r2')->put($fullPath, (string) $encoded);

        $this->uploadImages[$position] = null;
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
