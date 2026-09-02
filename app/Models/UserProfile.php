<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'date_of_birth',
        'gender',
        'bio',
        'city',
        'country',
        'latitude',
        'longitude',
        'occupation',
        'education',
        'height',
        'interests',
        'relationship_goal',
        'profile_visibility',
        'is_completed',
    ];

    protected function casts(): array
    {
        return [
            'date_of_birth' => 'date',
            'latitude' => 'float',
            'longitude' => 'float',
            'height' => 'integer',
            'interests' => 'array',
            'is_completed' => 'boolean',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Calculate age dynamically from date_of_birth
     */
    public function getAgeAttribute(): ?int
    {
        if (! $this->date_of_birth) {
            return null;
        }

        return Carbon::parse($this->date_of_birth)->age;
    }
}
