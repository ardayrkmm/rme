<?php

namespace App\Services;

use App\Interfaces\ServiceMasterRepositoryInterface;
use App\Interfaces\ServiceMasterServiceInterface;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ServiceMasterService implements ServiceMasterServiceInterface
{
    protected $serviceMasterRepository;

    public function __construct(ServiceMasterRepositoryInterface $serviceMasterRepository)
    {
        $this->serviceMasterRepository = $serviceMasterRepository;
    }

    public function getPaginatedServiceMasters(array $filters = [], int $perPage = 15)
    {
        return $this->serviceMasterRepository->getAllPaginated($filters, $perPage);
    }

    public function exportCsv(array $filters = [])
    {
        $services = $this->serviceMasterRepository->getAllForExport($filters);

        $csvData = [];
        $headers = ['Kode', 'Nama', 'Kategori', 'Durasi (menit)', 'Harga', 'Status', 'Terdaftar Pada'];

        foreach ($services as $service) {
            $csvData[] = [
                $service->code,
                $service->name,
                $service->category,
                $service->duration,
                $service->base_price,
                $service->is_active ? 'Aktif' : 'Nonaktif',
                $service->created_at->format('Y-m-d H:i:s'),
            ];
        }

        return [
            'headers' => $headers,
            'data' => $csvData
        ];
    }

    public function getServiceMasterById(string $id)
    {
        return $this->serviceMasterRepository->getById($id);
    }

    public function createServiceMaster(array $data)
    {
        DB::beginTransaction();
        try {
            // Generate Code otomatis
            $lastService = \App\Models\ServiceMaster::withTrashed()->orderBy('id', 'desc')->first();
            $nextNumber = $lastService ? $lastService->id + 1 : 1;
            $data['code'] = 'LYN-' . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
            
            // Map price from frontend to base_price in database
            if (isset($data['price'])) {
                $data['base_price'] = $data['price'];
                unset($data['price']);
            }

            $service = $this->serviceMasterRepository->create($data);
            DB::commit();
            return $service;
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Error creating service master: ' . $e->getMessage());
            throw $e;
        }
    }

    public function updateServiceMaster(string $id, array $data)
    {
        DB::beginTransaction();
        try {
            if (isset($data['price'])) {
                $data['base_price'] = $data['price'];
                unset($data['price']);
            }
            $service = $this->serviceMasterRepository->update($id, $data);
            DB::commit();
            return $service;
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Error updating service master: ' . $e->getMessage());
            throw $e;
        }
    }

    public function deleteServiceMaster(string $id)
    {
        DB::beginTransaction();
        try {
            $result = $this->serviceMasterRepository->delete($id);
            DB::commit();
            return $result;
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Error deleting service master: ' . $e->getMessage());
            throw $e;
        }
    }
}
