<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PresignedUrlController extends Controller
{
    public function generate(Request $request)
    {
        try {
            $request->validate([
                'filename' => 'required|string',
                'project_slug' => 'required|string',
            ]);

            $filename = time() . '_' . Str::random(8) . '.' . pathinfo($request->filename, PATHINFO_EXTENSION);
            $path = 'projects/' . $request->project_slug . '/' . $filename;

            // Create S3 client directly
            $config = config('filesystems.disks.r2');

            $s3Client = new \Aws\S3\S3Client([
                'version' => 'latest',
                'region' => $config['region'],
                'endpoint' => $config['endpoint'],
                'credentials' => [
                    'key' => $config['key'],
                    'secret' => $config['secret'],
                ],
                'use_path_style_endpoint' => $config['use_path_style_endpoint'] ?? false,
            ]);

            // Create presigned URL
            $command = $s3Client->getCommand('PutObject', [
                'Bucket' => $config['bucket'],
                'Key' => $path,
            ]);

            $presignedRequest = $s3Client->createPresignedRequest($command, '+20 minutes');

            return response()->json([
                'url' => (string) $presignedRequest->getUri(),
                'path' => $path,
            ]);
        } catch (\Exception $e) {
            \Log::error('Presigned URL generation failed: ' . $e->getMessage());
            return response()->json([
                'error' => 'Failed to generate presigned URL: ' . $e->getMessage()
            ], 500);
        }
    }

    public function upload(Request $request)
    {
        $request->validate([
            'file' => 'required|file|image|max:5120', // 5MB
            'category_slug' => 'required|string',
            'project_slug' => 'required|string',
        ]);

        $filename = time() . '_' . Str::random(8) . '.' . $request->file('file')->getClientOriginalExtension();
        $path = $request->category_slug . '/' . $request->project_slug . '/' . $filename;

        // Store to R2 with Flysystem
        Storage::disk('r2')->put($path, file_get_contents($request->file('file')), 'private');

        return response()->json([
            'path' => $path,
            'url' => config('filesystems.disks.r2.url') . '/' . $path
        ]);
    }
}
