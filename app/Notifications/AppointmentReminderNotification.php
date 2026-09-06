<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use App\Models\Appointment;

class AppointmentReminderNotification extends Notification
{
    use Queueable;

    protected $appointment;

    public function __construct(Appointment $appointment)
    {
        $this->appointment = $appointment;
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'type' => 'appointment_reminder',
            'title' => 'Pengingat Jadwal Temu Janji',
            'message' => 'Anda memiliki jadwal temu janji pada ' . $this->appointment->appointment_date->format('d/m/Y') . ' jam ' . $this->appointment->appointment_time,
            'appointment_id' => $this->appointment->id,
            'url' => '/appointments/' . $this->appointment->id
        ];
    }
}
