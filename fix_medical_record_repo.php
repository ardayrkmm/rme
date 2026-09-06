<?php

$file = 'e:/Bisnis/RekamMedis/backend_rekammedis/app/Repositories/MedicalRecordRepository.php';
$content = file_get_contents($file);

$oldCode = <<<PHP
        // Filter pencarian nama pasien
        if (isset(\$filters['search']) && !empty(\$filters['search'])) {
            \$search = \$filters['search'];
            \$query->whereHas('patient', function (\$q) use (\$search) {
                \$q->where('name', 'like', '%' . \$search . '%')
                  ->orWhere('medical_record_number', 'like', '%' . \$search . '%');
            });
        }
PHP;

$newCode = <<<PHP
        // Filter pencarian nama pasien
        if (isset(\$filters['search']) && !empty(\$filters['search'])) {
            \$search = \$filters['search'];
            \$query->whereHas('patient', function (\$q) use (\$search) {
                \$q->where('name', 'like', '%' . \$search . '%')
                  ->orWhere('medical_record_number', 'like', '%' . \$search . '%');
            });
        }

        // Filter untuk Fisioterapis: hanya melihat rekam medis pasien yang pernah ditangani
        if (isset(\$filters['treated_by_physio_id']) && !empty(\$filters['treated_by_physio_id'])) {
            \$physioId = \$filters['treated_by_physio_id'];
            \$treatedPatientIds = \App\Models\TherapySession::where('physiotherapist_id', \$physioId)
                ->pluck('patient_id')
                ->unique()
                ->toArray();
            
            \$query->whereIn('patient_id', \$treatedPatientIds);
        }
PHP;

$content = str_replace($oldCode, $newCode, $content);
file_put_contents($file, $content);
echo "MedicalRecordRepository updated\n";
