<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SubscriptionPlanResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'duration' => $this->duration,
            'duration_days' => $this->duration_days,
            'price' => (float) $this->price,
            'currency' => $this->currency,
            'google_product_id' => $this->google_product_id,
            'features' => $this->features ?? [],
            'coins_per_period' => $this->coins_per_period,
        ];
    }
}
