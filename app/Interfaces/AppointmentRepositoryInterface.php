<?php

namespace App\Interfaces;

interface AppointmentRepositoryInterface extends BaseRepositoryInterface
{
    /**
     * Get paginated appointments with filters (date range, status, patient name).
     *
     * @param array $filters
     * @param int $perPage
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function getPaginated(array $filters = [], int $perPage = 15);

    /**
     * Get paginated history of appointments (completed/cancelled).
     *
     * @param array $filters
     * @param int $perPage
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function getHistoryPaginated(array $filters = [], int $perPage = 15);
}
