<?php

namespace App\Services\Dating;

use App\Events\MatchCreatedEvent;
use App\Exceptions\ApiException;
use App\Models\Block;
use App\Models\Conversation;
use App\Models\ConversationParticipant;
use App\Models\Like;
use App\Models\MatchModel;
use App\Models\Pass;
use App\Models\SuperLike;
use App\Models\User;
use App\Notifications\MatchNotification;
use App\Services\Wallet\WalletService;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class MatchingService
{
    public function __construct(
        protected WalletService $walletService
    ) {}

    /**
     * Handle user like action
     */
    public function likeUser(User $user, int $targetUserId, bool $isSuperLike = false): array
    {
        if ($user->id === $targetUserId) {
            throw new ApiException('You cannot like your own profile.', 422);
        }

        $targetUser = User::where('id', $targetUserId)->where('status', 'active')->firstOrFail();

        // Check blocks
        $isBlocked = Block::where(function ($q) use ($user, $targetUserId) {
            $q->where('blocker_id', $user->id)->where('blocked_id', $targetUserId);
        })->orWhere(function ($q) use ($user, $targetUserId) {
            $q->where('blocker_id', $targetUserId)->where('blocked_id', $user->id);
        })->exists();

        if ($isBlocked) {
            throw new ApiException('Action not allowed.', 403);
        }

        return DB::transaction(function () use ($user, $targetUser, $targetUserId, $isSuperLike) {
            // Deduct coins if super like
            if ($isSuperLike) {
                $cost = config('dating.defaults.super_like_cost', 10);
                if ($cost > 0) {
                    $this->walletService->debit(
                        $user,
                        $cost,
                        'super_like',
                        $targetUserId,
                        'SuperLike',
                        "Sent a Super Like to {$targetUser->name}"
                    );
                }

                SuperLike::firstOrCreate([
                    'user_id' => $user->id,
                    'target_user_id' => $targetUserId,
                ], [
                    'coin_cost' => $cost,
                ]);
            }

            // Create or update like
            $like = Like::updateOrCreate(
                ['user_id' => $user->id, 'liked_user_id' => $targetUserId],
                ['is_super_like' => $isSuperLike]
            );

            // Remove from pass if previously passed
            Pass::where('user_id', $user->id)->where('passed_user_id', $targetUserId)->delete();

            // Check if target user already liked current user (Mutual Match)
            $mutualLike = Like::where('user_id', $targetUserId)
                ->where('liked_user_id', $user->id)
                ->first();

            $match = null;
            $isMatch = false;

            if ($mutualLike) {
                $userOneId = min($user->id, $targetUserId);
                $userTwoId = max($user->id, $targetUserId);

                $match = MatchModel::firstOrCreate(
                    [
                        'user_one_id' => $userOneId,
                        'user_two_id' => $userTwoId,
                    ],
                    [
                        'matched_at' => Carbon::now(),
                        'status' => 'active',
                    ]
                );

                if ($match->status !== 'active') {
                    $match->update(['status' => 'active', 'unmatched_by' => null, 'unmatched_at' => null]);
                }

                // Create conversation
                $conversation = Conversation::firstOrCreate(
                    ['match_id' => $match->id],
                    ['type' => 'direct']
                );

                ConversationParticipant::firstOrCreate([
                    'conversation_id' => $conversation->id,
                    'user_id' => $user->id,
                ]);

                ConversationParticipant::firstOrCreate([
                    'conversation_id' => $conversation->id,
                    'user_id' => $targetUserId,
                ]);

                $isMatch = true;

                // Notify target user
                $targetUser->notify(new MatchNotification($user, $match->id));
                event(new MatchCreatedEvent($match, $user, $targetUser));
            }

            return [
                'liked' => true,
                'is_match' => $isMatch,
                'match' => $match,
            ];
        });
    }

    /**
     * Handle user pass action
     */
    public function passUser(User $user, int $targetUserId): bool
    {
        if ($user->id === $targetUserId) {
            throw new ApiException('Invalid action.', 422);
        }

        Pass::firstOrCreate([
            'user_id' => $user->id,
            'passed_user_id' => $targetUserId,
        ]);

        return true;
    }

    /**
     * Unmatch users
     */
    public function unmatch(User $user, int $matchId): bool
    {
        $match = MatchModel::findOrFail($matchId);

        if ($match->user_one_id !== $user->id && $match->user_two_id !== $user->id) {
            throw new ApiException('Unauthorized.', 403);
        }

        DB::transaction(function () use ($match, $user) {
            $match->update([
                'status' => 'unmatched',
                'unmatched_by' => $user->id,
                'unmatched_at' => Carbon::now(),
            ]);
        });

        return true;
    }
}
