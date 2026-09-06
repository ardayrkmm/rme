<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();
$user = \App\Models\User::where('role', 'admin')->first() ?: \App\Models\User::first();
\Illuminate\Support\Facades\Auth::login($user);
$appointment = \App\Models\Appointment::latest()->first();
$res = app(\App\Http\Controllers\Api\V1\AppointmentController::class)->show($appointment->id);
echo $res->getContent();
