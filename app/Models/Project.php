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

    // Optional: fallback helper
    public function getMainImageAttribute()
    {
        return $this->primaryImage ?: $this->images->first();
    }
}
