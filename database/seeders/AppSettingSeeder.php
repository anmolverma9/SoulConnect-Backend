<?php

namespace Database\Seeders;

use App\Models\AppSetting;
use Illuminate\Database\Seeder;

class AppSettingSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            [
                'key' => 'call_coin_cost_per_minute',
                'value' => '20',
                'description' => 'Coin cost per minute for voice and video calls',
                'group' => 'pricing',
                'is_public' => true,
            ],
            [
                'key' => 'boost_coin_cost',
                'value' => '50',
                'description' => 'Coin cost for 30-minute profile visibility boost',
                'group' => 'pricing',
                'is_public' => true,
            ],
            [
                'key' => 'boost_duration_minutes',
                'value' => '30',
                'description' => 'Duration in minutes of a profile boost',
                'group' => 'dating',
                'is_public' => true,
            ],
            [
                'key' => 'super_like_coin_cost',
                'value' => '10',
                'description' => 'Coin cost per Super Like',
                'group' => 'pricing',
                'is_public' => true,
            ],
            [
                'key' => 'free_daily_likes',
                'value' => '50',
                'description' => 'Daily free likes limit for non-subscribers',
                'group' => 'dating',
                'is_public' => true,
            ],
            [
                'key' => 'maintenance_mode',
                'value' => 'false',
                'description' => 'Global API maintenance toggle',
                'group' => 'system',
                'is_public' => true,
            ],
        ];

        foreach ($settings as $setting) {
            AppSetting::updateOrCreate(
                ['key' => $setting['key']],
                $setting
            );
        }
    }
}
