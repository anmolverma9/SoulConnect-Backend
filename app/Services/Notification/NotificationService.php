<?php

namespace App\Services\Notification;

use App\Models\NotificationModel;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class NotificationService
{
    /**
     * Get user notifications paginated
     */
    public function getUserNotifications(User $user, int $perPage = 20): LengthAwarePaginator
    {
        return NotificationModel::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);
    }

    /**
     * Mark single notification as read
     */
    public function markAsRead(User $user, string $notificationId): NotificationModel
    {
        $notification = NotificationModel::where('user_id', $user->id)
            ->where('id', $notificationId)
            ->firstOrFail();

        $notification->update([
            'is_read' => true,
            'read_at' => now(),
        ]);

        return $notification;
    }

    /**
     * Mark all notifications as read
     */
    public function markAllAsRead(User $user): int
    {
        return NotificationModel::where('user_id', $user->id)
            ->where('is_read', false)
            ->update([
                'is_read' => true,
                'read_at' => now(),
            ]);
    }
}
