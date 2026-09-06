<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Physiotherapist\StorePhysiotherapistRequest;
use App\Http\Requests\Physiotherapist\UpdatePhysiotherapistRequest;
use App\Http\Resources\PhysiotherapistResource;
use App\Interfaces\PhysiotherapistServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Exception;

class PhysiotherapistController extends Controller
{
    protected $physiotherapistService;

    public function __construct(PhysiotherapistServiceInterface $physiotherapistService)
    {
        $this->physiotherapistService = $physiotherapistService;
    }

    public function index(Request $request): JsonResponse
    {
        $this->authorize('viewAny', \App\Models\Physiotherapist::class);

        $filters = $request->only(['search', 'status']);
        $perPage = min(100, (int) $request->input('per_page', 15));

        $physiotherapists = $this->physiotherapistService->getPaginatedPhysiotherapists($filters, $perPage);

        return $this->successResponse(
            PhysiotherapistResource::collection($physiotherapists)->response()->getData(true),
            'Data fisioterapis berhasil diambil'
        );
    }

    public function export(Request $request, \App\Services\CsvExportService $csvService)
    {
        $this->authorize('viewAny', \App\Models\Physiotherapist::class);
        $filters = $request->only(['search', 'status']);
        
        $exportData = $this->physiotherapistService->exportCsv($filters);
        $filename = 'physiotherapists_' . date('Y-m-d') . '.csv';

        return $csvService->download($filename, $exportData['headers'], $exportData['data']);
    }

    public function store(StorePhysiotherapistRequest $request): JsonResponse
    {
        try {
            $physiotherapist = $this->physiotherapistService->createPhysiotherapist($request->validated());
            return $this->successResponse(new PhysiotherapistResource($physiotherapist), 'Fisioterapis berhasil dibuat', 201);
        } catch (Exception $e) {
            return $this->errorResponse('Gagal membuat fisioterapis: ' . $e->getMessage(), 500);
        }
    }

    public function show(string $id): JsonResponse
    {
        $this->authorize('view', \App\Models\Physiotherapist::class);

        try {
            $physiotherapist = $this->physiotherapistService->getPhysiotherapistById($id);
            return $this->successResponse(new PhysiotherapistResource($physiotherapist), 'Data fisioterapis berhasil diambil');
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), 404);
        }
    }

    public function update(UpdatePhysiotherapistRequest $request, string $id): JsonResponse
    {
        try {
            $physiotherapist = $this->physiotherapistService->updatePhysiotherapist($id, $request->validated());
            return $this->successResponse(new PhysiotherapistResource($physiotherapist), 'Fisioterapis berhasil diupdate');
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), $e->getCode() ?: 500);
        }
    }

    public function destroy(string $id): JsonResponse
    {
        $this->authorize('manage', \App\Models\Physiotherapist::class);

        try {
            $this->physiotherapistService->deletePhysiotherapist($id);
            return $this->successResponse(null, 'Fisioterapis berhasil dihapus');
        } catch (Exception $e) {
            return $this->errorResponse('Gagal menghapus fisioterapis', 500);
        }
    }

    public function restore(string $id): JsonResponse
    {
        $this->authorize('manage', \App\Models\Physiotherapist::class);

        try {
            $this->physiotherapistService->restorePhysiotherapist($id);
            return $this->successResponse(null, 'Fisioterapis berhasil direstore');
        } catch (Exception $e) {
            return $this->errorResponse('Gagal merestore fisioterapis', 500);
        }
    }
}
