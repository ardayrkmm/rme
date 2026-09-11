<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Interfaces\NotificationServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Exception;

class NotificationController extends Controller
{
    protected $notificationService;

    public function __construct(NotificationServiceInterface $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    public function index(Request $request): JsonResponse
    {
        $perPage = min(10000, (int) $request->input('per_page', 15));
        $notifications = $this->notificationService->getUserNotifications($request->user()->id, false, $perPage);

        return $this->successResponse($notifications, 'Semua notifikasi berhasil diambil');
    }

    public function unread(Request $request): JsonResponse
    {
        $perPage = min(10000, (int) $request->input('per_page', 15));
        $notifications = $this->notificationService->getUserNotifications($request->user()->id, true, $perPage);

        return $this->successResponse($notifications, 'Notifikasi belum dibaca berhasil diambil');
    }

    public function markAsRead(Request $request, string $id): JsonResponse
    {
        try {
            $notification = $this->notificationService->markAsRead($request->user()->id, $id);
            return $this->successResponse($notification, 'Notifikasi ditandai sudah dibaca');
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), 404);
        }
    }

    public function markAsUnread(Request $request, string $id): JsonResponse
    {
        try {
            $notification = $this->notificationService->markAsUnread($request->user()->id, $id);
            return $this->successResponse($notification, 'Notifikasi ditandai belum dibaca');
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), 404);
        }
    }

    public function markAllAsRead(Request $request): JsonResponse
    {
        $this->notificationService->markAllAsRead($request->user()->id);
        return $this->successResponse(null, 'Semua notifikasi ditandai sudah dibaca');
    }

    public function destroy(Request $request, string $id): JsonResponse
    {
        try {
            $this->notificationService->deleteNotification($request->user()->id, $id);
            return $this->successResponse(null, 'Notifikasi berhasil dihapus');
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), 404);
        }
    }
}
