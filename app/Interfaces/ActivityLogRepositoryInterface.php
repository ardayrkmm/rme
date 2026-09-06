<?php

namespace App\Interfaces;

interface ActivityLogRepositoryInterface extends BaseRepositoryInterface
{
    public function getPaginated(array $filters = [], int $perPage = 15);
}
