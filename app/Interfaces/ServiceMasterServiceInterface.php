<?php

namespace App\Interfaces;

interface ServiceMasterServiceInterface
{
    public function getPaginatedServiceMasters(array $filters = [], int $perPage = 15);
    public function getServiceMasterById(string $id);
    public function createServiceMaster(array $data);
    public function updateServiceMaster(string $id, array $data);
    public function deleteServiceMaster(string $id);
}
