<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class EngagementRule extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_type',
        'title',
        'message_template',
        'is_enabled',
        'min_interval_hours',
        'daily_limit',
        'cooldown_hours',
        'priority',
        'metadata',
    ];

    protected function casts(): array
    {
        return [
            'is_enabled' => 'boolean',
            'min_interval_hours' => 'integer',
            'daily_limit' => 'integer',
            'cooldown_hours' => 'integer',
            'priority' => 'integer',
            'metadata' => 'array',
        ];
    }

    public function events(): HasMany
    {
        return $this->hasMany(EngagementEvent::class, 'rule_id');
    }
}
