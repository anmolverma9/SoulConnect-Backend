<?php

namespace Database\Factories;

use App\Models\Subscription;
use App\Models\SubscriptionPlan;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class SubscriptionFactory extends Factory
{
    protected $model = Subscription::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'plan_id' => SubscriptionPlan::factory(),
            'purchase_token' => 'sub_token_' . fake()->unique()->uuid(),
            'order_id' => 'GPA.' . fake()->numberBetween(1000, 9999),
            'status' => 'active',
            'starts_at' => now(),
            'ends_at' => now()->addDays(30),
            'auto_renew' => true,
        ];
    }
}
