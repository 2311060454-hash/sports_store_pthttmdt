<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ImageService
{
    /**
     * Upload an image to a given folder on the public disk.
     */
    public function upload(UploadedFile $file, string $folder = 'products'): string
    {
        $filename = Str::uuid() . '.' . $file->getClientOriginalExtension();
        $path = $file->storeAs('uploads/' . $folder, $filename, 'public');
        return 'storage/' . $path;
    }

    /**
     * Delete an existing image from public disk.
     */
    public function delete(?string $path): bool
    {
        if (!$path) {
            return false;
        }

        // Clean 'storage/' prefix to get the relative path inside storage/app/public
        $cleanPath = Str::replaceFirst('storage/', '', $path);
        if (Storage::disk('public')->exists($cleanPath)) {
            return Storage::disk('public')->delete($cleanPath);
        }

        return false;
    }
}
