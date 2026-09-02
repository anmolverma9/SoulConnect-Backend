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
                'price' => 99.00,
                'currency' => 'INR',
                'google_product_id' => 'com.soulconnect.coins_100',
                'is_active' => true,
                'sort_order' => 1,
            ],
            [
                'name' => 'Popular Pack',
                'coins' => 300,
                'bonus_coins' => 50,
                'price' => 249.00,
                'currency' => 'INR',
                'google_product_id' => 'com.soulconnect.coins_350',
                'is_active' => true,
                'sort_order' => 2,
            ],
            [
                'name' => 'Best Value Pack',
                'coins' => 700,
                'bonus_coins' => 150,
                'price' => 499.00,
                'currency' => 'INR',
                'google_product_id' => 'com.soulconnect.coins_850',
                'is_active' => true,
                'sort_order' => 3,
            ],
            [
                'name' => 'Mega Pack',
                'coins' => 1600,
                'bonus_coins' => 400,
                'price' => 999.00,
                'currency' => 'INR',
                'google_product_id' => 'com.soulconnect.coins_2000',
                'is_active' => true,
                'sort_order' => 4,
            ],
            [
                'name' => 'VIP Whale Pack',
                'coins' => 3800,
                'bonus_coins' => 1200,
                'price' => 1999.00,
                'currency' => 'INR',
                'google_product_id' => 'com.soulconnect.coins_5000',
                'is_active' => true,
                'sort_order' => 5,
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
