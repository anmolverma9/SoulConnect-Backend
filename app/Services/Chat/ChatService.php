<?php

namespace App\Services\Chat;

use App\Events\MessageSentEvent;
use App\Exceptions\ApiException;
use App\Models\Block;
use App\Models\Conversation;
use App\Models\ConversationParticipant;
use App\Models\Message;
use App\Models\MessageRead;
use App\Models\User;
use App\Notifications\MessageNotification;
use App\Services\Media\ImageUploadService;
use Carbon\Carbon;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;

class ChatService
{
    public function __construct(
        protected ImageUploadService $imageUploadService
    ) {}

    /**
     * Get user's active conversations paginated
     */
    public function getUserConversations(User $user, int $perPage = 20): LengthAwarePaginator
    {
        return Conversation::query()
            ->whereHas('participants', function ($q) use ($user) {
                $q->where('user_id', $user->id);
            })
            ->with([
                'participants.user.profile',
                'participants.user.photos',
                'lastMessage',
            ])
            ->orderBy('last_message_at', 'desc')
            ->paginate($perPage);
    }

    /**
     * Get or create conversation between two users
     */
    public function getOrCreateConversation(User $user, int $otherUserId): Conversation
    {
        if ($user->id === $otherUserId) {
            throw new ApiException('Cannot create conversation with yourself.', 422);
        }

        // Verify users are not blocked
        $isBlocked = Block::where(function ($q) use ($user, $otherUserId) {
            $q->where('blocker_id', $user->id)->where('blocked_id', $otherUserId);
        })->orWhere(function ($q) use ($user, $otherUserId) {
            $q->where('blocker_id', $otherUserId)->where('blocked_id', $user->id);
        })->exists();

        if ($isBlocked) {
            throw new ApiException('Cannot communicate with this user.', 403);
        }

        // Find existing conversation
        $conversation = Conversation::whereHas('participants', function ($q) use ($user) {
            $q->where('user_id', $user->id);
        })->whereHas('participants', function ($q) use ($otherUserId) {
            $q->where('user_id', $otherUserId);
        })->first();

        if (! $conversation) {
            $conversation = DB::transaction(function () use ($user, $otherUserId) {
                $conv = Conversation::create(['type' => 'direct']);
                ConversationParticipant::create(['conversation_id' => $conv->id, 'user_id' => $user->id]);
                ConversationParticipant::create(['conversation_id' => $conv->id, 'user_id' => $otherUserId]);
                return $conv;
            });
        }

        return $conversation->load(['participants.user.profile', 'participants.user.photos', 'lastMessage']);
    }

    /**
     * Send message in conversation
     */
    public function sendMessage(
        User $sender,
        Conversation $conversation,
        ?string $body = null,
        ?string $type = 'text',
        ?UploadedFile $mediaFile = null
    ): Message {
        // Verify sender is participant
        if (! $conversation->isParticipant($sender->id)) {
            throw new ApiException('You are not a participant in this conversation.', 403);
        }

        // Check if other participant has blocked sender
        $otherParticipant = $conversation->participants()->where('user_id', '!=', $sender->id)->first();
        if ($otherParticipant) {
            $isBlocked = Block::where('blocker_id', $otherParticipant->user_id)
                ->where('blocked_id', $sender->id)
                ->exists();

            if ($isBlocked) {
                throw new ApiException('You cannot message this user.', 403);
            }
        }

        $mediaUrl = null;
        $mediaMetadata = null;

        if ($mediaFile) {
            $upload = $this->imageUploadService->uploadProfilePhoto($mediaFile, $sender->id);
            $mediaUrl = $upload['url'];
            $type = 'image';
        }

        return DB::transaction(function () use ($sender, $conversation, $body, $type, $mediaUrl, $mediaMetadata, $otherParticipant) {
            $message = Message::create([
                'conversation_id' => $conversation->id,
                'sender_id' => $sender->id,
                'type' => $type ?? 'text',
                'body' => $body,
                'media_url' => $mediaUrl,
                'media_metadata' => $mediaMetadata,
                'status' => 'sent',
            ]);

            $conversation->update([
                'last_message_id' => $message->id,
                'last_message_at' => Carbon::now(),
            ]);

            // Real-time broadcast
            event(new MessageSentEvent($message, $sender));

            // Automated Bot Reply if receiver is a bot
            if ($otherParticipant && $otherParticipant->user && $otherParticipant->user->is_bot) {
                $canned = \App\Models\BotCannedMessage::where('is_active', true)
                    ->inRandomOrder()
                    ->first();
                $botBody = $canned ? $canned->body : "Haha you're so fun to talk to! Tell me more 😊✨";

                $botMessage = Message::create([
                    'conversation_id' => $conversation->id,
                    'sender_id' => $otherParticipant->user_id,
                    'type' => 'text',
                    'body' => $botBody,
                    'status' => 'sent',
                ]);

                $conversation->update([
                    'last_message_id' => $botMessage->id,
                    'last_message_at' => Carbon::now()->addSecond(),
                ]);
            }

            // Send push notification to receiver if real user
            if ($otherParticipant && $otherParticipant->user && ! $otherParticipant->user->is_bot) {
                $otherParticipant->user->notify(new MessageNotification($message, $sender));
            }

            return $message;
        });
    }

    /**
     * Mark conversation messages as read
     */
    public function markAsRead(User $user, Conversation $conversation): int
    {
        if (! $conversation->isParticipant($user->id)) {
            throw new ApiException('Unauthorized.', 403);
        }

        return DB::transaction(function () use ($user, $conversation) {
            $unreadMessages = Message::where('conversation_id', $conversation->id)
                ->where('sender_id', '!=', $user->id)
                ->where('status', '!=', 'read')
                ->get();

            foreach ($unreadMessages as $msg) {
                $msg->update(['status' => 'read']);
                MessageRead::firstOrCreate([
                    'message_id' => $msg->id,
                    'user_id' => $user->id,
                ], [
                    'read_at' => Carbon::now(),
                ]);
            }

            ConversationParticipant::where('conversation_id', $conversation->id)
                ->where('user_id', $user->id)
                ->update(['last_read_at' => Carbon::now()]);

            return $unreadMessages->count();
        });
    }

    /**
     * Delete message (soft delete)
     */
    public function deleteMessage(User $user, int $messageId): bool
    {
        $message = Message::findOrFail($messageId);

        if ($message->sender_id !== $user->id) {
            throw new ApiException('You can only delete your own messages.', 403);
        }

        $message->update(['status' => 'deleted']);
        $message->delete();

        return true;
    }
}
