<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HeroImage extends Model
{
    const LABEL_MASK_DETAIL = 'Mask Detail';
    const LABEL_FINISHED_PROP = 'Finished Prop';
    const LABEL_ARTISAN_HANDS = 'Artisan Hands';
    const LABEL_WORKSHOP_TOOLS = 'Workshop Tools';

    protected $fillable = [
        'label',
        'image_path',
        'position',
    ];

    public function getImageUrlAttribute(): string
    {
        return \Illuminate\Support\Facades\Storage::disk('r2')->url($this->image_path);
    }
}
