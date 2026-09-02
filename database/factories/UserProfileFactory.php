<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\UserProfile;
use Illuminate\Database\Eloquent\Factories\Factory;

class UserProfileFactory extends Factory
{
    protected $model = UserProfile::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'name' => fake()->firstName(),
            'date_of_birth' => fake()->dateTimeBetween('-35 years', '-19 years')->format('Y-m-d'),
            'gender' => fake()->randomElement(['male', 'female', 'non_binary']),
            'bio' => fake()->sentence(12),
            'city' => fake()->city(),
            'country' => fake()->country(),
            'latitude' => fake()->latitude(37.7, 37.8),
            'longitude' => fake()->longitude(-122.5, -122.4),
            'occupation' => fake()->jobTitle(),
            'education' => fake()->randomElement(['Bachelors in Design', 'Masters in CS', 'BS in Business']),
            'height' => fake()->numberBetween(160, 190),
            'interests' => ['Travel', 'Music', 'Hiking', 'Coffee'],
            'relationship_goal' => fake()->randomElement(['long_term', 'short_term', 'casual', 'friendship']),
            'profile_visibility' => 'public',
            'is_completed' => true,
        ];
    }
}
