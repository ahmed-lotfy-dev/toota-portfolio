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
            'path' => 'required|string',
        ]);

        $file = $request->file('image');
        $destinationPath = $request->input('path');

        try {
            // Optimize the image
            $image = Image::read($file);

            // Resize if width is greater than 2500px, maintaining aspect ratio
            if ($image->width() > 2500) {
                $image->scale(width: 2500);
            }

            // Encode to WebP with 90% quality
            $encoded = $image->toWebp(quality: 90);

            // Generate filename with .webp extension
            $filename = Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) . '-' . Str::random(6) . '.webp';
            
            // Store the optimized image directly to R2
            $fullPath = rtrim($destinationPath, '/') . '/' . $filename;
            Storage::disk('r2')->put($fullPath, (string) $encoded);

            return response()->json([
                'path' => $fullPath,
                'url' => Storage::disk('r2')->url($fullPath),
            ]);

        } catch (\Exception $e) {
            // Fallback: If optimization fails, store original file
            logger()->warning('Image optimization failed: ' . $e->getMessage());

            $filename = Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) . '-' . Str::random(6) . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs($destinationPath, $filename, 'r2');

            return response()->json([
                'path' => $path,
                'url' => Storage::disk('r2')->url($path),
            ]);
        }
    }
}
