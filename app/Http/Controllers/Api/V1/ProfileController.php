<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Profile\UpdateProfileRequest;
use App\Http\Requests\Profile\UploadPhotoRequest;
use App\Http\Resources\ProfilePhotoResource;
use App\Http\Resources\UserProfileResource;
use App\Http\Resources\UserResource;
use App\Services\Dating\ProfileService;
use App\Support\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function __construct(
        protected ProfileService $profileService
    ) {}

    /**
     * Get authenticated user's profile
     */
    public function show(Request $request): JsonResponse
    {
        $user = $request->user()->load(['profile', 'photos', 'preferences', 'wallet']);

        return ApiResponse::success(new UserResource($user));
    }

    /**
     * Update user profile information
     */
    public function update(UpdateProfileRequest $request): JsonResponse
    {
        $profile = $this->profileService->updateProfile($request->user(), $request->validated());

        return ApiResponse::success(new UserProfileResource($profile), 'Profile updated successfully.');
    }

    /**
     * Upload profile photo
     */
    public function uploadPhoto(UploadPhotoRequest $request): JsonResponse
    {
        $photo = $this->profileService->addPhoto(
            $request->user(),
            $request->file('photo'),
            $request->boolean('is_primary', false)
        );

        return ApiResponse::success(new ProfilePhotoResource($photo), 'Photo uploaded successfully.', 201);
    }

    /**
     * Set a photo as primary
     */
    public function setPrimaryPhoto(Request $request, int $id): JsonResponse
    {
        $photo = $this->profileService->setPrimaryPhoto($request->user(), $id);

        return ApiResponse::success(new ProfilePhotoResource($photo), 'Primary photo updated.');
    }

    /**
     * Delete a profile photo
     */
    public function deletePhoto(Request $request, int $id): JsonResponse
    {
        $this->profileService->deletePhoto($request->user(), $id);

        return ApiResponse::success(null, 'Photo deleted successfully.');
    }
}
