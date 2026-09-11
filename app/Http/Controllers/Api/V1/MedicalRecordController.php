<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\MedicalRecord\StoreMedicalRecordRequest;
use App\Http\Requests\MedicalRecord\UpdateMedicalRecordRequest;
use App\Http\Resources\MedicalRecordResource;
use App\Interfaces\MedicalRecordServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Exception;

class MedicalRecordController extends Controller
{
    protected $medicalRecordService;

    public function __construct(MedicalRecordServiceInterface $medicalRecordService)
    {
        $this->medicalRecordService = $medicalRecordService;
    }

    public function index(Request $request): JsonResponse
    {
        $this->authorize('viewAny', \App\Models\MedicalRecord::class);

        $filters = $request->only(['search']);
        
        $user = $request->user();
        if ($user && $user->role === \App\Enums\RoleEnum::FISIOTERAPIS) {
            $physio = \App\Models\Physiotherapist::where('email', $user->email)->first();
            if ($physio) {
                $filters['treated_by_physio_id'] = $physio->id;
            }
        }

        $perPage = min(10000, (int) $request->input('per_page', 15));

        $records = $this->medicalRecordService->getPaginatedRecords($filters, $perPage);

        return $this->successResponse(
            MedicalRecordResource::collection($records)->response()->getData(true),
            'Data rekam medis berhasil diambil'
        );
    }

    public function history(Request $request, string $patientId): JsonResponse
    {
        $this->authorize('viewAny', \App\Models\MedicalRecord::class);

        $user = $request->user();
        if ($user && $user->role === \App\Enums\RoleEnum::FISIOTERAPIS) {
            $physio = \App\Models\Physiotherapist::where('email', $user->email)->first();
            if ($physio) {
                $hasTreated = \App\Models\TherapySession::where('physiotherapist_id', $physio->id)
                    ->where('patient_id', $patientId)
                    ->exists();
                
                if (!$hasTreated) {
                    return $this->errorResponse('Akses ditolak: Anda belum pernah menangani pasien ini', 403);
                }
            }
        }

        $filters = $request->only([]);
        $perPage = min(10000, (int) $request->input('per_page', 15));

        $records = $this->medicalRecordService->getPatientHistory($patientId, $filters, $perPage);

        return $this->successResponse(
            MedicalRecordResource::collection($records)->response()->getData(true),
            'Histori rekam medis pasien berhasil diambil'
        );
    }

    public function store(StoreMedicalRecordRequest $request): JsonResponse
    {
        $user = $request->user();
        if ($user->role === \App\Enums\RoleEnum::FISIOTERAPIS) {
            $physio = \App\Models\Physiotherapist::where('email', $user->email)->first();
            if (!$physio || $request->input('physiotherapist_id') != $physio->id) {
                return $this->errorResponse('Akses ditolak: Anda tidak bisa membuat catatan klinis untuk fisioterapis lain', 403);
            }
        }

        try {
            $record = $this->medicalRecordService->createRecord($request->validated());
            $record->load(['patient', 'physiotherapist', 'appointment', 'service']);
            return $this->successResponse(new MedicalRecordResource($record), 'Rekam medis berhasil dibuat', 201);
        } catch (Exception $e) {
            return $this->errorResponse('Gagal membuat rekam medis: ' . $e->getMessage(), 500);
        }
    }

    public function show(string $id): JsonResponse
    {
        $this->authorize('view', \App\Models\MedicalRecord::class);

        try {
            $record = $this->medicalRecordService->getMedicalRecordById($id);
            return $this->successResponse(new MedicalRecordResource($record), 'Detail rekam medis berhasil diambil');
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), 404);
        }
    }

    public function update(UpdateMedicalRecordRequest $request, string $id): JsonResponse
    {
        $user = $request->user();
        if ($user->role === \App\Enums\RoleEnum::FISIOTERAPIS) {
            $physio = \App\Models\Physiotherapist::where('email', $user->email)->first();
            $existing = \App\Models\MedicalRecord::find($id);
            if (!$existing) {
                return $this->errorResponse('Rekam medis tidak ditemukan', 404);
            }
            if (!$physio || $existing->physiotherapist_id != $physio->id || $request->input('physiotherapist_id', $existing->physiotherapist_id) != $physio->id) {
                return $this->errorResponse('Akses ditolak: Anda tidak berwenang mengedit catatan klinis ini', 403);
            }
        }

        try {
            $record = $this->medicalRecordService->updateRecord($id, $request->validated());
            $record->load(['patient', 'physiotherapist', 'appointment', 'service']);
            return $this->successResponse(new MedicalRecordResource($record), 'Rekam medis berhasil diupdate');
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), $e->getCode() ?: 500);
        }
    }

    public function destroy(string $id): JsonResponse
    {
        $this->authorize('delete', \App\Models\MedicalRecord::class);

        try {
            $this->medicalRecordService->deleteRecord($id);
            return $this->successResponse(null, 'Rekam medis berhasil dihapus');
        } catch (Exception $e) {
            return $this->errorResponse('Gagal menghapus rekam medis', 500);
        }
    }

    public function exportPdf(string $id)
    {
        $this->authorize('view', \App\Models\MedicalRecord::class);

        try {
            if (!class_exists('PDF')) {
                return response()->make('dummy pdf', 200, [
                    'Content-Type' => 'application/pdf',
                    'Content-Disposition' => 'attachment; filename="rekam_medis_' . $id . '.pdf"'
                ]);
            }

            $pdf = $this->medicalRecordService->exportPdf($id);
            
            // Return file download
            return $pdf->download('rekam_medis_' . $id . '.pdf');
        } catch (Exception $e) {
            return $this->errorResponse('Gagal ekspor PDF: ' . $e->getMessage(), 500);
        }
    }
}
