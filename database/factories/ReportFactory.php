<?php

namespace Database\Factories;

use App\Models\Report;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ReportFactory extends Factory
{
    protected $model = Report::class;

    public function definition(): array
    {
        return [
            'reporter_id' => User::factory(),
            'reported_id' => User::factory(),
            'reason' => 'Harassment',
            'details' => fake()->sentence(10),
            'status' => 'pending',
        ];
    }
}
