<?php

namespace App\Services\Subscription;

use App\Models\Like;
use App\Models\Subscription;
use App\Models\User;
use Carbon\Carbon;

class EntitlementService
{
    /**
     * Check if a user is entitled to a specific capability or feature
     */
    public function can(User $user, string $capability): bool
    {
        $hasActiveSubscription = $this->hasActiveSubscription($user);

        return match ($capability) {
            'see_likes' => $hasActiveSubscription,
            'advanced_filters' => $hasActiveSubscription,
            'priority_discovery' => $hasActiveSubscription,
            'unlimited_likes' => $hasActiveSubscription || $this->hasRemainingDailyLikes($user),
            'voice_call' => true, // Allowed if user has sufficient coins or subscription
            'video_call' => true,
            default => $hasActiveSubscription,
        };
    }

    /**
     * Check if user has an active premium subscription
     */
    public function hasActiveSubscription(User $user): bool
    {
        return Subscription::where('user_id', $user->id)
            ->whereIn('status', ['active', 'grace_period'])
            ->where('ends_at', '>', Carbon::now())
            ->exists();
    }

    /**
     * Get active subscription model for user
     */
    public function getActiveSubscription(User $user): ?Subscription
    {
        return Subscription::where('user_id', $user->id)
            ->whereIn('status', ['active', 'grace_period'])
            ->where('ends_at', '>', Carbon::now())
            ->with('plan')
            ->latest()
            ->first();
    }

    /**
     * Check daily free likes limit for non-subscribers
     */
    public function hasRemainingDailyLikes(User $user): bool
    {
        if ($this->hasActiveSubscription($user)) {
            return true;
        }

        $freeDailyLimit = config('dating.defaults.free_daily_likes', 50);
        $todayLikesCount = Like::where('user_id', $user->id)
            ->where('created_at', '>=', Carbon::today())
            ->count();

        return $todayLikesCount < $freeDailyLimit;
    }
}
