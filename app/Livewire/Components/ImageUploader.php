<?php

namespace App\Livewire\Components;

use Livewire\Component;
use Livewire\Attributes\Modelable;
use Illuminate\Support\Facades\Storage;

class ImageUploader extends Component
{
    #[Modelable]
    public $images = [];

    public $context = '';

    public function removeImage($index)
    {
        if (isset($this->images[$index])) {
            $path = $this->images[$index];
            
            // Delete from storage
            Storage::disk('r2')->delete($path);

            // Remove from array
            $images = $this->images;
            array_splice($images, $index, 1);
            $this->images = $images;

            // This is mostly for the client-side to know, but doesn't hurt
            session()->flash('message', 'Image removed.');
        }
    }

    public function render()
    {
        return view('livewire.components.image-uploader');
    }
}
