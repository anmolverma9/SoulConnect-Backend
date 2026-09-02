<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserProfileResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'name' => $this->name,
            'age' => $this->age,
            'date_of_birth' => $this->date_of_birth?->toDateString(),
            'gender' => $this->gender,
            'bio' => $this->bio,
            'city' => $this->city,
            'country' => $this->country,
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
            'occupation' => $this->occupation,
            'education' => $this->education,
            'height' => $this->height,
            'interests' => $this->interests ?? [],
            'relationship_goal' => $this->relationship_goal,
            'profile_visibility' => $this->profile_visibility,
            'is_completed' => $this->is_completed,
        ];
    }
}
