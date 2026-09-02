<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class GiftTransactionResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'gift' => new GiftCatalogResource($this->whenLoaded('gift')),
            'sender' => [
                'id' => $this->sender?->id,
                'name' => $this->sender?->name,
                'primary_photo' => $this->sender?->primaryPhoto?->full_url,
            ],
            'receiver' => [
                'id' => $this->receiver?->id,
                'name' => $this->receiver?->name,
                'primary_photo' => $this->receiver?->primaryPhoto?->full_url,
            ],
            'coin_cost' => $this->coin_cost,
            'message' => $this->message,
            'created_at' => $this->created_at?->toIso8601String(),
        ];
    }
}
