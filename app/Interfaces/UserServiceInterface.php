<?php

namespace App\Interfaces;

interface UserServiceInterface
{
    public function getPaginatedUsers(array $filters = [], int $perPage = 15);
    public function createUser(array $data);
    public function updateUser(string $id, array $data);
    public function deleteUser(string $id);
    public function restoreUser(string $id);
    public function updateProfile(string $id, array $data);
}
