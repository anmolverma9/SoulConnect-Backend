<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserPreference extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'preferred_gender',
        'minimum_age',
        'maximum_age',
        'maximum_distance',
        'relationship_goal',
        'interests',
    ];

    protected function casts(): array
    {
        return [
            'minimum_age' => 'integer',
            'maximum_age' => 'integer',
            'maximum_distance' => 'integer',
            'interests' => 'array',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
