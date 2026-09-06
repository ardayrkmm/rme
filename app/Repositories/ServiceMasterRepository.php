<?php

namespace App\Repositories;

use App\Interfaces\ServiceMasterRepositoryInterface;
use App\Models\ServiceMaster;
use Illuminate\Pagination\LengthAwarePaginator;

class ServiceMasterRepository implements ServiceMasterRepositoryInterface
{
    public function getAllPaginated(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        $query = ServiceMaster::query();

        if (isset($filters['search']) && $filters['search']) {
            $searchTerm = $filters['search'];
            $query->where(function ($q) use ($searchTerm) {
                $q->where('name', 'like', "%{$searchTerm}%")
                  ->orWhere('code', 'like', "%{$searchTerm}%")
                  ->orWhere('category', 'like', "%{$searchTerm}%");
            });
        }
        
        if (isset($filters['category']) && $filters['category']) {
            $query->where('category', $filters['category']);
        }
        
        if (isset($filters['is_active']) && $filters['is_active'] !== null) {
            $query->where('is_active', $filters['is_active']);
        }

        return $query->latest()->paginate($perPage);
    }

    public function getAllForExport(array $filters = [])
    {
        $query = ServiceMaster::query();

        if (isset($filters['search']) && $filters['search']) {
            $searchTerm = $filters['search'];
            $query->where(function ($q) use ($searchTerm) {
                $q->where('name', 'like', "%{$searchTerm}%")
                  ->orWhere('code', 'like', "%{$searchTerm}%")
                  ->orWhere('category', 'like', "%{$searchTerm}%");
            });
        }
        
        if (isset($filters['category']) && $filters['category']) {
            $query->where('category', $filters['category']);
        }
        
        if (isset($filters['is_active']) && $filters['is_active'] !== null) {
            $query->where('is_active', $filters['is_active']);
        }

        return $query->latest()->get();
    }

    public function getById(string $id): ?ServiceMaster
    {
        return ServiceMaster::findOrFail($id);
    }

    public function create(array $data): ServiceMaster
    {
        return ServiceMaster::create($data);
    }

    public function update(string $id, array $data): ServiceMaster
    {
        $service = ServiceMaster::findOrFail($id);
        $service->update($data);
        return $service;
    }

    public function delete(string $id): bool
    {
        $service = ServiceMaster::findOrFail($id);
        return $service->delete();
    }
}
