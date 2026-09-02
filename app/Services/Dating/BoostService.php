<?php

namespace App\Services\Dating;

use App\Exceptions\ApiException;
use App\Models\AppSetting;
use App\Models\Boost;
use App\Models\User;
use App\Services\Wallet\WalletService;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class BoostService
{
    public function __construct(
        protected WalletService $walletService
    ) {}

    /**
     * Purchase and activate profile boost
     */
    public function purchaseBoost(User $user): Boost
    {
        // Check if already active
        $activeBoost = Boost::where('user_id', $user->id)
            ->where('status', 'active')
            ->where('expires_at', '>', Carbon::now())
            ->first();

        if ($activeBoost) {
            throw new ApiException('You already have an active profile boost.', 422, [
                'expires_at' => $activeBoost->expires_at->toIso8601String(),
            ]);
        }

        $cost = (int) AppSetting::get('boost_coin_cost', config('dating.defaults.boost_cost', 50));
        $durationMinutes = (int) AppSetting::get('boost_duration_minutes', config('dating.defaults.boost_duration_minutes', 30));

        return DB::transaction(function () use ($user, $cost, $durationMinutes) {
            if ($cost > 0) {
                $this->walletService->debit(
                    $user,
                    $cost,
                    'boost',
                    null,
                    'Boost',
                    "Purchased profile boost ({$durationMinutes} mins)"
                );
            }

            $startedAt = Carbon::now();
            $expiresAt = Carbon::now()->addMinutes($durationMinutes);

            return Boost::create([
                'user_id' => $user->id,
                'duration_minutes' => $durationMinutes,
                'started_at' => $startedAt,
                'expires_at' => $expiresAt,
                'coin_cost' => $cost,
                'status' => 'active',
            ]);
        });
    }

    /**
     * Get active boost for user
     */
    public function getActiveBoost(User $user): ?Boost
    {
        return Boost::where('user_id', $user->id)
            ->where('status', 'active')
            ->where('expires_at', '>', Carbon::now())
            ->first();
    }
}
