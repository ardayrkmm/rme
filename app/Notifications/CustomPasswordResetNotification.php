<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class CustomPasswordResetNotification extends Notification
{
    use Queueable;

    public function __construct()
    {
        //
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'type' => 'password_reset',
            'title' => 'Password Berhasil Direset',
            'message' => 'Password akun Anda telah berhasil diubah baru-baru ini. Jika ini bukan Anda, segera hubungi Administrator.',
            'url' => null
        ];
    }
}
