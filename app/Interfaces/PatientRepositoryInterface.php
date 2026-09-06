<?php

namespace App\Interfaces;

interface PatientRepositoryInterface extends BaseRepositoryInterface
{
    /**
     * Get paginated patients with optional search.
     *
     * @param array $filters
     * @param int $perPage
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function getPaginated(array $filters = [], int $perPage = 15);

    /**
     * Get the latest patient registered today.
     *
     * @return \App\Models\Patient|null
     */
    public function getLatestPatientToday();

    /**
     * Restore a soft deleted patient.
     *
     * @param int|string $id
     * @return bool
     */
    public function restore($id);
}
