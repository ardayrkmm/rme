<?php

namespace App\Repositories;

use App\Interfaces\PhysiotherapistRepositoryInterface;
use App\Models\Physiotherapist;

class PhysiotherapistRepository extends BaseRepository implements PhysiotherapistRepositoryInterface
{
    public function __construct(Physiotherapist $model)
    {
        parent::__construct($model);
    }

    public function getPaginated(array $filters = [], int $perPage = 15)
    {
        $query = $this->model->newQuery();

        if (isset($filters['search']) && !empty($filters['search'])) {
            $query->where(function ($q) use ($filters) {
                $q->where('name', 'like', '%' . $filters['search'] . '%')
                  ->orWhere('sip', 'like', '%' . $filters['search'] . '%')
                  ->orWhere('phone', 'like', '%' . $filters['search'] . '%');
            });
        }

        if (isset($filters['status']) && !empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }


        return $query->orderBy('created_at', 'desc')->paginate($perPage);
    }

    public function getAllForExport(array $filters = [])
    {
        $query = $this->model->newQuery();

        if (isset($filters['search']) && !empty($filters['search'])) {
            $query->where(function ($q) use ($filters) {
                $q->where('name', 'like', '%' . $filters['search'] . '%')
                  ->orWhere('sip', 'like', '%' . $filters['search'] . '%')
                  ->orWhere('phone', 'like', '%' . $filters['search'] . '%');
            });
        }

        if (isset($filters['status']) && !empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        return $query->orderBy('created_at', 'desc')->get();
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
