<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    protected $fillable = [
        'category_id',
        'title',
        'slug',
        'description',
        'is_featured',
        'is_published',
        'order',
    ];

    protected $casts = [
        'is_featured' => 'boolean',
        'is_published' => 'boolean',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function images()
    {
        return $this->hasMany(ProjectImage::class)->orderBy('order');
    }

    public function primaryImage()
    {
        return $this->hasOne(ProjectImage::class)
            ->where('is_primary', true)
            ->orderBy('order');
    }

    public function getMainImageAttribute()
    {
        return $this->primaryImage ?: $this->images->first();
    }

    protected static function booted()
    {
        static::deleting(function ($project) {
            foreach ($project->images as $image) {
                if ($image->image_path) {
                    \Illuminate\Support\Facades\Storage::disk('r2')->delete($image->image_path);
                }
            }
        });
    }
}
