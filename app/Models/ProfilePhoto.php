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
            if (str_starts_with($this->url, 'http://') || str_starts_with($this->url, 'https://')) {
                return $this->url;
            }
            return url($this->url);
        }

        if ($this->path) {
            $storageUrl = Storage::disk('public')->url($this->path);
            if (str_starts_with($storageUrl, 'http://') || str_starts_with($storageUrl, 'https://')) {
                return $storageUrl;
            }
            return url($storageUrl);
        }

        return '';
    }
}
