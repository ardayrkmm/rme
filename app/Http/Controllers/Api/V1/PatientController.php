<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Patient\StorePatientRequest;
use App\Http\Requests\Patient\UpdatePatientRequest;
use App\Http\Resources\PatientResource;
use App\Interfaces\PatientServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Exception;

class PatientController extends Controller
{
    protected $patientService;

    public function __construct(PatientServiceInterface $patientService)
    {
        $this->patientService = $patientService;
    }

    public function index(Request $request): JsonResponse
    {
        $this->authorize('viewAny', \App\Models\Patient::class);

        $filters = $request->only(['search']);
        $perPage = min(100, (int) $request->input('per_page', 15));

        $patients = $this->patientService->getPaginatedPatients($filters, $perPage);

        return $this->successResponse(
            PatientResource::collection($patients)->response()->getData(true),
            'Data pasien berhasil diambil'
        );
    }

    public function export(Request $request, \App\Services\CsvExportService $csvService)
    {
        $this->authorize('viewAny', \App\Models\Patient::class);
        $filters = $request->only(['search']);
        
        $exportData = $this->patientService->exportCsv($filters);
        $filename = 'patients_' . date('Y-m-d') . '.csv';

        return $csvService->download($filename, $exportData['headers'], $exportData['data']);
    }

    public function store(StorePatientRequest $request): JsonResponse
    {
        try {
            $patient = $this->patientService->createPatient($request->validated());
            return $this->successResponse(new PatientResource($patient), 'Pasien berhasil dibuat', 201);
        } catch (Exception $e) {
            return $this->errorResponse('Gagal membuat pasien: ' . $e->getMessage(), 500);
        }
    }

    public function show(string $id): JsonResponse
    {
        $this->authorize('view', \App\Models\Patient::class);

        try {
            $patient = $this->patientService->getPatientById($id);
            return $this->successResponse(new PatientResource($patient), 'Data pasien berhasil diambil');
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), 404);
        }
    }

    public function update(UpdatePatientRequest $request, string $id): JsonResponse
    {
        try {
            $patient = $this->patientService->updatePatient($id, $request->validated());
            return $this->successResponse(new PatientResource($patient), 'Pasien berhasil diupdate');
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), $e->getCode() ?: 500);
        }
    }

    public function destroy(string $id): JsonResponse
    {
        $this->authorize('manage', \App\Models\Patient::class);

        try {
            $this->patientService->deletePatient($id);
            return $this->successResponse(null, 'Pasien berhasil dihapus');
        } catch (Exception $e) {
            return $this->errorResponse('Gagal menghapus pasien', 500);
        }
    }

    public function restore(string $id): JsonResponse
    {
        $this->authorize('manage', \App\Models\Patient::class);

        try {
            $this->patientService->restorePatient($id);
            return $this->successResponse(null, 'Pasien berhasil direstore');
        } catch (Exception $e) {
            return $this->errorResponse('Gagal merestore pasien', 500);
        }
    }
}
