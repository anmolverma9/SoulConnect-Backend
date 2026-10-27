<?php

namespace Database\Seeders;

use App\Models\ProfilePhoto;
use App\Models\User;
use App\Models\UserProfile;
use Illuminate\Database\Seeder;

class UpdateProfileImagesSeeder extends Seeder
{
    /**
     * Update only the profile photos of existing profiles to real high-quality Indian images.
     */
    public function run(): void
    {
        $this->command?->info('Updating current profiles with real Indian images...');

        $botUsers = User::where('is_bot', true)->orderBy('id')->get();

        if ($botUsers->isEmpty()) {
            $this->command?->info('No bot users found, running BotEngagementSeeder first...');
            $this->call(BotEngagementSeeder::class);
            $botUsers = User::where('is_bot', true)->orderBy('id')->get();
        }

        $totalImages = 20;
        $updatedCount = 0;

        foreach ($botUsers as $index => $user) {
            $imgNum = ($index % $totalImages) + 1;
            $photoPath = "profiles/girl_{$imgNum}.jpg";
            $photoUrl = "/storage/profiles/girl_{$imgNum}.jpg";

            // Update or create primary photo
            ProfilePhoto::updateOrCreate(
                [
                    'user_id' => $user->id,
                    'is_primary' => true,
                ],
                [
                    'path' => $photoPath,
                    'url' => $photoUrl,
                    'status' => 'approved',
                    'sort_order' => 1,
                ]
            );

            // Clean up any extra non-primary stale photos for this user
            ProfilePhoto::where('user_id', $user->id)
                ->where('is_primary', false)
                ->delete();

            $this->command?->info("Updated {$user->name} (#{$user->id}) -> {$photoUrl}");
            $updatedCount++;
        }

        // Also update any female user profiles with external or missing photos
        $femaleUsers = User::where('is_bot', false)
            ->whereHas('profile', function ($q) {
                $q->where('gender', 'female');
            })
            ->with(['primaryPhoto', 'profile'])
            ->get();

        foreach ($femaleUsers as $index => $femaleUser) {
            $currentPhoto = $femaleUser->primaryPhoto;
            if (!$currentPhoto || str_contains($currentPhoto->url ?? '', 'unsplash.com') || empty($currentPhoto->url)) {
                $imgNum = (($updatedCount + $index) % $totalImages) + 1;
                $photoPath = "profiles/girl_{$imgNum}.jpg";
                $photoUrl = "/storage/profiles/girl_{$imgNum}.jpg";

                ProfilePhoto::updateOrCreate(
                    [
                        'user_id' => $femaleUser->id,
                        'is_primary' => true,
                    ],
                    [
                        'path' => $photoPath,
                        'url' => $photoUrl,
                        'status' => 'approved',
                        'sort_order' => 1,
                    ]
                );
                $this->command?->info("Updated female member {$femaleUser->name} (#{$femaleUser->id}) -> {$photoUrl}");
                $updatedCount++;
            }
        }

        $this->command?->info("Successfully updated {$updatedCount} profiles with real Indian images!");
    }
}
