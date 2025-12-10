<?php

namespace App\Services;

use App\Models\Category;
use App\Models\Project;
use App\Models\Testimonial;

class DataExportService
{
  public function exportToArray(): array
  {
    return [
      'meta' => [
        'exported_at' => now()->toIso8601String(),
        'version' => '1.0',
      ],
      'categories' => Category::orderBy('order')->get()->map(function ($category) {
        return $category->only(['id', 'name', 'slug', 'description', 'order', 'created_at', 'updated_at']);
      }),
      'projects' => Project::with('images', 'category')->orderBy('order')->get()->map(function ($project) {
        $data = $project->only([
          'id',
          'category_id',
          'title',
          'slug',
          'description',
          'is_featured',
          'is_published',
          'order',
          'created_at',
          'updated_at'
        ]);

        $data['category_slug'] = $project->category?->slug;

        $data['images'] = $project->images->map(function ($image) {
          return $image->only(['id', 'image_path', 'caption', 'order', 'is_primary', 'created_at', 'updated_at']);
        });

        return $data;
      }),
      'testimonials' => Testimonial::all()->map(function ($testimonial) {
        return $testimonial->only(['id', 'name', 'title', 'body', 'is_published', 'created_at', 'updated_at']);
      }),
    ];
  }

  public function exportToJson(): string
  {
    return json_encode($this->exportToArray(), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
  }
}
