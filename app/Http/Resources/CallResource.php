<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CallResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'caller_id' => $this->caller_id,
            'receiver_id' => $this->receiver_id,
            'type' => $this->type,
            'status' => $this->status,
            'channel_name' => $this->channel_name,
            'started_at' => $this->started_at?->toIso8601String(),
            'answered_at' => $this->answered_at?->toIso8601String(),
            'ended_at' => $this->ended_at?->toIso8601String(),
            'duration_seconds' => $this->duration_seconds,
            'coin_cost' => $this->coin_cost,
            'billing_status' => $this->billing_status,
            'caller' => [
                'id' => $this->caller?->id,
                'name' => $this->caller?->name,
                'primary_photo' => $this->caller?->primaryPhoto?->full_url,
            ],
            'receiver' => [
                'id' => $this->receiver?->id,
                'name' => $this->receiver?->name,
                'primary_photo' => $this->receiver?->primaryPhoto?->full_url,
            ],
        ];
    }
}
