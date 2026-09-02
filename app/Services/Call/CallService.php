<?php

namespace App\Services\Call;

use App\Events\CallStateChangedEvent;
use App\Exceptions\ApiException;
use App\Models\Block;
use App\Models\Call;
use App\Models\CallParticipant;
use App\Models\User;
use App\Notifications\CallNotification;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CallService
{
    public function __construct(
        protected CallBillingService $billingService
    ) {}

    /**
     * Initiate a new voice or video call
     */
    public function initiateCall(User $caller, int $receiverId, string $type = 'voice', ?int $conversationId = null): Call
    {
        if ($caller->id === $receiverId) {
            throw new ApiException('Cannot call yourself.', 422);
        }

        $receiver = User::where('id', $receiverId)->where('status', 'active')->firstOrFail();

        // Check blocks
        $isBlocked = Block::where(function ($q) use ($caller, $receiverId) {
            $q->where('blocker_id', $caller->id)->where('blocked_id', $receiverId);
        })->orWhere(function ($q) use ($caller, $receiverId) {
            $q->where('blocker_id', $receiverId)->where('blocked_id', $caller->id);
        })->exists();

        if ($isBlocked) {
            throw new ApiException('Unable to place call to this user.', 403);
        }

        // Validate wallet balance for billable call
        $this->billingService->validatePreCallBalance($caller);

        return DB::transaction(function () use ($caller, $receiver, $receiverId, $type, $conversationId) {
            $channelName = 'call_' . Str::uuid()->toString();

            $call = Call::create([
                'caller_id' => $caller->id,
                'receiver_id' => $receiverId,
                'conversation_id' => $conversationId,
                'type' => $type,
                'status' => 'requested',
                'channel_name' => $channelName,
                'started_at' => Carbon::now(),
                'billing_status' => 'pending',
            ]);

            CallParticipant::create([
                'call_id' => $call->id,
                'user_id' => $caller->id,
                'role' => 'caller',
                'joined_at' => Carbon::now(),
            ]);

            CallParticipant::create([
                'call_id' => $call->id,
                'user_id' => $receiverId,
                'role' => 'receiver',
            ]);

            // Broadcast call event
            event(new CallStateChangedEvent($call, 'requested'));

            // Send push notification to receiver
            $receiver->notify(new CallNotification($call, $caller));

            return $call->load(['caller.profile', 'receiver.profile']);
        });
    }

    /**
     * Accept incoming call
     */
    public function acceptCall(User $user, int $callId, ?array $signalingData = null): Call
    {
        $call = Call::findOrFail($callId);

        if ($call->receiver_id !== $user->id) {
            throw new ApiException('Unauthorized.', 403);
        }

        if (! in_array($call->status, ['requested', 'ringing'])) {
            throw new ApiException('Call is no longer active.', 422);
        }

        return DB::transaction(function () use ($call, $user, $signalingData) {
            $call->update([
                'status' => 'accepted',
                'answered_at' => Carbon::now(),
                'signaling_data' => $signalingData,
            ]);

            CallParticipant::where('call_id', $call->id)
                ->where('user_id', $user->id)
                ->update(['joined_at' => Carbon::now()]);

            event(new CallStateChangedEvent($call, 'accepted'));

            return $call;
        });
    }

    /**
     * Reject incoming call
     */
    public function rejectCall(User $user, int $callId): Call
    {
        $call = Call::findOrFail($callId);

        if ($call->receiver_id !== $user->id && $call->caller_id !== $user->id) {
            throw new ApiException('Unauthorized.', 403);
        }

        $call->update([
            'status' => 'rejected',
            'ended_at' => Carbon::now(),
            'billing_status' => 'free',
        ]);

        event(new CallStateChangedEvent($call, 'rejected'));

        return $call;
    }

    /**
     * End active call and process billing
     */
    public function endCall(User $user, int $callId): Call
    {
        $call = Call::findOrFail($callId);

        if (! $call->isParticipant($user->id)) {
            throw new ApiException('Unauthorized.', 403);
        }

        if (in_array($call->status, ['ended', 'rejected', 'missed', 'cancelled'])) {
            return $call;
        }

        return DB::transaction(function () use ($call, $user) {
            $endedAt = Carbon::now();
            $durationSeconds = 0;

            if ($call->answered_at) {
                $durationSeconds = $call->answered_at->diffInSeconds($endedAt);
            }

            $call->update([
                'status' => 'ended',
                'ended_at' => $endedAt,
                'duration_seconds' => $durationSeconds,
            ]);

            CallParticipant::where('call_id', $call->id)
                ->where('user_id', $user->id)
                ->update(['left_at' => $endedAt]);

            // Finalize billing atomically
            $this->billingService->finalizeBilling($call);

            event(new CallStateChangedEvent($call, 'ended'));

            return $call;
        });
    }
}
