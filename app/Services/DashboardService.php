<?php

namespace App\Services;

use App\Interfaces\DashboardServiceInterface;
use App\Models\Patient;
use App\Models\Physiotherapist;
use App\Models\Appointment;
use App\Models\MedicalRecord;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardService implements DashboardServiceInterface
{
    private function getDateRange(string $filter): array
    {
        $now = Carbon::now();
        switch ($filter) {
            case 'today':
                return [$now->copy()->startOfDay(), $now->copy()->endOfDay()];
            case 'week':
                return [$now->copy()->startOfWeek(), $now->copy()->endOfWeek()];
            case 'month':
                return [$now->copy()->startOfMonth(), $now->copy()->endOfMonth()];
            case 'year':
            default:
                return [$now->copy()->startOfYear(), $now->copy()->endOfYear()];
        }
    }

    public function getSummaryStats(string $filter = 'year'): array
    {
        $today = Carbon::today();
        $thisMonth = Carbon::now()->startOfMonth();

        return [
            'total_pasien' => Patient::count(),
            'total_fisioterapi' => Physiotherapist::count(),
            'total_appointment' => Appointment::count(),
            'appointment_hari_ini' => Appointment::whereDate('appointment_date', $today)->count(),
            'pasien_baru_bulan_ini' => Patient::where('created_at', '>=', $thisMonth)->count(),
            // Ensure App\Models\Payment is imported at the top of the file
            'pendapatan_hari_ini' => \App\Models\Payment::where('status', 'paid')->whereDate('payment_date', $today)->sum('total'),
            'pendapatan_bulan_ini' => \App\Models\Payment::where('status', 'paid')->where('payment_date', '>=', $thisMonth)->sum('total'),
        ];
    }

    public function getPatientChartData(string $filter = 'year'): array
    {
        $range = $this->getDateRange($filter);
        return $this->buildChartData(Patient::query(), 'created_at', $filter, $range, 'COUNT(*)');
    }

    public function getAppointmentChartData(string $filter = 'year'): array
    {
        // Go backend groups by status, returning name and value for Pie Chart
        $appointments = Appointment::select('status', DB::raw('COUNT(*) as count'))
            ->groupBy('status')
            ->get();

        $statusNames = [
            'scheduled'   => 'Dijadwalkan',
            'telah_tiba'  => 'Telah Tiba',
            'ongoing'     => 'Sedang Terapi',
            'completed'   => 'Selesai',
            'cancelled'   => 'Dibatalkan',
        ];

        $data = [];
        foreach ($appointments as $appt) {
            $key = $appt->status;
            $data[] = [
                'name'  => $statusNames[$key] ?? ucfirst($key),
                'value' => (int) $appt->count,
            ];
        }

        return $data;
    }

    public function getRevenueChartData(string $filter = 'year'): array
    {
        $range = $this->getDateRange($filter);
        return $this->buildChartData(\App\Models\Payment::where('status', 'paid'), 'payment_date', $filter, $range, 'SUM(total)');
    }

    public function getDiseaseChartData(string $filter = 'year'): array
    {
        $range = $this->getDateRange($filter);
        $diseases = MedicalRecord::select('diagnosis', DB::raw('COUNT(*) as count'))
            ->whereNotNull('diagnosis')
            ->whereBetween('created_at', $range)
            ->groupBy('diagnosis')
            ->orderByDesc('count')
            ->limit(10)
            ->get();

        $data = [];
        foreach ($diseases as $disease) {
            $diagnosisName = strlen($disease->diagnosis) > 30 ? substr($disease->diagnosis, 0, 30) . '...' : $disease->diagnosis;
            $data[] = [
                'diagnosis' => $diagnosisName,
                'count' => $disease->count
            ];
        }

        return $data;
    }

    private function buildChartData($query, string $dateColumn, string $filter, array $range, string $aggregateExpr): array
    {
        $query->whereBetween($dateColumn, $range);

        if ($filter === 'today') {
            $data = $query->select(DB::raw("HOUR($dateColumn) as label"), DB::raw("$aggregateExpr as total"))
                ->groupBy('label')->orderBy('label')->get();
            return $this->formatHourChartData($data);
        } elseif ($filter === 'week') {
            $data = $query->select(DB::raw("DAYNAME($dateColumn) as label_name"), DB::raw("DAYOFWEEK($dateColumn) as day_idx"), DB::raw("$aggregateExpr as total"))
                ->groupBy('label_name', 'day_idx')->orderBy('day_idx')->get();
            return $this->formatWeekChartData($data);
        } elseif ($filter === 'month') {
            $daysInMonth = Carbon::now()->daysInMonth;
            $data = $query->select(DB::raw("DAY($dateColumn) as label"), DB::raw("$aggregateExpr as total"))
                ->groupBy('label')->orderBy('label')->get();
            return $this->formatDayChartData($data, $daysInMonth);
        } else {
            $data = $query->select(DB::raw("MONTH($dateColumn) as label"), DB::raw("$aggregateExpr as total"))
                ->groupBy('label')->orderBy('label')->get();
            return $this->formatMonthlyChartData($data);
        }
    }

    private function formatHourChartData($collection): array
    {
        $data = [];
        for ($i = 0; $i < 24; $i++) {
            $data[$i] = ['label' => str_pad($i, 2, '0', STR_PAD_LEFT) . ':00', 'total' => 0];
        }
        foreach ($collection as $item) {
            $data[(int)$item->label]['total'] = (float)$item->total;
        }
        return array_values($data);
    }

    private function formatWeekChartData($collection): array
    {
        $days = [1 => 'Sun', 2 => 'Mon', 3 => 'Tue', 4 => 'Wed', 5 => 'Thu', 6 => 'Fri', 7 => 'Sat'];
        $data = [];
        foreach ($days as $idx => $name) {
            $data[$idx] = ['label' => $name, 'total' => 0];
        }
        foreach ($collection as $item) {
            if (isset($data[$item->day_idx])) {
                $data[$item->day_idx]['total'] = (float)$item->total;
            }
        }
        return array_values($data);
    }

    private function formatDayChartData($collection, int $daysInMonth): array
    {
        $data = [];
        for ($i = 1; $i <= $daysInMonth; $i++) {
            $data[$i] = ['label' => (string)$i, 'total' => 0];
        }
        foreach ($collection as $item) {
            if (isset($data[$item->label])) {
                $data[$item->label]['total'] = (float)$item->total;
            }
        }
        return array_values($data);
    }

    private function formatMonthlyChartData($collection): array
    {
        $months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
        $data = [];
        foreach ($months as $index => $monthName) {
            $data[$index + 1] = ['label' => $monthName, 'total' => 0];
        }
        foreach ($collection as $item) {
            if (isset($data[$item->label])) {
                $data[$item->label]['total'] = (float)$item->total;
            }
        }
        return array_values($data);
    }

    public function getFisioterapisStats($user): array
    {
        $today = Carbon::today();
        $physiotherapist = Physiotherapist::where('email', $user->email)->first();
        $physioId = $physiotherapist ? $physiotherapist->id : null;

        $todayAppointments = 0;
        $todayPatients = 0;
        $todayTherapySessions = 0;
        $nextAppointment = null;
        $recentActivities = [];

        if ($physioId) {
            $todayAppointments = Appointment::where('physiotherapist_id', $physioId)
                ->whereDate('appointment_date', $today)
                ->count();

            $todayPatients = Appointment::where('physiotherapist_id', $physioId)
                ->whereDate('appointment_date', $today)
                ->distinct('patient_id')
                ->count('patient_id');

            $todayTherapySessions = \App\Models\TherapySession::where('physiotherapist_id', $physioId)
                ->whereDate('therapy_date', $today)
                ->count();

            $nextAppointment = Appointment::with('patient:id,name')
                ->where('physiotherapist_id', $physioId)
                ->whereDate('appointment_date', '>=', $today)
                ->where('status', 'scheduled')
                ->orderBy('appointment_date', 'asc')
                ->orderBy('appointment_time', 'asc')
                ->first();

            // Temporarily disabled due to DB schema mismatch
            $recentActivitiesRaw = [];
            
            foreach ($recentActivitiesRaw as $log) {
                $recentActivities[] = [
                    'id' => $log->id,
                    'action' => $log->action,
                    'description' => $log->description,
                    'time' => $log->created_at->diffForHumans()
                ];
            }
        }

        // Prepare charts for Fisioterapis
        $patientsByMonth = [];
        $appointmentsByStatus = [];

        if ($physioId) {
            // Patients by month (using TherapySession or Appointment to determine treated patients)
            // Go logic groups by month for that physio. We can just reuse buildChartData on Appointment
            // but for simplicity, we'll mimic a basic monthly format or use the same buildChartData logic
            $range = $this->getDateRange('year');
            $patientsByMonth = $this->buildChartData(
                Appointment::query()->where('physiotherapist_id', $physioId), 
                'appointment_date', 
                'year', 
                $range, 
                'COUNT(DISTINCT patient_id)'
            );

            // Appointments by status for Pie Chart
            $appointments = Appointment::where('physiotherapist_id', $physioId)
                ->select('status', DB::raw('COUNT(*) as count'))
                ->groupBy('status')
                ->get();
            
            $statusNames = [
                'scheduled'   => 'Dijadwalkan',
                'telah_tiba'  => 'Telah Tiba',
                'ongoing'     => 'Sedang Terapi',
                'completed'   => 'Selesai',
                'cancelled'   => 'Dibatalkan',
            ];
            foreach ($appointments as $appt) {
                $key = $appt->status;
                $appointmentsByStatus[] = [
                    'name'  => $statusNames[$key] ?? ucfirst($key),
                    'value' => (int) $appt->count,
                ];
            }
        }

        return [
            'summary' => [
                'today_appointments' => $todayAppointments,
                'today_patients' => $todayPatients,
                'today_therapy_sessions' => $todayTherapySessions,
            ],
            'charts' => [
                'patients' => $patientsByMonth,
                'appointments' => $appointmentsByStatus,
            ],
            'next_appointment' => $nextAppointment ? [
                'time' => Carbon::parse($nextAppointment->appointment_date->format('Y-m-d') . ' ' . $nextAppointment->appointment_time)->format('M d, Y h:i A'),
                'patient_name' => $nextAppointment->patient ? $nextAppointment->patient->name : 'Unknown'
            ] : [
                'time' => '-',
                'patient_name' => '-'
            ],
            'recent_activities' => $recentActivities,
        ];
    }
}
