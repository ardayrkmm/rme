<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Interfaces\ActivityLogServiceInterface;
use App\Http\Resources\ActivityLogResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Enums\RoleEnum;
use Exception;

class ActivityLogController extends Controller
{
    protected $activityLogService;

    public function __construct(ActivityLogServiceInterface $activityLogService)
    {
        $this->activityLogService = $activityLogService;
    }

    public function index(Request $request): JsonResponse
    {
        // Hanya ADMIN dan OWNER yang bisa melihat log aktivitas
        if (!in_array($request->user()->role, [RoleEnum::ADMIN, RoleEnum::OWNER])) {
            return $this->errorResponse('Unauthorized. Hanya Admin dan Owner yang bisa mengakses menu ini.', 403);
        }

        $filters = $request->only(['search', 'action', 'start_date', 'end_date']);
        $perPage = min(10000, (int) $request->input('per_page', 15));

        $logs = $this->activityLogService->getPaginatedLogs($filters, $perPage);

        return $this->successResponse(
            ActivityLogResource::collection($logs)->response()->getData(true),
            'Data log aktivitas berhasil diambil'
        );
    }

    public function show(Request $request, string $id): JsonResponse
    {
        if (!in_array($request->user()->role, [RoleEnum::ADMIN, RoleEnum::OWNER])) {
            return $this->errorResponse('Unauthorized. Hanya Admin dan Owner yang bisa mengakses menu ini.', 403);
        }

        try {
            $log = $this->activityLogService->getLogById($id);
            $log->load('user');
            return $this->successResponse(new ActivityLogResource($log), 'Detail log berhasil diambil');
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), 404);
        }
    }

    public function destroy(Request $request, string $id): JsonResponse
    {
        // Hanya ADMIN dan OWNER yang bisa menghapus log
        if (!in_array($request->user()->role, [RoleEnum::ADMIN, RoleEnum::OWNER])) {
            return $this->errorResponse('Unauthorized. Hanya Admin dan Owner yang berhak menghapus log.', 403);
        }

        try {
            $this->activityLogService->deleteLog($id);
            return $this->successResponse(null, 'Log aktivitas berhasil dihapus secara permanen');
        } catch (Exception $e) {
            return $this->errorResponse('Gagal menghapus log', 500);
        }
    }
}
