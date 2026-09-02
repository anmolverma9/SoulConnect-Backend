<?php

namespace Database\Seeders;

use App\Models\CoinPackage;
use Illuminate\Database\Seeder;

class CoinPackageSeeder extends Seeder
{
    public function run(): void
    {
        $packages = [
            [
                'name' => 'Starter Pack',
                'coins' => 100,
                'bonus_coins' => 0,
                'price' => 2.99,
                'currency' => 'USD',
                'google_product_id' => 'com.datingapp.coins_100',
                'is_active' => true,
                'sort_order' => 1,
            ],
            [
                'name' => 'Popular Pack',
                'coins' => 500,
                'bonus_coins' => 50,
                'price' => 9.99,
                'currency' => 'USD',
                'google_product_id' => 'com.datingapp.coins_550',
                'is_active' => true,
                'sort_order' => 2,
            ],
            [
                'name' => 'Value Pack',
                'coins' => 1200,
                'bonus_coins' => 200,
                'price' => 19.99,
                'currency' => 'USD',
                'google_product_id' => 'com.datingapp.coins_1400',
                'is_active' => true,
                'sort_order' => 3,
            ],
            [
                'name' => 'Ultimate Pack',
                'coins' => 3500,
                'bonus_coins' => 1000,
                'price' => 49.99,
                'currency' => 'USD',
                'google_product_id' => 'com.datingapp.coins_4500',
                'is_active' => true,
                'sort_order' => 4,
            ],
        ];

        foreach ($packages as $pkg) {
            CoinPackage::updateOrCreate(
                ['google_product_id' => $pkg['google_product_id']],
                $pkg
            );
        }
    }
}
