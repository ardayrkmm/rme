<?php

namespace App\Services;

use App\Interfaces\MedicalRecordServiceInterface;
use App\Interfaces\MedicalRecordRepositoryInterface;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;
use Exception;

class MedicalRecordService implements MedicalRecordServiceInterface
{
    protected $medicalRecordRepository;

    public function __construct(MedicalRecordRepositoryInterface $medicalRecordRepository)
    {
        $this->medicalRecordRepository = $medicalRecordRepository;
    }

    public function getPaginatedRecords(array $filters = [], int $perPage = 15)
    {
        return $this->medicalRecordRepository->getPaginated($filters, $perPage);
    }

    public function getPatientHistory(string $patientId, array $filters = [], int $perPage = 15)
    {
        return $this->medicalRecordRepository->getByPatientId($patientId, $filters, $perPage);
    }

    public function getMedicalRecordById(string $id)
    {
        $record = $this->medicalRecordRepository->findByIdWithTrashed($id, ['patient', 'physiotherapist', 'appointment', 'service']);

        if (!$record) {
            throw new Exception('Rekam medis tidak ditemukan', 404);
        }

        return $record;
    }

    public function createRecord(array $data)
    {
        if (empty($data['visit_number'])) {
            $data['visit_number'] = $this->generateVisitNumber();
        }

        if (isset($data['attachment'])) {
            $data['attachment'] = $data['attachment']->store('medical_records/attachments', 'public');
        }

        return $this->medicalRecordRepository->create($data);
    }

    public function updateRecord(string $id, array $data)
    {
        $record = $this->medicalRecordRepository->find($id);

        if (!$record) {
            throw new Exception('Rekam medis tidak ditemukan', 404);
        }

        if (isset($data['attachment'])) {
            if ($record->attachment) {
                Storage::disk('public')->delete($record->attachment);
            }
            $data['attachment'] = $data['attachment']->store('medical_records/attachments', 'public');
        }

        return $this->medicalRecordRepository->update($id, $data);
    }

    protected function generateVisitNumber(): string
    {
        $today = \Carbon\Carbon::today();
        $datePrefix = 'VN-' . $today->format('Ymd') . '-';

        $latestRecord = $this->medicalRecordRepository->getLatestRecordToday();

        if ($latestRecord && strpos($latestRecord->visit_number, $datePrefix) === 0) {
            $lastSequence = (int) substr($latestRecord->visit_number, -4);
            $newSequence = $lastSequence + 1;
        } else {
            $newSequence = 1;
        }

        return $datePrefix . str_pad((string) $newSequence, 4, '0', STR_PAD_LEFT);
    }

    public function deleteRecord(string $id)
    {
        return $this->medicalRecordRepository->delete($id);
    }

    public function exportPdf(string $id)
    {
        $record = $this->medicalRecordRepository->find($id);

        if (!$record) {
            throw new Exception('Rekam medis tidak ditemukan', 404);
        }

        $record->load(['patient', 'physiotherapist', 'appointment', 'service']);

        // Mengasumsikan package barryvdh/laravel-dompdf terinstall
        // dan file view resources/views/pdf/medical_record.blade.php tersedia
        $pdf = Pdf::loadView('pdf.medical_record', compact('record'));
        
        return $pdf;
    }
}
