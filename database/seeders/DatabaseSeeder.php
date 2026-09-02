<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            AppSettingSeeder::class,
            CoinPackageSeeder::class,
            SubscriptionPlanSeeder::class,
            GiftCatalogSeeder::class,
            EngagementRuleSeeder::class,
            AdminUserSeeder::class,
        ]);
    }
}
