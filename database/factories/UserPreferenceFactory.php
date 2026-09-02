<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\UserPreference;
use Illuminate\Database\Eloquent\Factories\Factory;

class UserPreferenceFactory extends Factory
{
    protected $model = UserPreference::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'preferred_gender' => 'all',
            'minimum_age' => 18,
            'maximum_age' => 45,
            'maximum_distance' => 50,
            'relationship_goal' => 'long_term',
            'interests' => ['Travel', 'Music'],
        ];
    }
}
