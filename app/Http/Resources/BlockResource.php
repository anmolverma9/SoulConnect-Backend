<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BlockResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'blocked_user' => [
                'id' => $this->blocked?->id,
                'name' => $this->blocked?->name,
                'primary_photo' => $this->blocked?->primaryPhoto?->full_url,
            ],
            'reason' => $this->reason,
            'created_at' => $this->created_at?->toIso8601String(),
        ];
    }
}
