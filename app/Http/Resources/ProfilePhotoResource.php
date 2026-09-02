<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProfilePhotoResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'url' => $this->full_url,
            'is_primary' => $this->is_primary,
            'sort_order' => $this->sort_order,
            'status' => $this->status,
        ];
    }
}
