<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\ServiceMaster\StoreServiceMasterRequest;
use App\Http\Requests\ServiceMaster\UpdateServiceMasterRequest;
use App\Http\Resources\ServiceMasterResource;
use App\Interfaces\ServiceMasterServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Exception;

class ServiceMasterController extends Controller
{
    protected $serviceMasterService;

    public function __construct(ServiceMasterServiceInterface $serviceMasterService)
    {
        $this->serviceMasterService = $serviceMasterService;
    }

    public function index(Request $request): JsonResponse
    {
        $filters = $request->only(['search', 'category', 'is_active']);
        
        if (isset($filters['is_active'])) {
            $filters['is_active'] = filter_var($filters['is_active'], FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);
        }

        $perPage = min(10000, (int) $request->input('per_page', 15));

        $services = $this->serviceMasterService->getPaginatedServiceMasters($filters, $perPage);

        return $this->successResponse(
            ServiceMasterResource::collection($services)->response()->getData(true),
            'Data layanan fisioterapi berhasil diambil'
        );
    }

    public function export(Request $request, \App\Services\CsvExportService $csvService)
    {
        $filters = $request->only(['search', 'category', 'is_active']);
        
        if (isset($filters['is_active'])) {
            $filters['is_active'] = filter_var($filters['is_active'], FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);
        }
        
        $exportData = $this->serviceMasterService->exportCsv($filters);
        $filename = 'services_' . date('Y-m-d') . '.csv';

        return $csvService->download($filename, $exportData['headers'], $exportData['data']);
    }

    public function store(StoreServiceMasterRequest $request): JsonResponse
    {
        if (!in_array($request->user()->role->value, ['admin', 'owner'])) {
            return $this->errorResponse('Unauthorized', 403);
        }

        try {
            $service = $this->serviceMasterService->createServiceMaster($request->validated());
            return $this->successResponse(new ServiceMasterResource($service), 'Layanan fisioterapi berhasil dibuat', 201);
        } catch (Exception $e) {
            return $this->errorResponse('Gagal membuat layanan: ' . $e->getMessage(), 500);
        }
    }

    public function show(string $id): JsonResponse
    {
        try {
            $service = $this->serviceMasterService->getServiceMasterById($id);
            return $this->successResponse(new ServiceMasterResource($service), 'Data layanan fisioterapi berhasil diambil');
        } catch (Exception $e) {
            return $this->errorResponse('Layanan tidak ditemukan', 404);
        }
    }

    public function update(UpdateServiceMasterRequest $request, string $id): JsonResponse
    {
        if (!in_array($request->user()->role->value, ['admin', 'owner'])) {
            return $this->errorResponse('Unauthorized', 403);
        }

        try {
            $service = $this->serviceMasterService->updateServiceMaster($id, $request->validated());
            return $this->successResponse(new ServiceMasterResource($service), 'Layanan fisioterapi berhasil diupdate');
        } catch (Exception $e) {
            return $this->errorResponse('Gagal update layanan: ' . $e->getMessage(), 500);
        }
    }

    public function destroy(string $id): JsonResponse
    {
        if (!in_array(request()->user()->role->value, ['admin', 'owner'])) {
            return $this->errorResponse('Unauthorized', 403);
        }

        try {
            $this->serviceMasterService->deleteServiceMaster($id);
            return $this->successResponse(null, 'Layanan fisioterapi berhasil dihapus');
        } catch (Exception $e) {
            return $this->errorResponse('Gagal menghapus layanan: ' . $e->getMessage(), 500);
        }
    }
}
