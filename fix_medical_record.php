<?php

$file = 'e:/Bisnis/RekamMedis/backend_rekammedis/app/Http/Controllers/Api/V1/MedicalRecordController.php';
$content = file_get_contents($file);

// Fix index method
$oldIndex = <<<PHP
    public function index(Request \$request): JsonResponse
    {
        \$this->authorize('viewAny', \App\Models\MedicalRecord::class);

        \$filters = \$request->only(['search']);
        \$perPage = min(100, (int) \$request->input('per_page', 15));

        \$records = \$this->medicalRecordService->getPaginatedRecords(\$filters, \$perPage);

        return \$this->successResponse(
            MedicalRecordResource::collection(\$records)->response()->getData(true),
            'Data rekam medis berhasil diambil'
        );
    }
PHP;

$newIndex = <<<PHP
    public function index(Request \$request): JsonResponse
    {
        \$this->authorize('viewAny', \App\Models\MedicalRecord::class);

        \$filters = \$request->only(['search']);
        
        \$user = \$request->user();
        if (\$user && \$user->role === \App\Enums\RoleEnum::FISIOTERAPIS) {
            \$physio = \App\Models\Physiotherapist::where('email', \$user->email)->first();
            if (\$physio) {
                \$filters['treated_by_physio_id'] = \$physio->id;
            }
        }

        \$perPage = min(100, (int) \$request->input('per_page', 15));

        \$records = \$this->medicalRecordService->getPaginatedRecords(\$filters, \$perPage);

        return \$this->successResponse(
            MedicalRecordResource::collection(\$records)->response()->getData(true),
            'Data rekam medis berhasil diambil'
        );
    }
PHP;
$content = str_replace($oldIndex, $newIndex, $content);

// Fix history method
$oldHistory = <<<PHP
    public function history(Request \$request, int \$patientId): JsonResponse
    {
        \$this->authorize('viewAny', \App\Models\MedicalRecord::class);

        \$filters = \$request->only([]);
        \$perPage = min(100, (int) \$request->input('per_page', 15));

        \$records = \$this->medicalRecordService->getPatientHistory(\$patientId, \$filters, \$perPage);

        return \$this->successResponse(
            MedicalRecordResource::collection(\$records)->response()->getData(true),
            'Histori rekam medis pasien berhasil diambil'
        );
    }
PHP;

$newHistory = <<<PHP
    public function history(Request \$request, int \$patientId): JsonResponse
    {
        \$this->authorize('viewAny', \App\Models\MedicalRecord::class);

        \$user = \$request->user();
        if (\$user && \$user->role === \App\Enums\RoleEnum::FISIOTERAPIS) {
            \$physio = \App\Models\Physiotherapist::where('email', \$user->email)->first();
            if (\$physio) {
                \$hasTreated = \App\Models\TherapySession::where('physiotherapist_id', \$physio->id)
                    ->where('patient_id', \$patientId)
                    ->exists();
                
                if (!\$hasTreated) {
                    return \$this->errorResponse('Akses ditolak: Anda belum pernah menangani pasien ini', 403);
                }
            }
        }

        \$filters = \$request->only([]);
        \$perPage = min(100, (int) \$request->input('per_page', 15));

        \$records = \$this->medicalRecordService->getPatientHistory(\$patientId, \$filters, \$perPage);

        return \$this->successResponse(
            MedicalRecordResource::collection(\$records)->response()->getData(true),
            'Histori rekam medis pasien berhasil diambil'
        );
    }
PHP;
$content = str_replace($oldHistory, $newHistory, $content);

// Fix show method
$oldShow = <<<PHP
    public function show(int \$id): JsonResponse
    {
        \$this->authorize('view', \App\Models\MedicalRecord::class);

        try {
            \$record = \$this->medicalRecordService->getMedicalRecordById(\$id);
            return \$this->successResponse(new MedicalRecordResource(\$record), 'Data rekam medis berhasil diambil');
        } catch (Exception \$e) {
            return \$this->errorResponse(\$e->getMessage(), \$e->getCode() ?: 404);
        }
    }
PHP;

$newShow = <<<PHP
    public function show(int \$id): JsonResponse
    {
        \$this->authorize('view', \App\Models\MedicalRecord::class);

        try {
            \$record = \$this->medicalRecordService->getMedicalRecordById(\$id);
            
            \$user = request()->user();
            if (\$user && \$user->role === \App\Enums\RoleEnum::FISIOTERAPIS) {
                \$physio = \App\Models\Physiotherapist::where('email', \$user->email)->first();
                if (\$physio) {
                    \$hasTreated = \App\Models\TherapySession::where('physiotherapist_id', \$physio->id)
                        ->where('patient_id', \$record->patient_id)
                        ->exists();
                    
                    if (!\$hasTreated) {
                        return \$this->errorResponse('Akses ditolak: Anda belum pernah menangani pasien ini', 403);
                    }
                }
            }

            return \$this->successResponse(new MedicalRecordResource(\$record), 'Data rekam medis berhasil diambil');
        } catch (Exception \$e) {
            return \$this->errorResponse(\$e->getMessage(), \$e->getCode() ?: 404);
        }
    }
PHP;
$content = str_replace($oldShow, $newShow, $content);

file_put_contents($file, $content);
echo "MedicalRecordController updated\n";
