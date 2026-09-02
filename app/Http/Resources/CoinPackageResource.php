<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CoinPackageResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'coins' => $this->coins,
            'bonus_coins' => $this->bonus_coins,
            'total_coins' => $this->total_coins,
            'price' => (float) $this->price,
            'currency' => $this->currency,
            'google_product_id' => $this->google_product_id,
            'sort_order' => $this->sort_order,
        ];
    }
}
