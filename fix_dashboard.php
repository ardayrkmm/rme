<?php

$file = 'e:/Bisnis/RekamMedis/backend_rekammedis/app/Services/DashboardService.php';
$content = file_get_contents($file);

// Fix 1: getAppointmentChartData
$oldAppointmentChartData = <<<PHP
    public function getAppointmentChartData(string \$filter = 'year'): array
    {
        \$range = \$this->getDateRange(\$filter);
        return \$this->buildChartData(Appointment::query(), 'appointment_date', \$filter, \$range, 'COUNT(*)');
    }
PHP;

$newAppointmentChartData = <<<PHP
    public function getAppointmentChartData(string \$filter = 'year'): array
    {
        // Go backend groups by status, returning name and value for Pie Chart
        \$appointments = Appointment::select('status', DB::raw('COUNT(*) as count'))
            ->groupBy('status')
            ->get();

        \$statusNames = [
            'scheduled'   => 'Dijadwalkan',
            'telah_tiba'  => 'Telah Tiba',
            'ongoing'     => 'Sedang Terapi',
            'completed'   => 'Selesai',
            'cancelled'   => 'Dibatalkan',
        ];

        \$data = [];
        foreach (\$appointments as \$appt) {
            \$key = \$appt->status;
            \$data[] = [
                'name'  => \$statusNames[\$key] ?? ucfirst(\$key),
                'value' => (int) \$appt->count,
            ];
        }

        return \$data;
    }
PHP;

$content = str_replace($oldAppointmentChartData, $newAppointmentChartData, $content);

// Fix 2: getFisioterapisStats
$oldFisioReturn = <<<PHP
        return [
            'physiotherapist_name' => \$physiotherapist ? \$physiotherapist->name : \$user->name,
            'today_appointments' => \$todayAppointments,
            'today_patients' => \$todayPatients,
            'today_therapy_sessions' => \$todayTherapySessions,
            'next_appointment' => \$nextAppointment ? [
                'time' => Carbon::parse(\$nextAppointment->appointment_date->format('Y-m-d') . ' ' . \$nextAppointment->appointment_time)->format('M d, Y h:i A'),
                'patient_name' => \$nextAppointment->patient ? \$nextAppointment->patient->name : 'Unknown'
            ] : null,
            'recent_activities' => \$recentActivities,
        ];
PHP;

$newFisioReturn = <<<PHP
        // Prepare charts for Fisioterapis
        \$patientsByMonth = [];
        \$appointmentsByStatus = [];

        if (\$physioId) {
            // Patients by month (using TherapySession or Appointment to determine treated patients)
            // Go logic groups by month for that physio. We can just reuse buildChartData on Appointment
            // but for simplicity, we'll mimic a basic monthly format or use the same buildChartData logic
            \$range = \$this->getDateRange('year');
            \$patientsByMonth = \$this->buildChartData(
                Appointment::query()->where('physiotherapist_id', \$physioId), 
                'appointment_date', 
                'year', 
                \$range, 
                'COUNT(DISTINCT patient_id)'
            );

            // Appointments by status for Pie Chart
            \$appointments = Appointment::where('physiotherapist_id', \$physioId)
                ->select('status', DB::raw('COUNT(*) as count'))
                ->groupBy('status')
                ->get();
            
            \$statusNames = [
                'scheduled'   => 'Dijadwalkan',
                'telah_tiba'  => 'Telah Tiba',
                'ongoing'     => 'Sedang Terapi',
                'completed'   => 'Selesai',
                'cancelled'   => 'Dibatalkan',
            ];
            foreach (\$appointments as \$appt) {
                \$key = \$appt->status;
                \$appointmentsByStatus[] = [
                    'name'  => \$statusNames[\$key] ?? ucfirst(\$key),
                    'value' => (int) \$appt->count,
                ];
            }
        }

        return [
            'summary' => [
                'today_appointments' => \$todayAppointments,
                'today_patients' => \$todayPatients,
                'today_therapy_sessions' => \$todayTherapySessions,
            ],
            'charts' => [
                'patients' => \$patientsByMonth,
                'appointments' => \$appointmentsByStatus,
            ],
            'next_appointment' => \$nextAppointment ? [
                'time' => Carbon::parse(\$nextAppointment->appointment_date->format('Y-m-d') . ' ' . \$nextAppointment->appointment_time)->format('M d, Y h:i A'),
                'patient_name' => \$nextAppointment->patient ? \$nextAppointment->patient->name : 'Unknown'
            ] : [
                'time' => '-',
                'patient_name' => '-'
            ],
            'recent_activities' => \$recentActivities,
        ];
PHP;

$content = str_replace($oldFisioReturn, $newFisioReturn, $content);

file_put_contents($file, $content);
echo "DashboardService updated\n";
