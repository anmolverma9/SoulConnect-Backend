<?php

namespace Database\Seeders;

use App\Models\EngagementRule;
use Illuminate\Database\Seeder;

class EngagementRuleSeeder extends Seeder
{
    public function run(): void
    {
        $rules = [
            [
                'event_type' => 'inactive_reminder',
                'title' => 'We miss you! ✨',
                'message_template' => 'Hey {name}, there are new interesting people in your area. Come check them out!',
                'is_enabled' => true,
                'min_interval_hours' => 72,
                'daily_limit' => 1,
                'cooldown_hours' => 72,
                'priority' => 1,
                'metadata' => ['route' => 'discovery'],
            ],
            [
                'event_type' => 'boost_expiring',
                'title' => 'Your Boost is Ending ⏳',
                'message_template' => 'Your profile boost has expired. Re-boost to stay at the top of discovery!',
                'is_enabled' => true,
                'min_interval_hours' => 24,
                'daily_limit' => 1,
                'cooldown_hours' => 24,
                'priority' => 2,
                'metadata' => ['route' => 'boosts'],
            ],
            [
                'event_type' => 'profile_incomplete',
                'title' => 'Complete your Profile! 📸',
                'message_template' => 'Profiles with at least 3 photos get 5x more matches. Add your photos today!',
                'is_enabled' => true,
                'min_interval_hours' => 48,
                'daily_limit' => 1,
                'cooldown_hours' => 48,
                'priority' => 1,
                'metadata' => ['route' => 'profile_edit'],
            ],
        ];

        foreach ($rules as $rule) {
            EngagementRule::updateOrCreate(
                ['event_type' => $rule['event_type']],
                $rule
            );
        }
    }
}
