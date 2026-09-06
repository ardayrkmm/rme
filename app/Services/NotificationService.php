<?php

namespace App\Services;

use App\Interfaces\NotificationServiceInterface;
use App\Models\User;
use Exception;

class NotificationService implements NotificationServiceInterface
{
    public function getUserNotifications(string $userId, bool $unreadOnly = false, int $perPage = 15)
    {
        $user = User::find($userId);

        if (!$user) {
            throw new Exception('User tidak ditemukan', 404);
        }

        $query = $unreadOnly ? $user->unreadNotifications() : $user->notifications();

        return $query->paginate($perPage);
    }

    public function markAsRead(string $userId, string $notificationId)
    {
        $user = User::find($userId);
        $notification = $user->notifications()->where('id', $notificationId)->first();

        if (!$notification) {
            throw new Exception('Notifikasi tidak ditemukan', 404);
        }

        $notification->markAsRead();

        return $notification;
    }

    public function markAsUnread(string $userId, string $notificationId)
    {
        $user = User::find($userId);
        $notification = $user->notifications()->where('id', $notificationId)->first();

        if (!$notification) {
            throw new Exception('Notifikasi tidak ditemukan', 404);
        }

        // Eloquent/Laravel default does not have markAsUnread(), so we manually set read_at to null
        $notification->update(['read_at' => null]);

        return $notification;
    }

    public function markAllAsRead(string $userId)
    {
        $user = User::find($userId);
        $user->unreadNotifications->markAsRead();
    }

    public function deleteNotification(string $userId, string $notificationId)
    {
        $user = User::find($userId);
        $notification = $user->notifications()->where('id', $notificationId)->first();

        if (!$notification) {
            throw new Exception('Notifikasi tidak ditemukan', 404);
        }

        $notification->delete();
    }
}
