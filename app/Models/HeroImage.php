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
        'width',
        'height',
        'aspect_ratio',
        'ratio_mode',
    ];

    protected $casts = [
        'width' => 'integer',
        'height' => 'integer',
        'aspect_ratio' => 'decimal:4',
        'ratio_mode' => 'string',
    ];

    public function getImageUrlAttribute(): string
    {
        return \Illuminate\Support\Facades\Storage::disk('r2')->url($this->image_path);
    }

    public function getDynamicAspectRatioAttribute(): float
    {
        if ($this->ratio_mode === 'original' && $this->aspect_ratio) {
            return (float) $this->aspect_ratio;
        }

        // Context-aware defaults based on position (Presets)
        // This is used if mode is 'preset' OR if 'original' has no metadata
        return match ($this->position) {
            1 => 0.75,   // Mask Detail (Portrait)
            2 => 1.7778, // Workshop Tools (Landscape)
            3 => 1.0,    // Finished Prop (Square)
            4 => 0.6667, // Artisan Hands (Portrait)
            default => 1.0
        };
    }
}
