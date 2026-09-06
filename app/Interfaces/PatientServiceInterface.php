<?php

namespace App\Interfaces;

interface PatientServiceInterface
{
    public function getPaginatedPatients(array $filters = [], int $perPage = 15);
    public function getPatientById(string $id);
    public function createPatient(array $data);
    public function updatePatient(string $id, array $data);
    public function deletePatient(string $id);
    public function restorePatient(string $id);
}
