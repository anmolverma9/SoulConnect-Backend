<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\MatchResource;
use App\Models\MatchModel;
use App\Services\Dating\MatchingService;
use App\Support\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MatchController extends Controller
{
    public function __construct(
        protected MatchingService $matchingService
    ) {}

    /**
     * Get active matches for current user
     */
    public function index(Request $request): JsonResponse
    {
        $userId = $request->user()->id;

        $matches = MatchModel::query()
            ->where('status', 'active')
            ->where(function ($q) use ($userId) {
                $q->where('user_one_id', $userId)
                  ->orWhere('user_two_id', $userId);
            })
            ->with(['userOne.profile', 'userOne.photos', 'userTwo.profile', 'userTwo.photos', 'conversation'])
            ->orderBy('matched_at', 'desc')
            ->paginate(20);

        return ApiResponse::paginated(MatchResource::collection($matches));
    }

    /**
     * Get specific match details
     */
    public function show(Request $request, int $id): JsonResponse
    {
        $userId = $request->user()->id;

        $match = MatchModel::where('id', $id)
            ->where(function ($q) use ($userId) {
                $q->where('user_one_id', $userId)
                  ->orWhere('user_two_id', $userId);
            })
            ->with(['userOne.profile', 'userOne.photos', 'userTwo.profile', 'userTwo.photos', 'conversation'])
            ->firstOrFail();

        return ApiResponse::success(new MatchResource($match));
    }

    /**
     * Unmatch a user
     */
    public function destroy(Request $request, int $id): JsonResponse
    {
        $this->matchingService->unmatch($request->user(), $id);

        return ApiResponse::success(null, 'Successfully unmatched.');
    }
}
