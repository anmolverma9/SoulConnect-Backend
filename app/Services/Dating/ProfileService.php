<?php

namespace App\Services\Dating;

use App\Exceptions\ApiException;
use App\Models\ProfilePhoto;
use App\Models\User;
use App\Models\UserProfile;
use App\Services\Media\ImageUploadService;
use Carbon\Carbon;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;

class ProfileService
{
    public function __construct(
        protected ImageUploadService $imageUploadService
    ) {}

    /**
     * Update user profile information
     */
    public function updateProfile(User $user, array $data): UserProfile
    {
        if (! empty($data['date_of_birth'])) {
            $dob = Carbon::parse($data['date_of_birth']);
            if ($dob->age < config('dating.defaults.min_age', 18)) {
                throw new ApiException('You must be at least 18 years old to use this application.', 422);
            }
        }

        $profile = $user->profile ?? new UserProfile(['user_id' => $user->id]);
        $profile->fill($data);

        // Check if profile is complete (name, dob, gender, at least one photo)
        $hasPhoto = $user->photos()->exists();
        $isComplete = ! empty($profile->name) &&
            ! empty($profile->date_of_birth) &&
            ! empty($profile->gender) &&
            $hasPhoto;

        $profile->is_completed = $isComplete;
        $profile->save();

        if ($isComplete && ! $user->profile_completed_at) {
            $user->update(['profile_completed_at' => Carbon::now()]);
        }

        if (! empty($data['name'])) {
            $user->update(['name' => $data['name']]);
        }

        return $profile;
    }

    /**
     * Add profile photo
     */
    public function addPhoto(User $user, UploadedFile $file, bool $isPrimary = false): ProfilePhoto
    {
        $maxPhotos = config('dating.upload.max_photos_per_user', 6);
        $currentCount = $user->photos()->count();

        if ($currentCount >= $maxPhotos) {
            throw new ApiException("You can upload a maximum of {$maxPhotos} photos.", 422);
        }

        $upload = $this->imageUploadService->uploadProfilePhoto($file, $user->id);

        return DB::transaction(function () use ($user, $upload, $isPrimary, $currentCount) {
            $makePrimary = $isPrimary || $currentCount === 0;

            if ($makePrimary) {
                $user->photos()->update(['is_primary' => false]);
            }

            $photo = ProfilePhoto::create([
                'user_id' => $user->id,
                'path' => $upload['path'],
                'url' => $upload['url'],
                'is_primary' => $makePrimary,
                'sort_order' => $currentCount + 1,
                'status' => 'approved',
            ]);

            // If this is the user's first photo, check profile completion
            if (! $user->profile_completed_at && $user->profile?->date_of_birth && $user->profile?->gender) {
                $user->profile->update(['is_completed' => true]);
                $user->update(['profile_completed_at' => Carbon::now()]);
            }

            return $photo;
        });
    }

    /**
     * Set a photo as primary
     */
    public function setPrimaryPhoto(User $user, int $photoId): ProfilePhoto
    {
        $photo = $user->photos()->findOrFail($photoId);

        DB::transaction(function () use ($user, $photo) {
            $user->photos()->update(['is_primary' => false]);
            $photo->update(['is_primary' => true]);
        });

        return $photo;
    }

    /**
     * Delete a profile photo
     */
    public function deletePhoto(User $user, int $photoId): void
    {
        $photo = $user->photos()->findOrFail($photoId);
        $wasPrimary = $photo->is_primary;

        DB::transaction(function () use ($user, $photo, $wasPrimary) {
            $this->imageUploadService->deleteFile($photo->path);
            $photo->delete();

            // If the deleted photo was primary, make the next photo primary
            if ($wasPrimary) {
                $nextPhoto = $user->photos()->orderBy('sort_order', 'asc')->first();
                $nextPhoto?->update(['is_primary' => true]);
            }
        });
    }
}
