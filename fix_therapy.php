<?php

$file = 'e:/Bisnis/RekamMedis/backend_rekammedis/app/Http/Controllers/Api/V1/TherapySessionController.php';
$content = file_get_contents($file);

$oldUpdateMethodSignature = <<<PHP
    public function update(UpdateTherapySessionRequest \$request, \$id): JsonResponse
    {
        \$session = TherapySession::with('serviceMaster')->find(\$id);

        if (!\$session) {
            return response()->json([
                'success' => false,
                'message' => 'Therapy session not found'
            ], 404);
        }

        \$oldStatus = \$session->status;
PHP;

$newUpdateMethodStart = <<<PHP
    public function update(UpdateTherapySessionRequest \$request, \$id): JsonResponse
    {
        \$session = TherapySession::with('serviceMaster')->find(\$id);

        if (!\$session) {
            return response()->json([
                'success' => false,
                'message' => 'Therapy session not found'
            ], 404);
        }

        \$user = request()->user();
        if (\$user && \$user->role === \App\Enums\RoleEnum::FISIOTERAPIS) {
            \$physio = \App\Models\Physiotherapist::where('email', \$user->email)->first();
            if (\$physio && \$session->physiotherapist_id !== \$physio->id) {
                return response()->json([
                    'success' => false,
                    'message' => 'Akses ditolak: Anda tidak bisa mengubah sesi orang lain'
                ], 403);
            }
        }

        \$oldStatus = \$session->status;
        \$newStatus = \$request->input('status', \$oldStatus);

        if (\$oldStatus === 'completed' && \$newStatus === 'scheduled') {
            return response()->json([
                'success' => false,
                'message' => 'Sesi yang sudah diselesaikan tidak dapat diubah kembali menjadi Scheduled'
            ], 400);
        }

        if (\$newStatus === 'completed' && \$oldStatus !== 'completed') {
            // Check if Medical Record exists
            \$hasRecord = \App\Models\MedicalRecord::where('patient_id', \$session->patient_id)
                ->where('appointment_id', \$session->appointment_id)
                ->exists();

            if (!\$hasRecord) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tidak dapat menyelesaikan sesi: Data klinis (Rekam Medis) wajib diisi terlebih dahulu'
                ], 400);
            }
        }

        if (\$newStatus !== \$oldStatus) {
            \$valid = false;
            \$oldStatusLower = strtolower(\$oldStatus);
            \$newStatusLower = strtolower(\$newStatus);
            
            switch (\$oldStatusLower) {
                case 'scheduled':
                    if (in_array(\$newStatusLower, ['telah_tiba', 'patient arrived', 'cancelled', 'rescheduled'])) \$valid = true;
                    break;
                case 'telah_tiba':
                case 'patient arrived':
                    if (in_array(\$newStatusLower, ['ongoing', 'in progress', 'cancelled'])) \$valid = true;
                    break;
                case 'ongoing':
                case 'in progress':
                    if (\$newStatusLower === 'completed') \$valid = true;
                    break;
                case 'completed':
                case 'cancelled':
                    \$valid = false;
                    break;
                default:
                    \$valid = true; // allow unknown legacy statuses to be changed
            }

            if (!\$valid && !in_array(\$oldStatusLower, ['completed', 'cancelled'])) {
                // If it's already completed or cancelled, we don't strictly prevent changes to other non-scheduled statuses here since the first rule handles completed -> scheduled. But to perfectly match Go: Go says if valid=false return error.
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid status transition from ' . \$oldStatus . ' to ' . \$newStatus
                ], 400);
            }
        }
PHP;

$content = str_replace($oldUpdateMethodSignature, $newUpdateMethodStart, $content);
file_put_contents($file, $content);
echo "TherapySessionController updated\n";
