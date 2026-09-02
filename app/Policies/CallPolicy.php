<?php

namespace App\Policies;

use App\Models\Call;
use App\Models\User;

class CallPolicy
{
    /**
     * Determine whether the user can view, accept, reject or end the call.
     */
    public function view(User $user, Call $call): bool
    {
        return $call->isParticipant($user->id);
    }

    public function accept(User $user, Call $call): bool
    {
        return $call->receiver_id === $user->id && in_array($call->status, ['requested', 'ringing']);
    }

    public function reject(User $user, Call $call): bool
    {
        return $call->receiver_id === $user->id && in_array($call->status, ['requested', 'ringing']);
    }

    public function end(User $user, Call $call): bool
    {
        return $call->isParticipant($user->id) && in_array($call->status, ['requested', 'ringing', 'accepted']);
    }
}
