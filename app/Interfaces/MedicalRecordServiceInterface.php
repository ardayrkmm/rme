<?php

namespace App\Interfaces;

interface MedicalRecordServiceInterface
{
    public function getPaginatedRecords(array $filters = [], int $perPage = 15);
    public function getPatientHistory(string $patientId, array $filters = [], int $perPage = 15);
    public function getMedicalRecordById(string $id);
    public function createRecord(array $data);
    public function updateRecord(string $id, array $data);
    public function deleteRecord(string $id);
    public function exportPdf(string $id);
}
