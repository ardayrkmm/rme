<?php

$file = 'e:/Bisnis/RekamMedis/backend_rekammedis/app/Services/AppointmentService.php';
$content = file_get_contents($file);

$oldCode = <<<PHP
        // Auto-create Therapy Session if service_master_id is present
        if (!empty(\$data['service_master_id'])) {
            \App\Models\TherapySession::create([
                'patient_id' => \$appointment->patient_id,
                'physiotherapist_id' => \$appointment->physiotherapist_id,
                'appointment_id' => \$appointment->id,
                'service_master_id' => \$appointment->service_master_id,
                'therapy_date' => \$appointment->appointment_date,
                'complaint' => \$appointment->complaint ?? '-',
                'treatment_given' => '-',
                'status' => 'scheduled'
            ]);
        }
PHP;

$newCode = <<<PHP
        // MATCH GO: Auto-create Payment (Invoice) on Appointment creation instead of TherapySession
        if (!empty(\$data['service_master_id'])) {
            \$service = \App\Models\ServiceMaster::find(\$data['service_master_id']);
            if (\$service) {
                \$invoiceNumber = 'INV-' . date('Ymd') . '-' . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT);
                \$payment = \App\Models\Payment::create([
                    'invoice_number' => \$invoiceNumber,
                    'appointment_id' => \$appointment->id,
                    'patient_id' => \$appointment->patient_id,
                    'physiotherapist_id' => \$appointment->physiotherapist_id,
                    'payment_date' => \$appointment->appointment_date,
                    'payment_method' => 'cash',
                    'status' => 'pending',
                    'subtotal' => \$service->price,
                    'total' => \$service->price,
                    'discount' => 0,
                    'tax' => 0,
                    'notes' => 'Tagihan otomatis dari Pembuatan Janji Temu'
                ]);

                \App\Models\PaymentDetail::create([
                    'payment_id' => \$payment->id,
                    'service_master_id' => \$service->id,
                    'service_name' => \$service->name,
                    'quantity' => 1,
                    'price' => \$service->price,
                    'subtotal' => \$service->price
                ]);
            }
        }
PHP;

$content = str_replace($oldCode, $newCode, $content);
file_put_contents($file, $content);
echo "AppointmentService updated\n";
