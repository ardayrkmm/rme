<?php

namespace App\Services;

use App\Interfaces\AppointmentServiceInterface;
use App\Interfaces\AppointmentRepositoryInterface;
use Exception;

class AppointmentService implements AppointmentServiceInterface
{
    protected $appointmentRepository;

    public function __construct(AppointmentRepositoryInterface $appointmentRepository)
    {
        $this->appointmentRepository = $appointmentRepository;
    }

    public function getPaginatedAppointments(array $filters = [], int $perPage = 15)
    {
        return $this->appointmentRepository->getPaginated($filters, $perPage);
    }

    public function getHistoryPaginated(array $filters = [], int $perPage = 15)
    {
        return $this->appointmentRepository->getHistoryPaginated($filters, $perPage);
    }

    public function getAppointmentById(string $id)
    {
        $appointment = $this->appointmentRepository->findByIdWithTrashed($id, ['patient', 'physiotherapist']);

        if (!$appointment) {
            throw new Exception('Temu janji tidak ditemukan', 404);
        }

        return $appointment;
    }

    public function createAppointment(array $data)
    {
        // Prevent double booking for the same physiotherapist
        $existing = \App\Models\Appointment::where('physiotherapist_id', $data['physiotherapist_id'])
            ->whereDate('appointment_date', $data['appointment_date'])
            ->whereTime('appointment_time', '>=', substr($data['appointment_time'], 0, 5) . ':00')
            ->whereTime('appointment_time', '<=', substr($data['appointment_time'], 0, 5) . ':59')
            ->whereNotIn('status', ['cancelled'])
            ->first();

        if ($existing) {
            throw new Exception('Fisioterapis sudah memiliki jadwal pada tanggal dan jam tersebut.', 422);
        }

        $data['status'] = 'pending';
        $appointment = $this->appointmentRepository->create($data);

        return $appointment;
    }

    public function updateAppointment(string $id, array $data)
    {
        $appointment = $this->appointmentRepository->find($id);
        
        return $this->appointmentRepository->update($id, $data);
    }

    public function rescheduleAppointment(string $id, array $data)
    {
        $appointment = $this->appointmentRepository->find($id);

        if (!$appointment) {
            throw new Exception('Jadwal temu janji tidak ditemukan', 404);
        }

        if (in_array($appointment->status, ['completed', 'cancelled'])) {
            throw new Exception('Tidak dapat mengubah jadwal yang sudah selesai atau dibatalkan', 400);
        }

        // Prevent double booking on reschedule
        $existing = \App\Models\Appointment::where('physiotherapist_id', $appointment->physiotherapist_id)
            ->whereDate('appointment_date', $data['appointment_date'])
            ->whereTime('appointment_time', '>=', substr($data['appointment_time'], 0, 5) . ':00')
            ->whereTime('appointment_time', '<=', substr($data['appointment_time'], 0, 5) . ':59')
            ->where('id', '!=', $id)
            ->whereNotIn('status', ['cancelled'])
            ->first();

        if ($existing) {
            throw new Exception('Fisioterapis sudah memiliki jadwal pada tanggal dan jam tersebut.', 422);
        }

        $updateData = [
            'appointment_date' => $data['appointment_date'],
            'appointment_time' => $data['appointment_time'],
            'status' => 'rescheduled',
        ];

        return $this->appointmentRepository->update($id, $updateData);
    }

    public function cancelAppointment(string $id, array $data = [])
    {
        $appointment = $this->appointmentRepository->find($id);

        if (!$appointment) {
            throw new Exception('Jadwal temu janji tidak ditemukan', 404);
        }

        if ($appointment->status === 'completed') {
            throw new Exception('Tidak dapat membatalkan jadwal yang sudah selesai', 400);
        }

        $updateData = ['status' => 'cancelled'];
        
        if (isset($data['notes'])) {
            $updateData['notes'] = $appointment->notes . "\n[Alasan Batal]: " . $data['notes'];
        }

        return $this->appointmentRepository->update($id, $updateData);
    }

    public function deleteAppointment(string $id)
    {
        return $this->appointmentRepository->delete($id);
    }
}
