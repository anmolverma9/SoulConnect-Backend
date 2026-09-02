<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DiscoveryProfileResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $profile = $this->profile;
        $distance = isset($this->distance) ? round((float) $this->distance, 1) : null;

        return [
            'id' => $this->id,
            'name' => $this->name ?? $profile?->name,
            'age' => $profile?->age,
            'gender' => $profile?->gender,
            'bio' => $profile?->bio,
            'city' => $profile?->city,
            'country' => $profile?->country,
            'distance_km' => $distance,
            'occupation' => $profile?->occupation,
            'education' => $profile?->education,
            'height' => $profile?->height,
            'interests' => $profile?->interests ?? [],
            'relationship_goal' => $profile?->relationship_goal,
            'photos' => ProfilePhotoResource::collection($this->whenLoaded('photos')),
            'is_boosted' => $this->activeBoost !== null,
            'last_active_at' => $this->last_active_at?->diffForHumans(),
        ];
    }
}
