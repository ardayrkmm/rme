<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();
$user = \App\Models\User::where('role', 'admin')->first();
if (!$user) $user = \App\Models\User::first();
echo json_encode(['role' => $user->role, 'can_manage' => (new \App\Policies\AppointmentPolicy)->manage($user)]);
