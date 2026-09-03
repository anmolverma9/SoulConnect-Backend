<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PaymentOrder extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'order_id',
        'gateway',
        'gateway_order_id',
        'type',
        'package_id',
        'amount',
        'currency',
        'coins_to_credit',
        'status',
        'payment_url',
        'bank_txn_id',
        'response_payload',
        'paid_at',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'coins_to_credit' => 'integer',
        'response_payload' => 'array',
        'paid_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
