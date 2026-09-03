<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ConversationResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $currentUserId = $request->user()?->id;
        $otherParticipant = $this->participants->firstWhere('user_id', '!=', $currentUserId);
        $otherUser = $otherParticipant?->user;

        $unreadCount = $currentUserId ? \App\Models\Message::where('conversation_id', $this->id)
            ->where('sender_id', '!=', $currentUserId)
            ->whereNull('read_at')
            ->count() : 0;

        return [
            'id' => $this->id,
            'type' => $this->type,
            'match_id' => $this->match_id,
            'last_message_at' => $this->last_message_at?->toIso8601String(),
            'unread_count' => $unreadCount,
            'other_user' => $otherUser ? [
                'id' => $otherUser->id,
                'name' => $otherUser->name,
                'primary_photo' => $otherUser->primaryPhoto?->full_url,
                'is_online' => $otherUser->last_active_at && $otherUser->last_active_at->diffInMinutes(now()) < 5,
            ] : null,
            'last_message' => new MessageResource($this->whenLoaded('lastMessage')),
        ];
    }
}
