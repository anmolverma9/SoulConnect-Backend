<?php

namespace App\Notifications;

use App\Models\Call;
use App\Models\NotificationModel;
use App\Models\User;
use App\Services\Notification\FcmService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Str;

class CallNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public Call $call,
        public User $caller
    ) {}

    public function via(object $notifiable): array
    {
        return ['database_custom'];
    }

    public function toCustom(object $notifiable): void
    {
        $callerName = $this->caller->name ?? 'Someone';
        $typeLabel = ucfirst($this->call->type);
        $title = "Incoming {$typeLabel} Call";
        $body = "{$callerName} is calling you...";

        $payload = [
            'type' => 'call',
            'call_id' => $this->call->id,
            'call_type' => $this->call->type,
            'channel_name' => $this->call->channel_name,
            'caller_id' => $this->caller->id,
            'caller_name' => $callerName,
            'caller_photo' => $this->caller->primaryPhoto?->full_url,
        ];

        NotificationModel::create([
            'id' => Str::uuid()->toString(),
            'user_id' => $notifiable->id,
            'type' => 'call',
            'title' => $title,
            'body' => $body,
            'data' => $payload,
        ]);

        app(FcmService::class)->sendPushNotification($notifiable, $title, $body, $payload);
    }
}
