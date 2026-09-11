<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Appointment\StoreAppointmentRequest;
use App\Http\Requests\Appointment\UpdateAppointmentRequest;
use App\Http\Requests\Appointment\RescheduleAppointmentRequest;
use App\Http\Requests\Appointment\CancelAppointmentRequest;
use App\Http\Resources\AppointmentResource;
use App\Interfaces\AppointmentServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Exception;

class AppointmentController extends Controller
{
    protected $appointmentService;

    public function __construct(AppointmentServiceInterface $appointmentService)
    {
        $this->appointmentService = $appointmentService;
    }

    public function index(Request $request): JsonResponse
    {
        $this->authorize('viewAny', \App\Models\Appointment::class);

        $filters = $request->only(['search', 'status', 'start_date', 'end_date', 'patient_id', 'physiotherapist_id']);
        $perPage = min(10000, (int) $request->input('per_page', 15));

        $appointments = $this->appointmentService->getPaginatedAppointments($filters, $perPage);

        return $this->successResponse(
            AppointmentResource::collection($appointments)->response()->getData(true),
            'Data temu janji berhasil diambil'
        );
    }

    public function history(Request $request): JsonResponse
    {
        $this->authorize('viewAny', \App\Models\Appointment::class);

        $filters = $request->only(['search', 'start_date', 'end_date']);
        $perPage = min(10000, (int) $request->input('per_page', 15));

        $appointments = $this->appointmentService->getHistoryPaginated($filters, $perPage);

        return $this->successResponse(
            AppointmentResource::collection($appointments)->response()->getData(true),
            'Riwayat temu janji berhasil diambil'
        );
    }

    public function store(StoreAppointmentRequest $request): JsonResponse
    {
        try {
            $appointment = $this->appointmentService->createAppointment($request->validated());
            $appointment->load(['patient', 'physiotherapist']);
            return $this->successResponse(new AppointmentResource($appointment), 'Janji Terapi berhasil dibuat', 201);
        } catch (Exception $e) {
            return $this->errorResponse('Gagal membuat temu janji: ' . $e->getMessage(), $e->getCode() ?: 500);
        }
    }

    public function show(Request $request, string $id): JsonResponse
    {
        $this->authorize('view', \App\Models\Appointment::class);

        try {
            $appointment = $this->appointmentService->getAppointmentById($id);
            
            $user = $request->user();
            if ($user->role === \App\Enums\RoleEnum::FISIOTERAPIS) {
                $physio = \App\Models\Physiotherapist::where('email', $user->email)->first();
                if (!$physio || $appointment->physiotherapist_id != $physio->id) {
                    return $this->errorResponse('Akses ditolak', 403);
                }
            }
            
            return $this->successResponse(new AppointmentResource($appointment), 'Data temu janji berhasil diambil');
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), 404);
        }
    }

    public function update(UpdateAppointmentRequest $request, string $id): JsonResponse
    {
        $user = $request->user();
        if ($user->role === \App\Enums\RoleEnum::FISIOTERAPIS) {
            $physio = \App\Models\Physiotherapist::where('email', $user->email)->first();
            $existing = \App\Models\Appointment::find($id);
            if (!$existing) {
                return $this->errorResponse('Temu janji tidak ditemukan', 404);
            }
            if (!$physio || $existing->physiotherapist_id != $physio->id) {
                return $this->errorResponse('Akses ditolak: Fisioterapis hanya dapat mengubah sesinya sendiri', 403);
            }
        }

        try {
            $appointment = $this->appointmentService->updateAppointment($id, $request->validated());
            $appointment->load(['patient', 'physiotherapist']);
            return $this->successResponse(new AppointmentResource($appointment), 'Janji Terapi berhasil diupdate');
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), $e->getCode() ?: 500);
        }
    }

    public function reschedule(RescheduleAppointmentRequest $request, string $id): JsonResponse
    {
        try {
            $appointment = $this->appointmentService->rescheduleAppointment($id, $request->validated());
            $appointment->load(['patient', 'physiotherapist']);
            return $this->successResponse(new AppointmentResource($appointment), 'Janji Terapi berhasil dijadwalkan ulang');
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), $e->getCode() ?: 500);
        }
    }

    public function cancel(CancelAppointmentRequest $request, string $id): JsonResponse
    {
        try {
            $appointment = $this->appointmentService->cancelAppointment($id, $request->validated());
            $appointment->load(['patient', 'physiotherapist']);
            return $this->successResponse(new AppointmentResource($appointment), 'Janji Terapi berhasil dibatalkan');
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), $e->getCode() ?: 500);
        }
    }

    public function destroy(string $id): JsonResponse
    {
        $this->authorize('adminOnly', \App\Models\Appointment::class);

        try {
            $this->appointmentService->deleteAppointment($id);
            return $this->successResponse(null, 'Janji Terapi berhasil dihapus (soft delete)');
        } catch (Exception $e) {
            return $this->errorResponse('Gagal menghapus temu janji', 500);
        }
    }
}
