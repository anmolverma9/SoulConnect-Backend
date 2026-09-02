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
                'price' => 299.00,
                'currency' => 'INR',
                'google_product_id' => 'com.soulconnect.sub_monthly',
                'features' => [
                    'unlimited_likes',
                    'see_likes',
                    'advanced_filters',
                    'priority_discovery',
                    '1_free_boost_per_week',
                ],
                'coins_per_period' => 150,
                'is_active' => true,
            ],
            [
                'name' => 'Premium Quarterly',
                'duration' => 'quarterly',
                'duration_days' => 90,
                'price' => 699.00,
                'currency' => 'INR',
                'google_product_id' => 'com.soulconnect.sub_quarterly',
                'features' => [
                    'unlimited_likes',
                    'see_likes',
                    'advanced_filters',
                    'priority_discovery',
                    '1_free_boost_per_week',
                    '5_super_likes_per_week',
                ],
                'coins_per_period' => 500,
                'is_active' => true,
            ],
            [
                'name' => 'Premium Yearly',
                'duration' => 'yearly',
                'duration_days' => 365,
                'price' => 1499.00,
                'currency' => 'INR',
                'google_product_id' => 'com.soulconnect.sub_yearly',
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
