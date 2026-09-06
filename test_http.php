<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$user = \App\Models\User::where('role', 'admin')->first() ?: \App\Models\User::first();
$appointment = \App\Models\Appointment::latest()->first();

$response = $kernel->handle(
    $request = \Illuminate\Http\Request::create(
        '/api/v1/appointments/' . $appointment->id,
        'PUT',
        [
            'patient_id' => $appointment->patient_id,
            'physiotherapist_id' => $appointment->physiotherapist_id,
            'service_master_id' => $appointment->service_master_id,
            'appointment_date' => \Carbon\Carbon::parse($appointment->appointment_date)->format('Y-m-d'),
            'appointment_time' => substr($appointment->appointment_time, 0, 5),
            'status' => 'telah_tiba',
        ]
    )->setUserResolver(function () use ($user) {
        return $user;
    })
);

echo $response->status() . "\n";
echo $response->getContent();
