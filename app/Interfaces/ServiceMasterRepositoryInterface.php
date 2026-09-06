<?php

namespace App\Interfaces;

interface ServiceMasterRepositoryInterface
{
    public function getAllPaginated(array $filters = [], int $perPage = 15);
    public function getById(string $id);
    public function create(array $data);
    public function update(string $id, array $data);
    public function delete(string $id);
}
