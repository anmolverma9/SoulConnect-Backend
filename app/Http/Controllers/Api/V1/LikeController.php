<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\DiscoveryProfileResource;
use App\Http\Resources\MatchResource;
use App\Models\Like;
use App\Models\User;
use App\Services\Dating\MatchingService;
use App\Services\Subscription\EntitlementService;
use App\Support\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class LikeController extends Controller
{
    public function __construct(
        protected MatchingService $matchingService,
        protected EntitlementService $entitlementService
    ) {}

    /**
     * Like a user profile
     */
    public function like(Request $request, User $user): JsonResponse
    {
        if (! $this->entitlementService->can($request->user(), 'unlimited_likes')) {
            return ApiResponse::error('Daily like limit reached. Upgrade to Premium for unlimited likes!', 429, [
                'code' => 'LIKE_LIMIT_EXCEEDED',
            ]);
        }

        $result = $this->matchingService->likeUser($request->user(), $user->id, false);

        return ApiResponse::success([
            'is_match' => $result['is_match'],
            'match' => $result['match'] ? new MatchResource($result['match']) : null,
        ], $result['is_match'] ? "It's a Match!" : 'Liked profile.');
    }

    /**
     * Super Like a user profile
     */
    public function superLike(Request $request, User $user): JsonResponse
    {
        $result = $this->matchingService->likeUser($request->user(), $user->id, true);

        return ApiResponse::success([
            'is_match' => $result['is_match'],
            'match' => $result['match'] ? new MatchResource($result['match']) : null,
        ], $result['is_match'] ? "It's a Super Match!" : 'Super Liked profile.');
    }

    /**
     * Pass a user profile
     */
    public function pass(Request $request, User $user): JsonResponse
    {
        $this->matchingService->passUser($request->user(), $user->id);

        return ApiResponse::success(null, 'Passed profile.');
    }

    /**
     * Get profiles that liked current user (requires Premium entitlement)
     */
    public function getLikes(Request $request): JsonResponse
    {
        if (! $this->entitlementService->can($request->user(), 'see_likes')) {
            $count = Like::where('liked_user_id', $request->user()->id)->count();
            return ApiResponse::success([
                'can_see_likes' => false,
                'likes_count' => $count,
                'message' => 'Upgrade to Premium to see who likes you!',
            ]);
        }

        $likers = User::query()
            ->join('likes', 'users.id', '=', 'likes.user_id')
            ->where('likes.liked_user_id', $request->user()->id)
            ->where('users.status', 'active')
            ->select('users.*')
            ->with(['profile', 'photos'])
            ->orderBy('likes.created_at', 'desc')
            ->paginate(20);

        return ApiResponse::paginated(DiscoveryProfileResource::collection($likers));
    }
}
