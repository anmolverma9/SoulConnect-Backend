<?php

namespace App\Notifications;

use App\Models\GiftCatalog;
use App\Models\NotificationModel;
use App\Models\User;
use App\Services\Notification\FcmService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Str;

class GiftNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public GiftCatalog $gift,
        public User $sender,
        public ?string $message = null
    ) {}

    public function via(object $notifiable): array
    {
        return ['database_custom'];
    }

    public function toCustom(object $notifiable): void
    {
        $senderName = $this->sender->name ?? 'Someone';
        $title = "You received a Gift! 🎁";
        $body = "{$senderName} sent you a {$this->gift->name}!";

        $payload = [
            'type' => 'gift',
            'gift_id' => $this->gift->id,
            'gift_name' => $this->gift->name,
            'sender_id' => $this->sender->id,
            'sender_name' => $senderName,
            'message' => $this->message,
        ];

        NotificationModel::create([
            'id' => Str::uuid()->toString(),
            'user_id' => $notifiable->id,
            'type' => 'gift',
            'title' => $title,
            'body' => $body,
            'data' => $payload,
        ]);

        app(FcmService::class)->sendPushNotification($notifiable, $title, $body, $payload);
    }
}
