<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'status' => $this->status,
            'email_verified_at' => $this->email_verified_at?->toIso8601String(),
            'profile_completed_at' => $this->profile_completed_at?->toIso8601String(),
            'last_active_at' => $this->last_active_at?->toIso8601String(),
            'profile' => new UserProfileResource($this->whenLoaded('profile')),
            'photos' => ProfilePhotoResource::collection($this->whenLoaded('photos')),
            'preferences' => new UserPreferenceResource($this->whenLoaded('preferences')),
            'wallet' => new WalletResource($this->whenLoaded('wallet')),
            'active_subscription' => new SubscriptionResource($this->whenLoaded('activeSubscription')),
            'active_boost' => new BoostResource($this->whenLoaded('activeBoost')),
            'created_at' => $this->created_at?->toIso8601String(),
        ];
    }
}
