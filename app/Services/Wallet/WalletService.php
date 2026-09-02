<?php

namespace App\Services\Wallet;

use App\Exceptions\ApiException;
use App\Exceptions\InsufficientBalanceException;
use App\Models\User;
use App\Models\Wallet;
use App\Models\WalletTransaction;
use Illuminate\Support\Facades\DB;

class WalletService
{
    /**
     * Get or create wallet for user
     */
    public function getWallet(User $user): Wallet
    {
        return Wallet::firstOrCreate(
            ['user_id' => $user->id],
            ['balance' => 0]
        );
    }

    /**
     * Credit coins to user wallet transactionally
     */
    public function credit(
        User|int $user,
        int $amount,
        string $type,
        ?int $referenceId = null,
        ?string $referenceType = null,
        ?string $description = null,
        ?array $metadata = null
    ): WalletTransaction {
        if ($amount <= 0) {
            throw new ApiException('Credit amount must be greater than zero.', 422);
        }

        $userId = $user instanceof User ? $user->id : $user;

        return DB::transaction(function () use ($userId, $amount, $type, $referenceId, $referenceType, $description, $metadata) {
            // Lock wallet row for update
            $wallet = Wallet::where('user_id', $userId)->lockForUpdate()->first();

            if (! $wallet) {
                $wallet = Wallet::create(['user_id' => $userId, 'balance' => 0]);
                // Re-lock
                $wallet = Wallet::where('user_id', $userId)->lockForUpdate()->first();
            }

            $balanceBefore = $wallet->balance;
            $balanceAfter = $balanceBefore + $amount;

            $wallet->balance = $balanceAfter;
            $wallet->save();

            return WalletTransaction::create([
                'wallet_id' => $wallet->id,
                'user_id' => $userId,
                'type' => $type,
                'amount' => $amount,
                'balance_before' => $balanceBefore,
                'balance_after' => $balanceAfter,
                'reference_type' => $referenceType,
                'reference_id' => $referenceId,
                'description' => $description,
                'metadata' => $metadata,
            ]);
        });
    }

    /**
     * Debit coins from user wallet with row locking and strict balance check
     */
    public function debit(
        User|int $user,
        int $amount,
        string $type,
        ?int $referenceId = null,
        ?string $referenceType = null,
        ?string $description = null,
        ?array $metadata = null
    ): WalletTransaction {
        if ($amount <= 0) {
            throw new ApiException('Debit amount must be greater than zero.', 422);
        }

        $userId = $user instanceof User ? $user->id : $user;

        return DB::transaction(function () use ($userId, $amount, $type, $referenceId, $referenceType, $description, $metadata) {
            $wallet = Wallet::where('user_id', $userId)->lockForUpdate()->first();

            if (! $wallet || $wallet->balance < $amount) {
                throw new InsufficientBalanceException(
                    "Insufficient coins. Required: {$amount}, Available: " . ($wallet?->balance ?? 0)
                );
            }

            $balanceBefore = $wallet->balance;
            $balanceAfter = $balanceBefore - $amount;

            $wallet->balance = $balanceAfter;
            $wallet->save();

            return WalletTransaction::create([
                'wallet_id' => $wallet->id,
                'user_id' => $userId,
                'type' => $type,
                'amount' => -$amount,
                'balance_before' => $balanceBefore,
                'balance_after' => $balanceAfter,
                'reference_type' => $referenceType,
                'reference_id' => $referenceId,
                'description' => $description,
                'metadata' => $metadata,
            ]);
        });
    }

    /**
     * Check if user has at least given amount of coins
     */
    public function hasBalance(User $user, int $amount): bool
    {
        $wallet = $this->getWallet($user);
        return $wallet->balance >= $amount;
    }
}
