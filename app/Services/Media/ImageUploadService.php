<?php

namespace App\Services\Media;

use App\Exceptions\ApiException;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ImageUploadService
{
    /**
     * Validate and upload a profile photo securely
     */
    public function uploadProfilePhoto(UploadedFile $file, int $userId): array
    {
        $allowedMimes = config('dating.upload.allowed_mimes', ['image/jpeg', 'image/png', 'image/webp']);
        $maxSizeKb = config('dating.upload.max_photo_size_kb', 10240);

        // MIME validation
        $mime = $file->getMimeType();
        if (! in_array($mime, $allowedMimes)) {
            throw new ApiException('Invalid file type. Allowed formats: JPEG, PNG, WEBP.', 422);
        }

        // File size check
        if ($file->getSize() > ($maxSizeKb * 1024)) {
            throw new ApiException("File exceeds the maximum upload limit of {$maxSizeKb} KB.", 422);
        }

        // Generate safe unique filename
        $extension = match ($mime) {
            'image/jpeg' => 'jpg',
            'image/png' => 'png',
            'image/webp' => 'webp',
            default => 'jpg',
        };

        $fileName = Str::uuid()->toString() . '.' . $extension;
        $folder = "photos/user_{$userId}";
        $path = $file->storeAs($folder, $fileName, 'public');

        if (! $path) {
            throw new ApiException('Failed to upload profile photo.', 500);
        }

        return [
            'path' => $path,
            'url' => Storage::disk('public')->url($path),
        ];
    }

    /**
     * Delete an uploaded file
     */
    public function deleteFile(string $path): bool
    {
        if (Storage::disk('public')->exists($path)) {
            return Storage::disk('public')->delete($path);
        }

        return false;
    }
}
