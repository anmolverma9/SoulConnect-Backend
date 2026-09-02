<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class ProfilePhoto extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'path',
        'url',
        'is_primary',
        'sort_order',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'is_primary' => 'boolean',
            'sort_order' => 'integer',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getFullUrlAttribute(): string
    {
        if ($this->url) {
            return $this->url;
        }

        return Storage::disk('public')->url($this->path);
    }
}
