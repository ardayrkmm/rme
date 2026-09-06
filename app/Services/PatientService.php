<?php

namespace App\Services;

use App\Interfaces\PatientServiceInterface;
use App\Interfaces\PatientRepositoryInterface;
use Carbon\Carbon;
use Exception;

class PatientService implements PatientServiceInterface
{
    protected $patientRepository;

    public function __construct(PatientRepositoryInterface $patientRepository)
    {
        $this->patientRepository = $patientRepository;
    }

    public function getPaginatedPatients(array $filters = [], int $perPage = 15)
    {
        return $this->patientRepository->getPaginated($filters, $perPage);
    }

    public function exportCsv(array $filters = [])
    {
        $patients = $this->patientRepository->getAllForExport($filters);

        $csvData = [];
        $headers = ['No. RM', 'NIK', 'Nama', 'Kategori', 'No. Telepon', 'Alamat', 'Tanggal Lahir', 'Usia', 'Jenis Kelamin', 'Status Menikah', 'Pekerjaan', 'Terdaftar Pada'];

        foreach ($patients as $patient) {
            $csvData[] = [
                $patient->medical_record_number,
                $patient->nik,
                $patient->name,
                $patient->category ? $patient->category->name : '-',
                $patient->phone,
                $patient->address,
                $patient->birth_date ? Carbon::parse($patient->birth_date)->format('Y-m-d') : '-',
                $patient->birth_date ? Carbon::parse($patient->birth_date)->age : '-',
                $patient->gender ? $patient->gender->name : '-',
                $patient->marital_status ?? '-',
                $patient->occupation ?? '-',
                $patient->created_at->format('Y-m-d H:i:s'),
            ];
        }

        return [
            'headers' => $headers,
            'data' => $csvData
        ];
    }

    public function getPatientById(string $id)
    {
        $patient = $this->patientRepository->findByIdWithTrashed($id);

        if (!$patient) {
            throw new Exception('Pasien tidak ditemukan', 404);
        }

        return $patient;
    }

    public function createPatient(array $data)
    {
        $data['medical_record_number'] = $this->generateMedicalRecordNumber();

        return $this->patientRepository->create($data);
    }

    public function updatePatient(string $id, array $data)
    {
        // Cegah update nomor rekam medis
        unset($data['medical_record_number']);

        return $this->patientRepository->update($id, $data);
    }

    public function deletePatient(string $id)
    {
        return $this->patientRepository->delete($id);
    }

    public function restorePatient(string $id)
    {
        return $this->patientRepository->restore($id);
    }

    /**
     * Generate Nomor Rekam Medis (Format: RM-YYYYMMDD-XXXX)
     */
    protected function generateMedicalRecordNumber(): string
    {
        $today = Carbon::today();
        $datePrefix = 'RM-' . $today->format('Ymd') . '-';

        $latestPatient = $this->patientRepository->getLatestPatientToday();

        if ($latestPatient && strpos($latestPatient->medical_record_number, $datePrefix) === 0) {
            // Ambil 4 digit terakhir dan increment
            $lastSequence = (int) substr($latestPatient->medical_record_number, -4);
            $newSequence = $lastSequence + 1;
        } else {
            $newSequence = 1;
        }

        return $datePrefix . str_pad((string) $newSequence, 4, '0', STR_PAD_LEFT);
    }
}
