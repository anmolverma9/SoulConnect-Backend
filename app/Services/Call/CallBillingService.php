<?php

namespace App\Services\Call;

use App\Exceptions\ApiException;
use App\Exceptions\InsufficientBalanceException;
use App\Models\AppSetting;
use App\Models\Call;
use App\Models\User;
use App\Services\Subscription\EntitlementService;
use App\Services\Wallet\WalletService;
use Illuminate\Support\Facades\DB;

class CallBillingService
{
    public function __construct(
        protected WalletService $walletService,
        protected EntitlementService $entitlementService
    ) {}

    /**
     * Get per-minute coin rate for calls
     */
    public function getRatePerMinute(): int
    {
        return (int) AppSetting::get('call_coin_cost_per_minute', config('dating.defaults.call_cost_per_minute', 20));
    }

    /**
     * Pre-check caller balance before initiating a call
     */
    public function validatePreCallBalance(User $caller): void
    {
        $rate = $this->getRatePerMinute();

        if ($rate > 0 && ! $this->walletService->hasBalance($caller, $rate)) {
            throw new InsufficientBalanceException("You need at least {$rate} coins to initiate a call.");
        }
    }

    /**
     * Calculate and debit coins when call ends
     */
    public function finalizeBilling(Call $call): int
    {
        if ($call->billing_status === 'billed') {
            return $call->coin_cost;
        }

        $durationSeconds = $call->duration_seconds;
        if ($durationSeconds <= 0 || $call->status !== 'ended') {
            $call->update(['billing_status' => 'free', 'coin_cost' => 0]);
            return 0;
        }

        // Bill per full or partial minute
        $billableMinutes = (int) ceil($durationSeconds / 60);
        $ratePerMinute = $this->getRatePerMinute();
        $totalCost = $billableMinutes * $ratePerMinute;

        if ($totalCost <= 0) {
            $call->update(['billing_status' => 'free', 'coin_cost' => 0]);
            return 0;
        }

        return DB::transaction(function () use ($call, $totalCost, $billableMinutes) {
            $caller = $call->caller;

            // Debit up to available balance or exact cost
            $wallet = $this->walletService->getWallet($caller);
            $actualCharge = min($wallet->balance, $totalCost);

            if ($actualCharge > 0) {
                $this->walletService->debit(
                    $caller,
                    $actualCharge,
                    'call',
                    $call->id,
                    'Call',
                    "Call duration: {$billableMinutes} min(s)",
                    ['call_id' => $call->id, 'duration_seconds' => $call->duration_seconds]
                );
            }

            $call->update([
                'coin_cost' => $actualCharge,
                'billing_status' => 'billed',
            ]);

            return $actualCharge;
        });
    }
}
