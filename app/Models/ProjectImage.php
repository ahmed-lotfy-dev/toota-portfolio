<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class ProjectImage extends Model
{
    protected $fillable = [
        'project_id',
        'image_path',
        'caption',
        'order',
        'is_primary',
    ];

    protected $casts = [
        'is_primary' => 'boolean',
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    /**
     * Get the full URL for the image.
     *
     * @return string
     */
    public function getUrlAttribute()
    {
        return Storage::disk('r2')->url($this->image_path);
    }
}
