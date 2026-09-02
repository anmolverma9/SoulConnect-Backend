<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserPreferenceResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'preferred_gender' => $this->preferred_gender,
            'minimum_age' => $this->minimum_age,
            'maximum_age' => $this->maximum_age,
            'maximum_distance' => $this->maximum_distance,
            'relationship_goal' => $this->relationship_goal,
            'interests' => $this->interests ?? [],
        ];
    }
}
