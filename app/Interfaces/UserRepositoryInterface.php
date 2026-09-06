<?php

namespace App\Interfaces;

interface UserRepositoryInterface extends BaseRepositoryInterface
{
    /**
     * Find user by email.
     *
     * @param string $email
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function findByEmail(string $email);

    /**
     * Get paginated users with optional search and role filter.
     *
     * @param array $filters
     * @param int $perPage
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function getPaginatedUsers(array $filters = [], int $perPage = 15);

    /**
     * Restore a soft deleted user.
     *
     * @param int|string $id
     * @return bool
     */
    public function restore($id);
}
