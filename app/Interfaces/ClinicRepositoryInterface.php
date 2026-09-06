<?php

namespace App\Interfaces;

interface ClinicRepositoryInterface extends BaseRepositoryInterface
{
    /**
     * Get paginated clinics with optional search filter.
     *
     * @param array $filters
     * @param int $perPage
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function getPaginated(array $filters = [], int $perPage = 15);

    /**
     * Restore a soft deleted clinic.
     *
     * @param int|string $id
     * @return bool
     */
    public function restore($id);
}
