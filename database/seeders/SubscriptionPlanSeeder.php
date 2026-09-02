<?php

namespace Database\Seeders;

use App\Models\SubscriptionPlan;
use Illuminate\Database\Seeder;

class SubscriptionPlanSeeder extends Seeder
{
    public function run(): void
    {
        $plans = [
            [
                'name' => 'Premium Monthly',
                'duration' => 'monthly',
                'duration_days' => 30,
                'price' => 14.99,
                'currency' => 'USD',
                'google_product_id' => 'com.datingapp.sub_monthly',
                'features' => [
                    'unlimited_likes',
                    'see_likes',
                    'advanced_filters',
                    'priority_discovery',
                    '1_free_boost_per_week',
                ],
                'coins_per_period' => 100,
                'is_active' => true,
            ],
            [
                'name' => 'Premium Quarterly',
                'duration' => 'quarterly',
                'duration_days' => 90,
                'price' => 34.99,
                'currency' => 'USD',
                'google_product_id' => 'com.datingapp.sub_quarterly',
                'features' => [
                    'unlimited_likes',
                    'see_likes',
                    'advanced_filters',
                    'priority_discovery',
                    '1_free_boost_per_week',
                    '5_super_likes_per_week',
                ],
                'coins_per_period' => 350,
                'is_active' => true,
            ],
            [
                'name' => 'Premium Yearly',
                'duration' => 'yearly',
                'duration_days' => 365,
                'price' => 99.99,
                'currency' => 'USD',
                'google_product_id' => 'com.datingapp.sub_yearly',
                'features' => [
                    'unlimited_likes',
                    'see_likes',
                    'advanced_filters',
                    'priority_discovery',
                    'unlimited_rewinds',
                    '2_free_boosts_per_week',
                    '10_super_likes_per_week',
                ],
                'coins_per_period' => 1500,
                'is_active' => true,
            ],
        ];

        foreach ($plans as $plan) {
            SubscriptionPlan::updateOrCreate(
                ['google_product_id' => $plan['google_product_id']],
                $plan
            );
        }
    }
}
