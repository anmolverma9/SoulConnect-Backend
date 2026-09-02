<?php

namespace App\Services\Engagement;

use App\Models\EngagementEvent;
use App\Models\EngagementRule;
use App\Models\NotificationModel;
use App\Models\User;
use App\Services\Notification\FcmService;
use Carbon\Carbon;
use Illuminate\Support\Str;

class EngagementService
{
    public function __construct(
        protected FcmService $fcmService
    ) {}

    /**
     * Trigger engagement evaluation for a user
     */
    public function triggerUserEngagement(User $user, string $eventType, array $payload = []): ?EngagementEvent
    {
        $rule = EngagementRule::where('event_type', $eventType)
            ->where('is_enabled', true)
            ->orderBy('priority', 'desc')
            ->first();

        if (! $rule) {
            return null;
        }

        // Check cooldown
        $cooldownCutoff = Carbon::now()->subHours($rule->cooldown_hours);
        $recentTrigger = EngagementEvent::where('user_id', $user->id)
            ->where('event_type', $eventType)
            ->where('triggered_at', '>', $cooldownCutoff)
            ->exists();

        if ($recentTrigger) {
            return null;
        }

        // Check daily limit across all events for this user
        $dailyCount = EngagementEvent::where('user_id', $user->id)
            ->where('triggered_at', '>=', Carbon::today())
            ->where('status', 'sent')
            ->count();

        if ($dailyCount >= $rule->daily_limit) {
            return null;
        }

        $message = str_replace(
            ['{name}', '{app_name}'],
            [$user->name ?? 'there', config('app.name')],
            $rule->message_template
        );

        // Create engagement event record
        $event = EngagementEvent::create([
            'user_id' => $user->id,
            'rule_id' => $rule->id,
            'event_type' => $eventType,
            'payload' => $payload,
            'triggered_at' => Carbon::now(),
            'status' => 'sent',
        ]);

        // Send notification & push
        NotificationModel::create([
            'id' => Str::uuid()->toString(),
            'user_id' => $user->id,
            'type' => 'system',
            'title' => $rule->title,
            'body' => $message,
            'data' => array_merge($payload, ['event_type' => $eventType]),
        ]);

        $this->fcmService->sendPushNotification($user, $rule->title, $message, array_merge($payload, ['type' => 'system']));

        return $event;
    }

    /**
     * Process inactive users engagement batch
     */
    public function processInactiveUsersBatch(int $inactiveDays = 3, int $limit = 100): int
    {
        $cutoff = Carbon::now()->subDays($inactiveDays);

        $inactiveUsers = User::where('status', 'active')
            ->where(function ($q) use ($cutoff) {
                $q->where('last_active_at', '<', $cutoff)
                  ->orWhereNull('last_active_at');
            })
            ->limit($limit)
            ->get();

        $count = 0;
        foreach ($inactiveUsers as $user) {
            $triggered = $this->triggerUserEngagement($user, 'inactive_reminder', [
                'inactive_days' => $inactiveDays,
            ]);

            if ($triggered) {
                $count++;
            }
        }

        return $count;
    }
}
