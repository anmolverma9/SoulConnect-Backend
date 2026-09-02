<?php

namespace Database\Factories;

use App\Models\MatchModel;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class MatchModelFactory extends Factory
{
    protected $model = MatchModel::class;

    public function definition(): array
    {
        return [
            'user_one_id' => User::factory(),
            'user_two_id' => User::factory(),
            'matched_at' => now(),
            'status' => 'active',
        ];
    }
}
