<?php

namespace Database\Seeders;

use App\Models\GiftCatalog;
use Illuminate\Database\Seeder;

class GiftCatalogSeeder extends Seeder
{
    public function run(): void
    {
        $gifts = [
            ['name' => 'Red Rose', 'icon' => '🌹', 'coin_cost' => 10, 'sort_order' => 1, 'is_active' => true],
            ['name' => 'Chocolates', 'icon' => '🍫', 'coin_cost' => 25, 'sort_order' => 2, 'is_active' => true],
            ['name' => 'Teddy Bear', 'icon' => '🧸', 'coin_cost' => 50, 'sort_order' => 3, 'is_active' => true],
            ['name' => 'Diamond Ring', 'icon' => '💍', 'coin_cost' => 100, 'sort_order' => 4, 'is_active' => true],
            ['name' => 'Sports Car', 'icon' => '🏎️', 'coin_cost' => 500, 'sort_order' => 5, 'is_active' => true],
            ['name' => 'Private Jet', 'icon' => '✈️', 'coin_cost' => 1000, 'sort_order' => 6, 'is_active' => true],
        ];

        foreach ($gifts as $gift) {
            GiftCatalog::updateOrCreate(
                ['name' => $gift['name']],
                $gift
            );
        }
    }
}
