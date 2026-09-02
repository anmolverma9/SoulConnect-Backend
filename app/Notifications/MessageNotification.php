<?php

namespace App\Notifications;

use App\Models\Message;
use App\Models\NotificationModel;
use App\Models\User;
use App\Services\Notification\FcmService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Str;

class MessageNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public Message $message,
        public User $sender
    ) {}

    public function via(object $notifiable): array
    {
        return ['database_custom'];
    }

    public function toCustom(object $notifiable): void
    {
        $senderName = $this->sender->name ?? 'Someone';
        $title = "New message from {$senderName}";
        $body = $this->message->type === 'text' ? Str::limit($this->message->body, 100) : "Sent an {$this->message->type}";

        $payload = [
            'type' => 'message',
            'conversation_id' => $this->message->conversation_id,
            'message_id' => $this->message->id,
            'sender_id' => $this->sender->id,
            'sender_name' => $senderName,
        ];

        NotificationModel::create([
            'id' => Str::uuid()->toString(),
            'user_id' => $notifiable->id,
            'type' => 'message',
            'title' => $title,
            'body' => $body,
            'data' => $payload,
        ]);

        app(FcmService::class)->sendPushNotification($notifiable, $title, $body, $payload);
    }
}
