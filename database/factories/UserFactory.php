<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    protected $model = User::class;

    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => '$2y$12$e8x/kZ6k9tVv6KjV7w/m.Ou5v7JmF2.L7Vd/0/2B9J6GqH9V2',
            'status' => 'active',
            'last_active_at' => now(),
            'remember_token' => Str::random(10),
        ];
    }
}
