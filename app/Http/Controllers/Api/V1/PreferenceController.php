<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Profile\UpdatePreferencesRequest;
use App\Http\Resources\UserPreferenceResource;
use App\Models\UserPreference;
use App\Support\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PreferenceController extends Controller
{
    /**
     * Get user preferences
     */
    public function show(Request $request): JsonResponse
    {
        $preferences = $request->user()->preferences ?? UserPreference::create(['user_id' => $request->user()->id]);

        return ApiResponse::success(new UserPreferenceResource($preferences));
    }

    /**
     * Update user preferences
     */
    public function update(UpdatePreferencesRequest $request): JsonResponse
    {
        $preferences = $request->user()->preferences ?? new UserPreference(['user_id' => $request->user()->id]);
        $preferences->fill($request->validated());
        $preferences->save();

        return ApiResponse::success(new UserPreferenceResource($preferences), 'Preferences updated successfully.');
    }
}
