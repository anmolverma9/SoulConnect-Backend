<?php

namespace Database\Factories;

use App\Models\CoinPackage;
use Illuminate\Database\Eloquent\Factories\Factory;

class CoinPackageFactory extends Factory
{
    protected $model = CoinPackage::class;

    public function definition(): array
    {
        return [
            'name' => 'Test Pack',
            'coins' => 100,
            'bonus_coins' => 10,
            'price' => 4.99,
            'currency' => 'USD',
            'google_product_id' => 'com.test.pack_' . fake()->unique()->numberBetween(1, 9999),
            'is_active' => true,
            'sort_order' => 1,
        ];
    }
}
