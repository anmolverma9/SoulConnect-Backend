<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class GiftCatalog extends Model
{
    use HasFactory;

    protected $table = 'gift_catalog';

    protected $fillable = [
        'name',
        'icon',
        'coin_cost',
        'is_active',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'coin_cost' => 'integer',
            'is_active' => 'boolean',
            'sort_order' => 'integer',
        ];
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(GiftTransaction::class, 'gift_id');
    }
}
