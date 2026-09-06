<?php

namespace App\Interfaces;

interface NotificationServiceInterface
{
    public function getUserNotifications(string $userId, bool $unreadOnly = false, int $perPage = 15);
    public function markAsRead(string $userId, string $notificationId);
    public function markAsUnread(string $userId, string $notificationId);
    public function markAllAsRead(string $userId);
    public function deleteNotification(string $userId, string $notificationId);
}
