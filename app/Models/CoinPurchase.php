<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CoinPurchase extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'package_id',
        'product_id',
        'purchase_token',
        'order_id',
        'coins',
        'status',
        'purchased_at',
        'verified_at',
        'metadata',
    ];

    protected function casts(): array
    {
        return [
            'coins' => 'integer',
            'purchased_at' => 'datetime',
            'verified_at' => 'datetime',
            'metadata' => 'array',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function package(): BelongsTo
    {
        return $this->belongsTo(CoinPackage::class, 'package_id');
    }
}
