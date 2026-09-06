<?php

namespace App\Repositories;

use App\Interfaces\PatientRepositoryInterface;
use App\Models\Patient;
use Carbon\Carbon;

class PatientRepository extends BaseRepository implements PatientRepositoryInterface
{
    public function __construct(Patient $model)
    {
        parent::__construct($model);
    }

    public function getPaginated(array $filters = [], int $perPage = 15)
    {
        $query = $this->model->with(['category', 'gender']);

        if (isset($filters['search']) && !empty($filters['search'])) {
            $query->where(function ($q) use ($filters) {
                $q->where('name', 'like', '%' . $filters['search'] . '%')
                  ->orWhere('medical_record_number', 'like', '%' . $filters['search'] . '%')
                  ->orWhere('nik', 'like', '%' . $filters['search'] . '%')
                  ->orWhere('phone', 'like', '%' . $filters['search'] . '%');
            });
        }

        return $query->orderBy('created_at', 'desc')->paginate($perPage);
    }

    public function getAllForExport(array $filters = [])
    {
        $query = $this->model->with(['category', 'gender']);

        if (isset($filters['search']) && !empty($filters['search'])) {
            $query->where(function ($q) use ($filters) {
                $q->where('name', 'like', '%' . $filters['search'] . '%')
                  ->orWhere('medical_record_number', 'like', '%' . $filters['search'] . '%')
                  ->orWhere('nik', 'like', '%' . $filters['search'] . '%')
                  ->orWhere('phone', 'like', '%' . $filters['search'] . '%');
            });
        }

        return $query->orderBy('created_at', 'desc')->get();
    }

    public function getLatestPatientToday()
    {
        return $this->model->whereDate('created_at', Carbon::today())->latest('id')->first();
    }

    public function restore($id)
    {
        $model = $this->model->withTrashed()->find($id);

        if (!$model) {
            return false;
        }

        return $model->restore();
    }
}
