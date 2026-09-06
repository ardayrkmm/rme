<?php

namespace App\Repositories;

use App\Interfaces\MedicalRecordRepositoryInterface;
use App\Models\MedicalRecord;

class MedicalRecordRepository extends BaseRepository implements MedicalRecordRepositoryInterface
{
    public function __construct(MedicalRecord $model)
    {
        parent::__construct($model);
    }

    public function getPaginated(array $filters = [], int $perPage = 15)
    {
        $query = $this->model->newQuery()->with(['patient', 'physiotherapist', 'appointment', 'service']);

        // Filter pencarian nama pasien
        if (isset($filters['search']) && !empty($filters['search'])) {
            $search = $filters['search'];
            $query->whereHas('patient', function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('medical_record_number', 'like', '%' . $search . '%');
            });
        }

        // Filter untuk Fisioterapis: hanya melihat rekam medis pasien yang pernah ditangani
        if (isset($filters['treated_by_physio_id']) && !empty($filters['treated_by_physio_id'])) {
            $physioId = $filters['treated_by_physio_id'];
            $treatedPatientIds = \App\Models\TherapySession::where('physiotherapist_id', $physioId)
                ->pluck('patient_id')
                ->unique()
                ->toArray();
            
            $query->whereIn('patient_id', $treatedPatientIds);
        }

        return $query->orderBy('examination_date', 'desc')->paginate($perPage);
    }

    public function getByPatientId(string $patientId, array $filters = [], int $perPage = 15)
    {
        return $this->model->where('patient_id', $patientId)
            ->with(['physiotherapist', 'appointment', 'service'])
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);
    }

    public function getLatestRecordToday()
    {
        return $this->model->whereDate('created_at', \Carbon\Carbon::today())->latest('id')->first();
    }
}
