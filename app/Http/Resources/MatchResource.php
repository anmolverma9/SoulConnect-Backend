<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MatchResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $currentUserId = $request->user()?->id;
        $otherUser = $this->getOtherUser($currentUserId ?? 0);

        return [
            'id' => $this->id,
            'matched_at' => $this->matched_at?->toIso8601String(),
            'status' => $this->status,
            'user' => $otherUser ? [
                'id' => $otherUser->id,
                'name' => $otherUser->name,
                'age' => $otherUser->profile?->age,
                'primary_photo' => $otherUser->primaryPhoto?->full_url,
                'bio' => $otherUser->profile?->bio,
            ] : null,
            'conversation_id' => $this->conversation?->id,
        ];
    }
}
