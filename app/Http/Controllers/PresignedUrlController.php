<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PresignedUrlController extends Controller
{
    public function store(Request $request)
    {
        $this->validate($request, [
            'filename' => 'required|string|max:255',
            'content_type' => 'required|string|max:255',
        ]);

        $disk = Storage::disk('r2');
        $filename = Str::uuid() . '.' . pathinfo($request->filename, PATHINFO_EXTENSION);

        $presignedRequest = $disk->getDriver()->getAdapter()->getClient()->createPresignedRequest(
            $disk->getDriver()->getAdapter()->getClient()->getCommand('PutObject', [
                'Bucket' => config('filesystems.disks.r2.bucket'),
                'Key' => $filename,
                'ContentType' => $request->content_type,
            ]),
            '+5 minutes'
        );

        return response()->json([
            'url' => (string) $presignedRequest->getUri(),
            'filename' => $filename,
        ]);
    }
}