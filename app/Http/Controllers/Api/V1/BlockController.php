<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Safety\BlockUserRequest;
use App\Http\Resources\BlockResource;
use App\Models\Block;
use App\Models\MatchModel;
use App\Models\User;
use App\Support\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BlockController extends Controller
{
    /**
     * Get list of blocked users
     */
    public function index(Request $request): JsonResponse
    {
        $blocks = Block::where('blocker_id', $request->user()->id)
            ->with(['blocked.profile', 'blocked.photos'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return ApiResponse::paginated(BlockResource::collection($blocks));
    }

    /**
     * Block a user
     */
    public function store(BlockUserRequest $request, User $user): JsonResponse
    {
        if ($request->user()->id === $user->id) {
            return ApiResponse::error('You cannot block yourself.', 422);
        }

        DB::transaction(function () use ($request, $user) {
            Block::firstOrCreate(
                [
                    'blocker_id' => $request->user()->id,
                    'blocked_id' => $user->id,
                ],
                [
                    'reason' => $request->input('reason'),
                ]
            );

            // Mark existing match as blocked
            $userOneId = min($request->user()->id, $user->id);
            $userTwoId = max($request->user()->id, $user->id);

            MatchModel::where('user_one_id', $userOneId)
                ->where('user_two_id', $userTwoId)
                ->update(['status' => 'blocked']);
        });

        return ApiResponse::success(null, "User {$user->name} has been blocked.");
    }

    /**
     * Unblock a user
     */
    public function destroy(Request $request, User $user): JsonResponse
    {
        Block::where('blocker_id', $request->user()->id)
            ->where('blocked_id', $user->id)
            ->delete();

        return ApiResponse::success(null, "User {$user->name} has been unblocked.");
    }
}
