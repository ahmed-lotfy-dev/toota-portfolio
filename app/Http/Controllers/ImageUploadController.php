<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Laravel\Facades\Image;

class ImageUploadController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'image' => 'required|image|max:15360', // 15MB
            'path' => 'required|string|regex:/^[a-zA-Z0-9\/_-]+$/', // Prevent path traversal
            'title' => 'nullable|string|max:255',
        ]);

        $file = $request->file('image');
        $destinationPath = $request->input('path');
        $title = $request->input('title');

        /** @var \Illuminate\Filesystem\FilesystemAdapter $disk */
        $disk = Storage::disk('r2');

        try {
            // Optimize the image
            $image = Image::read($file->getRealPath());
            $image->orient();

            // 2. Clear GPS data and Orientation flags but keep other technical metadata
            $exif = $image->exif();
            if ($exif instanceof \Intervention\Image\Interfaces\CollectionInterface) {
                // Remove GPS (Privacy) and Orientation (Redundancy) tags
                $filteredData = array_filter($exif->toArray(), function ($key) {
                    $k = strtoupper((string) $key);
                    return !str_starts_with($k, 'GPS') && !str_contains($k, 'ORIENTATION');
                }, ARRAY_FILTER_USE_KEY);

                // Create a new collection and set it back to the image
                $image->setExif(new \Intervention\Image\Collection($filteredData));
            }

            // Resize if width is greater than 2500px, maintaining aspect ratio
            if ($image->width() > 2500) {
                $image->scale(width: 2500);
            }

            // Encode to WebP with 90% quality - strip: false to keep our filtered EXIF
            $encoded = $image->toWebp(quality: 90, strip: true);

            // Generate filename
            $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $sluggedName = Str::slug($originalName);

            if ($title) {
                $filename = Str::slug($title) . '-' . $sluggedName . '-' . Str::random(6) . '.webp';
            } else {
                $filename = $sluggedName . '-' . Str::random(6) . '.webp';
            }

            // Store the optimized image directly to R2
            $fullPath = rtrim($destinationPath, '/') . '/' . $filename;
            $disk->put($fullPath, (string) $encoded);

            return response()->json([
                'path' => $fullPath,
                'url' => $disk->url($fullPath),
                'width' => $image->width(),
                'height' => $image->height(),
            ]);

        } catch (\Exception $e) {
            // Fallback: If optimization fails, store original file
            logger()->warning('Image optimization failed: ' . $e->getMessage());

            $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $sluggedName = Str::slug($originalName);
            $extension = $file->getClientOriginalExtension();

            if ($title) {
                $filename = Str::slug($title) . '-' . $sluggedName . '-' . Str::random(6) . '.' . $extension;
            } else {
                $filename = $sluggedName . '-' . Str::random(6) . '.' . $extension;
            }

            $path = $file->storeAs($destinationPath, $filename, 'r2');

            // For fallback, we still want to try to get dimensions if possible
            $width = null;
            $height = null;
            try {
                $image = Image::read($file->getRealPath());
                $width = $image->width();
                $height = $image->height();
            } catch (\Exception $dimError) {
                // Ignore dimension errors in fallback
            }

            return response()->json([
                'path' => $path,
                'url' => $disk->url($path),
                'width' => $width,
                'height' => $height,
            ]);
        }
    }
}
