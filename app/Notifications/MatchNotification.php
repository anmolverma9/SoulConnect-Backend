<?php

namespace App\Notifications;

use App\Models\NotificationModel;
use App\Models\User;
use App\Services\Notification\FcmService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Str;

class MatchNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public User $matchedUser,
        public int $matchId
    ) {}

    public function via(object $notifiable): array
    {
        return ['database_custom'];
    }

    public function toCustom(object $notifiable): void
    {
        $title = "It's a Match! 🎉";
        $body = "You and {$this->matchedUser->name} liked each other!";
        $payload = [
            'type' => 'match',
            'match_id' => $this->matchId,
            'user_id' => $this->matchedUser->id,
            'user_name' => $this->matchedUser->name,
            'user_photo' => $this->matchedUser->primaryPhoto?->full_url,
        ];

        // Store notification record
        NotificationModel::create([
            'id' => Str::uuid()->toString(),
            'user_id' => $notifiable->id,
            'type' => 'match',
            'title' => $title,
            'body' => $body,
            'data' => $payload,
        ]);

        // Dispatch FCM Push Notification
        app(FcmService::class)->sendPushNotification($notifiable, $title, $body, $payload);
    }
}
