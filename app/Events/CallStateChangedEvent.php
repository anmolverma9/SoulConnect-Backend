<?php

namespace App\Events;

use App\Models\Call;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class CallStateChangedEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public Call $call,
        public string $state
    ) {}

    public function broadcastOn(): array
    {
        return [
            new PrivateChannel("user.{$this->call->caller_id}"),
            new PrivateChannel("user.{$this->call->receiver_id}"),
            new PrivateChannel("call.{$this->call->id}"),
        ];
    }

    public function broadcastWith(): array
    {
        return [
            'call_id' => $this->call->id,
            'state' => $this->state,
            'type' => $this->call->type,
            'channel_name' => $this->call->channel_name,
            'duration_seconds' => $this->call->duration_seconds,
            'signaling_data' => $this->call->signaling_data,
        ];
    }
}
