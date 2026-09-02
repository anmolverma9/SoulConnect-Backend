<?php

namespace App\Services\Dating;

use App\Exceptions\ApiException;
use App\Models\Block;
use App\Models\GiftCatalog;
use App\Models\GiftTransaction;
use App\Models\User;
use App\Notifications\GiftNotification;
use App\Services\Wallet\WalletService;
use Illuminate\Support\Facades\DB;

class GiftService
{
    public function __construct(
        protected WalletService $walletService
    ) {}

    /**
     * Get active gift catalog items
     */
    public function getCatalog()
    {
        return GiftCatalog::where('is_active', true)->orderBy('sort_order', 'asc')->get();
    }

    /**
     * Send virtual gift to user
     */
    public function sendGift(User $sender, int $receiverId, int $giftId, ?string $message = null): GiftTransaction
    {
        if ($sender->id === $receiverId) {
            throw new ApiException('You cannot send a gift to yourself.', 422);
        }

        $receiver = User::where('id', $receiverId)->where('status', 'active')->firstOrFail();
        $gift = GiftCatalog::where('id', $giftId)->where('is_active', true)->firstOrFail();

        // Check blocks
        $isBlocked = Block::where(function ($q) use ($sender, $receiverId) {
            $q->where('blocker_id', $sender->id)->where('blocked_id', $receiverId);
        })->orWhere(function ($q) use ($sender, $receiverId) {
            $q->where('blocker_id', $receiverId)->where('blocked_id', $sender->id);
        })->exists();

        if ($isBlocked) {
            throw new ApiException('Cannot send gift to this user.', 403);
        }

        return DB::transaction(function () use ($sender, $receiver, $gift, $message) {
            // Debit sender wallet
            $this->walletService->debit(
                $sender,
                $gift->coin_cost,
                'gift',
                $gift->id,
                'GiftCatalog',
                "Sent {$gift->name} to {$receiver->name}"
            );

            $transaction = GiftTransaction::create([
                'sender_id' => $sender->id,
                'receiver_id' => $receiver->id,
                'gift_id' => $gift->id,
                'coin_cost' => $gift->coin_cost,
                'message' => $message,
            ]);

            // Notify receiver
            $receiver->notify(new GiftNotification($gift, $sender, $message));

            return $transaction->load(['gift', 'sender.profile', 'receiver.profile']);
        });
    }
}
