<?php

namespace App\Interfaces;

interface PhysiotherapistRepositoryInterface extends BaseRepositoryInterface
{
    /**
     * Get paginated physiotherapists with optional search and filters.
     *
     * @param array $filters
     * @param int $perPage
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function getPaginated(array $filters = [], int $perPage = 15);

    /**
     * Restore a soft deleted physiotherapist.
     *
     * @param int|string $id
     * @return bool
     */
    public function restore($id);
}
