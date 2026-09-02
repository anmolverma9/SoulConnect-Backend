<?php

use App\Models\Call;
use App\Models\Conversation;
use App\Models\User;
use Illuminate\Support\Facades\Broadcast;

/*
|--------------------------------------------------------------------------
| Broadcast Channels — Real-time WebSockets / Reverb
|--------------------------------------------------------------------------
*/

Broadcast::channel('user.{id}', function (User $user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('conversation.{conversationId}', function (User $user, $conversationId) {
    $conversation = Conversation::find($conversationId);
    return $conversation && $conversation->isParticipant($user->id);
});

Broadcast::channel('call.{callId}', function (User $user, $callId) {
    $call = Call::find($callId);
    return $call && $call->isParticipant($user->id);
});
