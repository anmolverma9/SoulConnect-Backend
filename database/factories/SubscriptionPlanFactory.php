<?php

namespace Database\Factories;

use App\Models\SubscriptionPlan;
use Illuminate\Database\Eloquent\Factories\Factory;

class SubscriptionPlanFactory extends Factory
{
    protected $model = SubscriptionPlan::class;

    public function definition(): array
    {
        return [
            'name' => 'Gold Monthly',
            'duration' => 'monthly',
            'duration_days' => 30,
            'price' => 19.99,
            'currency' => 'USD',
            'google_product_id' => 'com.test.sub_' . fake()->unique()->numberBetween(1, 9999),
            'features' => ['unlimited_likes', 'see_likes'],
            'coins_per_period' => 100,
            'is_active' => true,
        ];
    }
}
