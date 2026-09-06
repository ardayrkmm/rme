<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();
$req = new \Illuminate\Http\Request();
$req->merge([
    'patient_id' => 3, 
    'physiotherapist_id' => 1, 
    'service_master_id' => 2, 
    'appointment_date' => '2026-07-11', 
    'appointment_time' => '09:00', 
    'status' => 'telah_tiba'
]);
$val = \Illuminate\Support\Facades\Validator::make($req->all(), (new \App\Http\Requests\Appointment\UpdateAppointmentRequest)->rules());
echo json_encode(['passes' => $val->passes(), 'errors' => $val->errors()->toArray()]);
