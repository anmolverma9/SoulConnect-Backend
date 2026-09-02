<?php

namespace Database\Seeders;

use App\Models\AdminUser;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        AdminUser::updateOrCreate(
            ['email' => 'admin@datingapp.example.com'],
            [
                'name' => 'Super Administrator',
                'password' => Hash::make('Admin@Secure2026!'),
                'role' => 'super_admin',
                'is_active' => true,
            ]
        );
    }
}
