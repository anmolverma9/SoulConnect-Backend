<?php

namespace App\Events;

use App\Models\MatchModel;
use App\Models\User;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MatchCreatedEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public MatchModel $match,
        public User $userOne,
        public User $userTwo
    ) {}

    public function broadcastOn(): array
    {
        return [
            new PrivateChannel("user.{$this->userOne->id}"),
            new PrivateChannel("user.{$this->userTwo->id}"),
        ];
    }

    public function broadcastWith(): array
    {
        return [
            'match_id' => $this->match->id,
            'matched_at' => $this->match->matched_at->toIso8601String(),
            'user' => [
                'id' => $this->userOne->id,
                'name' => $this->userOne->name,
                'primary_photo' => $this->userOne->primaryPhoto?->full_url,
            ],
        ];
    }
}
