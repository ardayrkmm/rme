<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Patient;
use App\Models\Physiotherapist;
use App\Models\TherapySession;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function dashboard(): JsonResponse
    {
        $data = \Illuminate\Support\Facades\Cache::remember('dashboard_report_data', 60 * 5, function () {
            $totalPatients = Patient::count();
            $totalPhysiotherapists = Physiotherapist::count();
            $totalAppointments = Appointment::count();
            $totalSessions = TherapySession::count();

            // Appointments by status
            $appointmentsByStatus = Appointment::select('status', DB::raw('count(*) as count'))
                ->groupBy('status')
                ->get();

            // Therapy sessions by month (last 6 months)
            $sessionsByMonth = TherapySession::select(
                DB::raw('DATE_FORMAT(therapy_date, "%Y-%m") as month'),
                DB::raw('count(*) as count')
            )
            ->groupBy('month')
            ->orderBy('month', 'desc')
            ->limit(6)
            ->get();

            return [
                'summary' => [
                    'total_patients' => $totalPatients,
                    'total_physiotherapists' => $totalPhysiotherapists,
                    'total_appointments' => $totalAppointments,
                    'total_sessions' => $totalSessions,
                ],
                'appointments_by_status' => $appointmentsByStatus,
                'sessions_by_month' => $sessionsByMonth,
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }
}
