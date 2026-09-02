<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CoinPackage extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'coins',
        'bonus_coins',
        'price',
        'currency',
        'google_product_id',
        'is_active',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'coins' => 'integer',
            'bonus_coins' => 'integer',
            'price' => 'float',
            'is_active' => 'boolean',
            'sort_order' => 'integer',
        ];
    }

    public function purchases(): HasMany
    {
        return $this->hasMany(CoinPurchase::class, 'package_id');
    }

    public function getTotalCoinsAttribute(): int
    {
        return $this->coins + $this->bonus_coins;
    }
}
