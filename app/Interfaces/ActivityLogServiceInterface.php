<?php

namespace App\Interfaces;

interface ActivityLogServiceInterface
{
    public function getPaginatedLogs(array $filters = [], int $perPage = 15);
    public function getLogById(string $id);
    public function deleteLog(string $id);
}
