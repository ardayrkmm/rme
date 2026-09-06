<?php

namespace App\Services;

use App\Interfaces\ActivityLogServiceInterface;
use App\Interfaces\ActivityLogRepositoryInterface;
use Exception;

class ActivityLogService implements ActivityLogServiceInterface
{
    protected $activityLogRepository;

    public function __construct(ActivityLogRepositoryInterface $activityLogRepository)
    {
        $this->activityLogRepository = $activityLogRepository;
    }

    public function getPaginatedLogs(array $filters = [], int $perPage = 15)
    {
        return $this->activityLogRepository->getPaginated($filters, $perPage);
    }

    public function getLogById(string $id)
    {
        $log = $this->activityLogRepository->find($id);
        
        if (!$log) {
            throw new Exception('Log aktivitas tidak ditemukan', 404);
        }

        return $log;
    }

    public function deleteLog(string $id)
    {
        return $this->activityLogRepository->delete($id);
    }
}
