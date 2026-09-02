<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Support\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SimulatedCallController extends Controller
{
    /**
     * Return a random bot female profile for in-app incoming video call simulation
     */
    public function randomCaller(Request $request): JsonResponse
    {
        $bot = User::where('is_bot', true)
            ->with(['profile', 'photos'])
            ->inRandomOrder()
            ->first();

        $videoUrls = [
            'https://commondatastorage.googleapis.com/gtv-videos-bucket/sample/ForBiggerBlazes.mp4',
            'https://commondatastorage.googleapis.com/gtv-videos-bucket/sample/ForBiggerEscapes.mp4',
            'https://commondatastorage.googleapis.com/gtv-videos-bucket/sample/ForBiggerFun.mp4',
            'https://commondatastorage.googleapis.com/gtv-videos-bucket/sample/ForBiggerJoyBlazes.mp4',
        ];

        $callerData = [
            'id' => $bot ? $bot->id : 1,
            'name' => $bot?->profile?->name ?: 'Ananya Sharma',
            'age' => $bot?->profile?->age ?: 23,
            'city' => $bot?->profile?->city ?: 'Mumbai',
            'occupation' => $bot?->profile?->occupation ?: 'UI/UX Designer',
            'avatar_emoji' => '👩‍🎨',
            'avatar_url' => $bot?->photos->first()?->file_path ?: 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=500&auto=format&fit=crop&q=80',
            'video_url' => $videoUrls[array_rand($videoUrls)],
            'cost_per_minute' => 50,
        ];

        return ApiResponse::success($callerData);
    }
}
