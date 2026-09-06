<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$user = \App\Models\User::where('role', 'admin')->first() ?: \App\Models\User::first();
\Illuminate\Support\Facades\Auth::login($user);

$appointment = \App\Models\Appointment::latest()->first();
$req = \Illuminate\Http\Request::create('/api/v1/appointments/' . $appointment->id, 'PUT', [
    'patient_id' => $appointment->patient_id,
    'physiotherapist_id' => $appointment->physiotherapist_id,
    'service_master_id' => $appointment->service_master_id,
    'appointment_date' => \Carbon\Carbon::parse($appointment->appointment_date)->format('Y-m-d'),
    'appointment_time' => substr($appointment->appointment_time, 0, 5),
    'status' => 'telah_tiba',
]);
$res = app(\App\Http\Controllers\Api\V1\AppointmentController::class)->update(
    \App\Http\Requests\Appointment\UpdateAppointmentRequest::createFrom($req),
    $appointment->id
);

echo $res->getContent();
