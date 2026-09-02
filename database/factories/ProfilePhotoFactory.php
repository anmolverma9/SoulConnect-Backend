<?php

namespace Database\Factories;

use App\Models\ProfilePhoto;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProfilePhotoFactory extends Factory
{
    protected $model = ProfilePhoto::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'path' => 'photos/test_photo.jpg',
            'url' => 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?auto=format&fit=crop&w=600&q=80',
            'is_primary' => true,
            'sort_order' => 1,
            'status' => 'approved',
        ];
    }
}
