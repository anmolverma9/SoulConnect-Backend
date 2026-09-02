<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Models\UserFollow;
use App\Support\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class FollowController extends Controller
{
    /**
     * Follow a user
     */
    public function follow(Request $request, User $user): JsonResponse
    {
        $currentUser = $request->user();

        if ($currentUser->id === $user->id) {
            return ApiResponse::error('You cannot follow yourself.', 422);
        }

        UserFollow::firstOrCreate([
            'follower_id' => $currentUser->id,
            'following_id' => $user->id,
        ]);

        $followersCount = $user->followers()->count();

        return ApiResponse::success([
            'user_id' => $user->id,
            'is_following' => true,
            'followers_count' => $followersCount,
        ], 'Successfully followed user.');
    }

    /**
     * Unfollow a user
     */
    public function unfollow(Request $request, User $user): JsonResponse
    {
        $currentUser = $request->user();

        UserFollow::where('follower_id', $currentUser->id)
            ->where('following_id', $user->id)
            ->delete();

        $followersCount = $user->followers()->count();

        return ApiResponse::success([
            'user_id' => $user->id,
            'is_following' => false,
            'followers_count' => $followersCount,
        ], 'Successfully unfollowed user.');
    }

    /**
     * Toggle follow status
     */
    public function toggle(Request $request, User $user): JsonResponse
    {
        $currentUser = $request->user();

        if ($currentUser->id === $user->id) {
            return ApiResponse::error('You cannot follow yourself.', 422);
        }

        $existing = UserFollow::where('follower_id', $currentUser->id)
            ->where('following_id', $user->id)
            ->first();

        if ($existing) {
            $existing->delete();
            $isFollowing = false;
            $message = 'Unfollowed user.';
        } else {
            UserFollow::create([
                'follower_id' => $currentUser->id,
                'following_id' => $user->id,
            ]);
            $isFollowing = true;
            $message = 'Followed user.';
        }

        $followersCount = $user->followers()->count();

        return ApiResponse::success([
            'user_id' => $user->id,
            'is_following' => $isFollowing,
            'followers_count' => $followersCount,
        ], $message);
    }

    /**
     * Get list of followers for a user
     */
    public function followers(Request $request, User $user): JsonResponse
    {
        $followers = User::whereIn('id', $user->followers()->select('follower_id'))
            ->with(['profile', 'photos'])
            ->paginate($request->integer('per_page', 20));

        return ApiResponse::paginated(
            UserResource::collection($followers),
            $followers,
            'Followers retrieved successfully.'
        );
    }

    /**
     * Get list of users followed by a user
     */
    public function following(Request $request, User $user): JsonResponse
    {
        $following = User::whereIn('id', $user->following()->select('following_id'))
            ->with(['profile', 'photos'])
            ->paginate($request->integer('per_page', 20));

        return ApiResponse::paginated(
            UserResource::collection($following),
            $following,
            'Following retrieved successfully.'
        );
    }

    /**
     * Get current user's follow stats
     */
    public function myStats(Request $request): JsonResponse
    {
        $user = $request->user();

        return ApiResponse::success([
            'followers_count' => $user->followers()->count(),
            'following_count' => $user->following()->count(),
        ]);
    }
}
