<?php

namespace App\Interfaces;

interface MedicalRecordRepositoryInterface extends BaseRepositoryInterface
{
    /**
     * Get paginated medical records.
     *
     * @param array $filters
     * @param int $perPage
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function getPaginated(array $filters = [], int $perPage = 15);

    /**
     * Get paginated medical records by patient ID.
     *
     * @param string $patientId
     * @param array $filters
     * @param int $perPage
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function getByPatientId(string $patientId, array $filters = [], int $perPage = 15);
}
