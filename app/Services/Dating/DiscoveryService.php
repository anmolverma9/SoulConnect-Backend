<?php

namespace App\Services\Dating;

use App\Models\Block;
use App\Models\Like;
use App\Models\MatchModel;
use App\Models\Pass;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class DiscoveryService
{
    /**
     * Retrieve discovery candidate profiles matching user's preferences
     */
    public function discover(User $user, int $perPage = 15): LengthAwarePaginator
    {
        $preferences = $user->preferences;
        $userProfile = $user->profile;

        $userLat = $userProfile?->latitude;
        $userLng = $userProfile?->longitude;

        $minAge = $preferences?->minimum_age ?? 18;
        $maxAge = $preferences?->maximum_age ?? 50;
        $preferredGender = $preferences?->preferred_gender ?? 'all';
        $maxDistance = $preferences?->maximum_distance ?? 100;

        // Date ranges for age
        $maxDob = Carbon::now()->subYears($minAge)->toDateString();
        $minDob = Carbon::now()->subYears($maxAge + 1)->addDay()->toDateString();

        // Exclude IDs
        $likedUserIds = Like::where('user_id', $user->id)->pluck('liked_user_id');
        $passedUserIds = Pass::where('user_id', $user->id)->pluck('passed_user_id');

        $matchedUserOneIds = MatchModel::where('user_one_id', $user->id)->where('status', 'active')->pluck('user_two_id');
        $matchedUserTwoIds = MatchModel::where('user_two_id', $user->id)->where('status', 'active')->pluck('user_one_id');

        $blockedUserIds = Block::where('blocker_id', $user->id)->pluck('blocked_id');
        $blockingUserIds = Block::where('blocked_id', $user->id)->pluck('blocker_id');

        $excludeUserIds = collect([$user->id])
            ->merge($likedUserIds)
            ->merge($passedUserIds)
            ->merge($matchedUserOneIds)
            ->merge($matchedUserTwoIds)
            ->merge($blockedUserIds)
            ->merge($blockingUserIds)
            ->unique()
            ->values()
            ->all();

        // Build query
        $query = User::query()
            ->where('users.status', 'active')
            ->whereNotIn('users.id', $excludeUserIds)
            ->join('user_profiles', 'users.id', '=', 'user_profiles.user_id')
            ->where('user_profiles.profile_visibility', 'public')
            ->whereNotNull('user_profiles.date_of_birth')
            ->whereBetween('user_profiles.date_of_birth', [$minDob, $maxDob]);

        // Gender filter
        if ($preferredGender !== 'all') {
            $query->where('user_profiles.gender', $preferredGender);
        }

        // Distance & location filter (Haversine formula in KM)
        if ($userLat !== null && $userLng !== null) {
            $haversine = "(6371 * acos(cos(radians(?)) * cos(radians(user_profiles.latitude)) * cos(radians(user_profiles.longitude) - radians(?)) + sin(radians(?)) * sin(radians(user_profiles.latitude))))";
            
            $query->select('users.*', DB::raw("{$haversine} AS distance"))
                ->addBinding([$userLat, $userLng, $userLat], 'select')
                ->whereRaw("{$haversine} <= ?", [$userLat, $userLng, $userLat, $maxDistance]);
        } else {
            $query->select('users.*');
        }

        // Join active boosts for priority ranking
        $query->leftJoin('boosts', function ($join) {
            $join->on('users.id', '=', 'boosts.user_id')
                ->where('boosts.status', '=', 'active')
                ->where('boosts.expires_at', '>', Carbon::now());
        });

        // Order by active boost first, then last_active_at, then distance (if applicable)
        $query->orderByRaw('CASE WHEN boosts.id IS NOT NULL THEN 1 ELSE 0 END DESC')
            ->orderBy('users.last_active_at', 'desc');

        return $query->with(['profile', 'photos'])->paginate($perPage);
    }
}
