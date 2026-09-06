<?php

namespace App\Interfaces;

interface ClinicServiceInterface
{
    public function getPaginatedClinics(array $filters = [], int $perPage = 15);
    public function getClinicById(string $id);
    public function createClinic(array $data);
    public function updateClinic(string $id, array $data);
    public function deleteClinic(string $id);
    public function restoreClinic(string $id);
}
