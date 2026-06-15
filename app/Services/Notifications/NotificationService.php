<?php

namespace App\Services\Notifications;

use App\Models\User;
use App\Models\Notifications\Notification;
use Illuminate\Database\Eloquent::Collection;

class NotificationService
{
    public function createNotification(User $user, array $data): Notification
    {
        return $user->notifications()->create($data);
    }

    public function markAsRead(Notification $notification): Notification
    {
        $notification->update([
            'is_read' => true,
            'read_at' => now(),
        ]);
        return $notification->refresh();
    }

    public function markAllAsRead(User $user): void
    {
        $user->notifications()
            ->where('is_read', false)
            ->update([
                'is_read' => true,
                'read_at' => now(),
            ]);
    }

    public function deleteNotification(Notification $notification): bool
    {
        return (bool) $notification->delete();
    }

    public function getUserNotifications(User $user, int $limit = 20): Collection
    {
        return $user->notifications()
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
    }

    public function getUnreadCount(User $user): int
    {
        return $user->notifications()
            ->where('is_read', false)
            ->count();
    }
}
