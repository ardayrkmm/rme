<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

use App\Http\Controllers\Api\V1\AuthController;

Route::prefix('v1')->group(function () {
    
    // Auth Routes - Public
    Route::prefix('auth')->group(function () {
        Route::post('register', [AuthController::class, 'register']);
        Route::post('login', [AuthController::class, 'login']);
        Route::post('forgot-password', [AuthController::class, 'forgotPassword']);
        Route::post('reset-password', [AuthController::class, 'resetPassword']);
    });

    // Auth Routes - Protected
    Route::middleware('auth:sanctum')->group(function () {
        Route::prefix('auth')->group(function () {
            Route::get('profile', [AuthController::class, 'profile']);
            Route::post('change-password', [AuthController::class, 'changePassword']);
            Route::post('logout', [AuthController::class, 'logout']);
        });

        // Profil Sendiri
        Route::post('profile', [\App\Http\Controllers\Api\V1\UserController::class, 'updateProfile']);

        // Dashboard
        Route::prefix('dashboard')->group(function () {
            Route::get('/admin', [\App\Http\Controllers\Api\V1\DashboardController::class, 'admin']);
            Route::get('/fisio', [\App\Http\Controllers\Api\V1\DashboardController::class, 'fisio']);
        });

        // Notifications
        Route::prefix('notifications')->group(function () {
            Route::get('/', [\App\Http\Controllers\Api\V1\NotificationController::class, 'index']);
            Route::get('/unread', [\App\Http\Controllers\Api\V1\NotificationController::class, 'unread']);
            Route::post('/read-all', [\App\Http\Controllers\Api\V1\NotificationController::class, 'markAllAsRead']);
            Route::post('/{id}/read', [\App\Http\Controllers\Api\V1\NotificationController::class, 'markAsRead']);
            Route::post('/{id}/unread', [\App\Http\Controllers\Api\V1\NotificationController::class, 'markAsUnread']);
            Route::delete('/{id}', [\App\Http\Controllers\Api\V1\NotificationController::class, 'destroy']);
        });

        // Activity Logs
        Route::prefix('activity-logs')->group(function () {
            Route::get('/', [\App\Http\Controllers\Api\V1\ActivityLogController::class, 'index']);
            Route::get('/{id}', [\App\Http\Controllers\Api\V1\ActivityLogController::class, 'show']);
            Route::delete('/{id}', [\App\Http\Controllers\Api\V1\ActivityLogController::class, 'destroy']);
        });

        // User Management (Admin / Owner)
        Route::prefix('users')->group(function () {
            Route::get('/', [\App\Http\Controllers\Api\V1\UserController::class, 'index']);
            Route::post('/', [\App\Http\Controllers\Api\V1\UserController::class, 'store']);
            Route::get('/{id}', [\App\Http\Controllers\Api\V1\UserController::class, 'show']);
            Route::put('/{id}', [\App\Http\Controllers\Api\V1\UserController::class, 'update']);
            Route::delete('/{id}', [\App\Http\Controllers\Api\V1\UserController::class, 'destroy']);
            Route::post('/{id}/restore', [\App\Http\Controllers\Api\V1\UserController::class, 'restore']);
        });


        // Fisioterapi
        Route::prefix('physiotherapists')->group(function () {
            Route::get('/export/csv', [\App\Http\Controllers\Api\V1\PhysiotherapistController::class, 'export']);
            Route::get('/', [\App\Http\Controllers\Api\V1\PhysiotherapistController::class, 'index']);
            Route::post('/', [\App\Http\Controllers\Api\V1\PhysiotherapistController::class, 'store']);
            Route::get('/{id}', [\App\Http\Controllers\Api\V1\PhysiotherapistController::class, 'show']);
            Route::put('/{id}', [\App\Http\Controllers\Api\V1\PhysiotherapistController::class, 'update']);
            Route::delete('/{id}', [\App\Http\Controllers\Api\V1\PhysiotherapistController::class, 'destroy']);
            Route::post('/{id}/restore', [\App\Http\Controllers\Api\V1\PhysiotherapistController::class, 'restore']);
        });

        // Pasien
        Route::prefix('patients')->group(function () {
            Route::get('/export/csv', [\App\Http\Controllers\Api\V1\PatientController::class, 'export']);
            Route::get('/', [\App\Http\Controllers\Api\V1\PatientController::class, 'index']);
            Route::post('/', [\App\Http\Controllers\Api\V1\PatientController::class, 'store']);
            Route::get('/{id}', [\App\Http\Controllers\Api\V1\PatientController::class, 'show']);
            Route::put('/{id}', [\App\Http\Controllers\Api\V1\PatientController::class, 'update']);
            Route::delete('/{id}', [\App\Http\Controllers\Api\V1\PatientController::class, 'destroy']);
            Route::post('/{id}/restore', [\App\Http\Controllers\Api\V1\PatientController::class, 'restore']);
            
            // Histori Rekam Medis Pasien
            Route::get('/{patientId}/medical-records', [\App\Http\Controllers\Api\V1\MedicalRecordController::class, 'history']);
        });

        // Patient Categories & Genders
        Route::prefix('patient-categories')->group(function () {
            Route::get('/', [\App\Http\Controllers\Api\V1\PatientCategoryController::class, 'index']);
            Route::post('/', [\App\Http\Controllers\Api\V1\PatientCategoryController::class, 'store']);
            Route::put('/{id}', [\App\Http\Controllers\Api\V1\PatientCategoryController::class, 'update']);
            Route::delete('/{id}', [\App\Http\Controllers\Api\V1\PatientCategoryController::class, 'destroy']);
        });
        Route::get('/genders', [\App\Http\Controllers\Api\V1\GenderController::class, 'index']);

        // Service Categories
        Route::prefix('service-categories')->group(function () {
            Route::get('/', [\App\Http\Controllers\Api\V1\ServiceCategoryController::class, 'index']);
            Route::post('/', [\App\Http\Controllers\Api\V1\ServiceCategoryController::class, 'store']);
            Route::get('/{id}', [\App\Http\Controllers\Api\V1\ServiceCategoryController::class, 'show']);
            Route::put('/{id}', [\App\Http\Controllers\Api\V1\ServiceCategoryController::class, 'update']);
            Route::delete('/{id}', [\App\Http\Controllers\Api\V1\ServiceCategoryController::class, 'destroy']);
        });

        // Appointment
        Route::prefix('appointments')->group(function () {
            Route::get('/', [\App\Http\Controllers\Api\V1\AppointmentController::class, 'index']);
            Route::get('/history', [\App\Http\Controllers\Api\V1\AppointmentController::class, 'history']);
            Route::post('/', [\App\Http\Controllers\Api\V1\AppointmentController::class, 'store']);
            Route::get('/{id}', [\App\Http\Controllers\Api\V1\AppointmentController::class, 'show']);
            Route::put('/{id}', [\App\Http\Controllers\Api\V1\AppointmentController::class, 'update']);
            Route::post('/{id}/reschedule', [\App\Http\Controllers\Api\V1\AppointmentController::class, 'reschedule']);
            Route::post('/{id}/cancel', [\App\Http\Controllers\Api\V1\AppointmentController::class, 'cancel']);
            Route::delete('/{id}', [\App\Http\Controllers\Api\V1\AppointmentController::class, 'destroy']);
        });

        // Therapy Sessions
        Route::prefix('therapy-sessions')->group(function () {
            Route::get('/schedule/weekly', [\App\Http\Controllers\Api\V1\TherapySessionController::class, 'getWeeklySchedule']);
            Route::get('/schedule', [\App\Http\Controllers\Api\V1\TherapySessionController::class, 'getSchedule']);
            Route::get('/', [\App\Http\Controllers\Api\V1\TherapySessionController::class, 'index']);
            Route::post('/', [\App\Http\Controllers\Api\V1\TherapySessionController::class, 'store']);
            Route::get('/{id}', [\App\Http\Controllers\Api\V1\TherapySessionController::class, 'show']);
            Route::put('/{id}', [\App\Http\Controllers\Api\V1\TherapySessionController::class, 'update']);
            Route::delete('/{id}', [\App\Http\Controllers\Api\V1\TherapySessionController::class, 'destroy']);
        });

        // Reports
        Route::prefix('reports')->group(function () {
            Route::get('/dashboard', [\App\Http\Controllers\Api\V1\ReportController::class, 'dashboard']);
        });

        // Rekam Medis
        Route::prefix('medical-records')->group(function () {
            Route::get('/', [\App\Http\Controllers\Api\V1\MedicalRecordController::class, 'index']);
            Route::post('/', [\App\Http\Controllers\Api\V1\MedicalRecordController::class, 'store']);
            Route::get('/{id}', [\App\Http\Controllers\Api\V1\MedicalRecordController::class, 'show']);
            Route::put('/{id}', [\App\Http\Controllers\Api\V1\MedicalRecordController::class, 'update']);
            Route::delete('/{id}', [\App\Http\Controllers\Api\V1\MedicalRecordController::class, 'destroy']);
            Route::get('/{id}/export-pdf', [\App\Http\Controllers\Api\V1\MedicalRecordController::class, 'exportPdf']);
        });

        // Service Masters / Layanan Fisioterapi
        Route::prefix('service-masters')->group(function () {
            Route::get('/export/csv', [\App\Http\Controllers\Api\V1\ServiceMasterController::class, 'export']);
            Route::get('/', [\App\Http\Controllers\Api\V1\ServiceMasterController::class, 'index']);
            Route::post('/', [\App\Http\Controllers\Api\V1\ServiceMasterController::class, 'store']);
            Route::get('/{id}', [\App\Http\Controllers\Api\V1\ServiceMasterController::class, 'show']);
            Route::put('/{id}', [\App\Http\Controllers\Api\V1\ServiceMasterController::class, 'update']);
            Route::delete('/{id}', [\App\Http\Controllers\Api\V1\ServiceMasterController::class, 'destroy']);
        });

        // Payments / Pembayaran
        Route::prefix('payments')->group(function () {
            Route::get('/export/csv', [\App\Http\Controllers\Api\V1\PaymentController::class, 'exportCsv']);
            Route::get('/export/pdf', [\App\Http\Controllers\Api\V1\PaymentController::class, 'exportListPdf']);
            Route::get('/{id}/pdf/preview', [\App\Http\Controllers\Api\V1\PaymentController::class, 'previewPdf']);
            Route::get('/{id}/pdf/download', [\App\Http\Controllers\Api\V1\PaymentController::class, 'downloadPdf']);
            Route::get('/{id}/pdf/receipt/preview', [\App\Http\Controllers\Api\V1\PaymentController::class, 'previewReceipt']);
            Route::get('/{id}/pdf/receipt/download', [\App\Http\Controllers\Api\V1\PaymentController::class, 'downloadReceipt']);
            Route::get('/{id}/share-link', [\App\Http\Controllers\Api\V1\PaymentController::class, 'shareLink']);
            Route::get('/', [\App\Http\Controllers\Api\V1\PaymentController::class, 'index']);
            Route::post('/', [\App\Http\Controllers\Api\V1\PaymentController::class, 'store']);
            Route::get('/{id}', [\App\Http\Controllers\Api\V1\PaymentController::class, 'show']);
            Route::put('/{id}', [\App\Http\Controllers\Api\V1\PaymentController::class, 'update']);
            Route::delete('/{id}', [\App\Http\Controllers\Api\V1\PaymentController::class, 'destroy']);
        });

        // Tambahkan routes v1 lainnya yang membutuhkan autentikasi di sini
    });

    // Public Signed Route for sharing Invoice
    Route::get('/public/payments/{id}/invoice', [\App\Http\Controllers\Api\V1\PaymentController::class, 'publicDownloadPdf'])
        ->name('payment.invoice.download')
        ->middleware('signed');

});
