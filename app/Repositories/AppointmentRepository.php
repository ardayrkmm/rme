<?php

namespace App\Repositories;

use App\Interfaces\AppointmentRepositoryInterface;
use App\Models\Appointment;

class AppointmentRepository extends BaseRepository implements AppointmentRepositoryInterface
{
    public function __construct(Appointment $model)
    {
        parent::__construct($model);
    }

    public function getPaginated(array $filters = [], int $perPage = 15)
    {
        $query = $this->model->newQuery()->with(['patient', 'physiotherapist', 'serviceMaster']);

        // Filter berdasarkan pencarian nama pasien atau fisioterapis
        if (isset($filters['search']) && !empty($filters['search'])) {
            $search = $filters['search'];
            $query->whereHas('patient', function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%');
            })->orWhereHas('physiotherapist', function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%');
            });
        }

        // Filter status
        if (isset($filters['status']) && !empty($filters['status'])) {
            $query->where('status', $filters['status']);
        } else {
            // Default: do not show completed or cancelled in active list
            $query->whereNotIn('status', ['completed', 'cancelled']);
        }

        if (isset($filters['patient_id']) && !empty($filters['patient_id'])) {
            $query->where('patient_id', $filters['patient_id']);
        }

        if (isset($filters['physiotherapist_id']) && !empty($filters['physiotherapist_id'])) {
            $query->where('physiotherapist_id', $filters['physiotherapist_id']);
        }

        // Filter rentang tanggal
        if (isset($filters['start_date']) && !empty($filters['start_date'])) {
            $query->whereDate('appointment_date', '>=', $filters['start_date']);
        }
        if (isset($filters['end_date']) && !empty($filters['end_date'])) {
            $query->whereDate('appointment_date', '<=', $filters['end_date']);
        }

        return $query->orderBy('appointment_date', 'desc')->orderBy('appointment_time', 'desc')->paginate($perPage);
    }

    public function getHistoryPaginated(array $filters = [], int $perPage = 15)
    {
        $query = $this->model->newQuery()->with(['patient', 'physiotherapist']);

        $query->whereIn('status', ['completed', 'cancelled']);

        if (isset($filters['search']) && !empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->whereHas('patient', function ($q2) use ($search) {
                    $q2->where('name', 'like', '%' . $search . '%');
                })->orWhereHas('physiotherapist', function ($q2) use ($search) {
                    $q2->where('name', 'like', '%' . $search . '%');
                });
            });
        }

        if (isset($filters['start_date']) && !empty($filters['start_date'])) {
            $query->whereDate('appointment_date', '>=', $filters['start_date']);
        }
        if (isset($filters['end_date']) && !empty($filters['end_date'])) {
            $query->whereDate('appointment_date', '<=', $filters['end_date']);
        }

        return $query->orderBy('appointment_date', 'desc')->orderBy('appointment_time', 'desc')->paginate($perPage);
    }
}
