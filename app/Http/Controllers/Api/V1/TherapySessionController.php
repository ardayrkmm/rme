<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\TherapySession;
use App\Http\Requests\TherapySession\StoreTherapySessionRequest;
use App\Http\Requests\TherapySession\UpdateTherapySessionRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TherapySessionController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $perPage = min(100, (int) $request->input('per_page', 10));
        $search = $request->input('search');

        $query = TherapySession::with(['patient', 'physiotherapist', 'serviceMaster']);

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->whereHas('patient', function($p) use ($search) {
                    $p->where('name', 'like', "%{$search}%");
                })->orWhereHas('physiotherapist', function($p) use ($search) {
                    $p->where('name', 'like', "%{$search}%");
                });
            });
        }

        if ($request->has('patient_id')) {
            $query->where('patient_id', $request->input('patient_id'));
        }

        if ($request->has('physiotherapist_id')) {
            $query->where('physiotherapist_id', $request->input('physiotherapist_id'));
        }

        if ($request->has('start_date')) {
            $query->whereDate('therapy_date', '>=', $request->input('start_date'));
        }

        if ($request->has('end_date')) {
            $query->whereDate('therapy_date', '<=', $request->input('end_date'));
        }

        $sessions = $query->orderBy('therapy_date', 'desc')->paginate($perPage);

        return response()->json([
            'success' => true,
            'message' => 'Therapy sessions retrieved successfully',
            'data' => $sessions
        ]);
    }

    public function store(StoreTherapySessionRequest $request): JsonResponse
    {
        $session = TherapySession::create($request->validated());
        $session->load(['patient', 'physiotherapist', 'serviceMaster']);

        return response()->json([
            'success' => true,
            'message' => 'Therapy session created successfully',
            'data' => $session
        ], 201);
    }

    public function show($id): JsonResponse
    {
        $session = TherapySession::with(['patient', 'physiotherapist', 'appointment', 'serviceMaster'])->find($id);

        if (!$session) {
            return response()->json([
                'success' => false,
                'message' => 'Therapy session not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Therapy session retrieved successfully',
            'data' => $session
        ]);
    }

    public function update(UpdateTherapySessionRequest $request, $id): JsonResponse
    {
        $session = TherapySession::with('serviceMaster')->find($id);

        if (!$session) {
            return response()->json([
                'success' => false,
                'message' => 'Therapy session not found'
            ], 404);
        }

        $user = request()->user();
        if ($user && $user->role === \App\Enums\RoleEnum::FISIOTERAPIS) {
            $physio = \App\Models\Physiotherapist::where('email', $user->email)->first();
            if ($physio && $session->physiotherapist_id !== $physio->id) {
                return response()->json([
                    'success' => false,
                    'message' => 'Akses ditolak: Anda tidak bisa mengubah sesi orang lain'
                ], 403);
            }
        }

        $oldStatus = $session->status;
        $newStatus = $request->input('status', $oldStatus);

        if ($oldStatus === 'completed' && $newStatus === 'scheduled') {
            return response()->json([
                'success' => false,
                'message' => 'Sesi yang sudah diselesaikan tidak dapat diubah kembali menjadi Scheduled'
            ], 400);
        }

        if ($newStatus === 'completed' && $oldStatus !== 'completed') {
            // Check if Medical Record exists
            $hasRecord = \App\Models\MedicalRecord::where('patient_id', $session->patient_id)
                ->where('appointment_id', $session->appointment_id)
                ->exists();

            if (!$hasRecord) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tidak dapat menyelesaikan sesi: Data klinis (Rekam Medis) wajib diisi terlebih dahulu'
                ], 400);
            }
        }

        if ($newStatus !== $oldStatus) {
            $valid = false;
            $oldStatusLower = strtolower($oldStatus);
            $newStatusLower = strtolower($newStatus);
            
            switch ($oldStatusLower) {
                case 'scheduled':
                    if (in_array($newStatusLower, ['telah_tiba', 'patient arrived', 'cancelled', 'rescheduled'])) $valid = true;
                    break;
                case 'telah_tiba':
                case 'patient arrived':
                    if (in_array($newStatusLower, ['ongoing', 'in progress', 'cancelled'])) $valid = true;
                    break;
                case 'ongoing':
                case 'in progress':
                    if ($newStatusLower === 'completed') $valid = true;
                    break;
                case 'completed':
                case 'cancelled':
                    $valid = false;
                    break;
                default:
                    $valid = true; // allow unknown legacy statuses to be changed
            }

            if (!$valid && !in_array($oldStatusLower, ['completed', 'cancelled'])) {
                // If it's already completed or cancelled, we don't strictly prevent changes to other non-scheduled statuses here since the first rule handles completed -> scheduled. But to perfectly match Go: Go says if valid=false return error.
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid status transition from ' . $oldStatus . ' to ' . $newStatus
                ], 400);
            }
        }
        $session->update($request->validated());
        $session->load(['patient', 'physiotherapist', 'serviceMaster']);

        // Update related appointment status if session is completed or cancelled
        if ($session->appointment_id && $session->status !== $oldStatus) {
            if ($session->status === 'completed') {
                \App\Models\Appointment::where('id', $session->appointment_id)->update(['status' => 'completed']);
            } elseif ($session->status === 'cancelled') {
                \App\Models\Appointment::where('id', $session->appointment_id)->update(['status' => 'cancelled']);
            }
        }

        // Auto-generate Payment if status changed to completed
        if ($session->status === 'completed' && $oldStatus !== 'completed') {

            $services = collect();
            if (!empty($session->service_master_ids)) {
                $services = \App\Models\ServiceMaster::whereIn('id', $session->service_master_ids)->get();
            } elseif ($session->serviceMaster) {
                $services->push($session->serviceMaster);
            }

            if ($services->isNotEmpty()) {
                // Check if payment already exists
                $existingPayment = \App\Models\Payment::where('therapy_session_id', $session->id)->first();
                if (!$existingPayment) {
                    $totalPrice = $services->sum('base_price');
                    $invoiceNumber = 'INV-' . date('Ymd') . '-' . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT);
                    $payment = \App\Models\Payment::create([
                        'invoice_number' => $invoiceNumber,
                        'therapy_session_id' => $session->id,
                        'patient_id' => $session->patient_id,
                        'physiotherapist_id' => $session->physiotherapist_id,
                        'payment_date' => now()->toDateString(),
                        'payment_method' => 'Tunai',
                        'status' => 'Pending',
                        'subtotal' => $totalPrice,
                        'total' => $totalPrice,
                        'discount' => 0,
                        'tax' => 0,
                        'notes' => 'Tagihan otomatis dari Sesi Terapi selesai'
                    ]);

                    foreach ($services as $service) {
                        \App\Models\PaymentDetail::create([
                            'payment_id' => $payment->id,
                            'service_master_id' => $service->id,
                            'service_name' => $service->name,
                            'quantity' => 1,
                            'price' => $service->base_price,
                            'subtotal' => $service->base_price
                        ]);
                    }
                }
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Therapy session updated successfully',
            'data' => $session
        ]);
    }

    public function destroy($id): JsonResponse
    {
        $session = TherapySession::find($id);

        if (!$session) {
            return response()->json([
                'success' => false,
                'message' => 'Therapy session not found'
            ], 404);
        }

        $session->delete();

        return response()->json([
            'success' => true,
            'message' => 'Therapy session deleted successfully'
        ]);
    }
    public function getSchedule(Request $request): JsonResponse
    {
        $date = $request->input('date', now()->toDateString());
        
        $physiotherapists = \App\Models\Physiotherapist::all();
        
        $appointments = \App\Models\Appointment::with(['patient', 'physiotherapist', 'serviceMaster'])
            ->whereDate('appointment_date', $date)
            ->get()
            ->groupBy('physiotherapist_id');
            
        $therapySessions = \App\Models\TherapySession::with(['patient', 'physiotherapist', 'serviceMaster'])
            ->whereDate('therapy_date', $date)
            ->get()
            ->keyBy('appointment_id');
            
        $startHour = 8;
        $endHour = 17;
        
        $result = [];
        
        foreach ($physiotherapists as $physio) {
            $physioSlots = [];
            $physioAppointments = $appointments->get($physio->id, collect());
            
            for ($i = $startHour; $i < $endHour; $i++) {
                $timeSlot = sprintf('%02d:00', $i);
                $nextTimeSlot = sprintf('%02d:00', $i + 1);
                $timeRange = "$timeSlot - $nextTimeSlot";
                
                $slotData = [
                    'time' => $timeSlot,
                    'range' => $timeRange,
                    'is_empty' => true,
                    'data' => null
                ];
                
                $appointment = $physioAppointments->first(function ($app) use ($timeSlot) {
                    return substr($app->appointment_time, 0, 5) === $timeSlot;
                });
                
                if ($appointment) {
                    $session = $therapySessions->get($appointment->id);
                    $status = 'Kosong';
                    $status_code = 'empty';
                    
                    if ($session && $session->status === 'completed') {
                        $status = 'Selesai';
                        $status_code = 'completed';
                    } elseif ($session && $session->status === 'ongoing') {
                        $status = 'Sedang Terapi';
                        $status_code = 'ongoing';
                    } else {
                        if (in_array($appointment->status, ['pending', 'approved'])) {
                            $status = 'Sudah Dijadwalkan';
                            $status_code = 'scheduled';
                        } elseif ($appointment->status === 'telah_tiba') {
                            $status = 'Pasien Datang';
                            $status_code = 'arrived';
                        } elseif ($appointment->status === 'completed') {
                            $status = 'Selesai';
                            $status_code = 'completed';
                        } else {
                            $status = ucfirst($appointment->status);
                            $status_code = $appointment->status;
                        }
                    }
                    
                    $slotData['is_empty'] = false;
                    $slotData['data'] = [
                        'appointment' => $appointment,
                        'therapy_session' => $session,
                        'display_status' => $status,
                        'status_code' => $status_code,
                        'patient_name' => $appointment->patient->name ?? '-',
                        'physiotherapist_name' => $physio->name,
                    ];
                }
                $physioSlots[] = $slotData;
            }
            
            $result[] = [
                'physiotherapist' => $physio,
                'slots' => $physioSlots
            ];
        }

        return response()->json([
            'success' => true,
            'message' => 'Schedule retrieved successfully',
            'data' => $result
        ]);
    }

    public function getWeeklySchedule(Request $request): JsonResponse
    {
        $startDate = $request->input('start_date', now()->startOfWeek()->toDateString());
        $endDate = $request->input('end_date', now()->endOfWeek()->toDateString());
        
        $physiotherapists = \App\Models\Physiotherapist::all();
        
        $appointments = \App\Models\Appointment::with(['patient', 'physiotherapist', 'serviceMaster'])
            ->whereBetween('appointment_date', [$startDate, $endDate])
            ->get()
            ->groupBy(function($item) {
                // Parse the datetime to get just the date portion
                $dateStr = $item->appointment_date instanceof \Carbon\Carbon 
                    ? $item->appointment_date->format('Y-m-d') 
                    : \Carbon\Carbon::parse($item->appointment_date)->format('Y-m-d');
                return $item->physiotherapist_id . '_' . $dateStr;
            });
            
        $therapySessions = \App\Models\TherapySession::with(['patient', 'physiotherapist', 'serviceMaster'])
            ->whereBetween('therapy_date', [$startDate, $endDate])
            ->get()
            ->keyBy('appointment_id');
            
        $startHour = 8;
        $endHour = 17;
        
        $result = [];
        
        $dates = [];
        $currentDate = \Carbon\Carbon::parse($startDate);
        $end = \Carbon\Carbon::parse($endDate);
        while ($currentDate <= $end) {
            $dates[] = $currentDate->format('Y-m-d');
            $currentDate->addDay();
        }

        foreach ($physiotherapists as $physio) {
            $physioSchedule = [];
            
            foreach ($dates as $date) {
                $daySlots = [];
                $key = $physio->id . '_' . $date;
                $physioAppointments = $appointments->get($key, collect());
                
                for ($i = $startHour; $i < $endHour; $i++) {
                    $timeSlot = sprintf('%02d:00', $i);
                    $nextTimeSlot = sprintf('%02d:00', $i + 1);
                    $timeRange = "$timeSlot - $nextTimeSlot";
                    
                    $slotData = [
                        'time' => $timeSlot,
                        'range' => $timeRange,
                        'is_empty' => true,
                        'data' => null
                    ];
                    
                    $appointment = $physioAppointments->first(function ($app) use ($timeSlot) {
                        return substr($app->appointment_time, 0, 5) === $timeSlot;
                    });
                    
                    if ($appointment) {
                        $session = $therapySessions->get($appointment->id);
                        $status = 'Kosong';
                        $status_code = 'empty';
                        
                        if ($session && $session->status === 'completed') {
                            $status = 'Selesai';
                            $status_code = 'completed';
                        } elseif ($session && $session->status === 'ongoing') {
                            $status = 'Sedang Terapi';
                            $status_code = 'ongoing';
                        } else {
                            if (in_array($appointment->status, ['pending', 'approved'])) {
                                $status = 'Sudah Dijadwalkan';
                                $status_code = 'scheduled';
                            } elseif ($appointment->status === 'telah_tiba') {
                                $status = 'Pasien Datang';
                                $status_code = 'arrived';
                            } elseif ($appointment->status === 'completed') {
                                $status = 'Selesai';
                                $status_code = 'completed';
                            } else {
                                $status = ucfirst($appointment->status);
                                $status_code = $appointment->status;
                            }
                        }
                        
                        $slotData['is_empty'] = false;
                        $slotData['data'] = [
                            'appointment' => $appointment,
                            'therapy_session' => $session,
                            'display_status' => $status,
                            'status_code' => $status_code,
                            'patient_name' => $appointment->patient->name ?? '-',
                            'physiotherapist_name' => $physio->name,
                        ];
                    }
                    $daySlots[] = $slotData;
                }
                $physioSchedule[$date] = $daySlots;
            }
            
            $result[] = [
                'physiotherapist' => $physio,
                'schedule' => $physioSchedule
            ];
        }

        return response()->json([
            'success' => true,
            'message' => 'Weekly schedule retrieved successfully',
            'data' => $result
        ]);
    }
}
