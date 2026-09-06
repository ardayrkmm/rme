<?php

namespace App\Services;

use App\Interfaces\UserServiceInterface;
use App\Interfaces\UserRepositoryInterface;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Exception;

class UserService implements UserServiceInterface
{
    protected $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function getPaginatedUsers(array $filters = [], int $perPage = 15)
    {
        return $this->userRepository->getPaginatedUsers($filters, $perPage);
    }

    public function createUser(array $data)
    {
        if (isset($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        }

        if (isset($data['photo'])) {
            $data['photo'] = $data['photo']->store('photos', 'public');
        }

        return $this->userRepository->create($data);
    }

    public function updateUser(string $id, array $data)
    {
        $user = $this->userRepository->find($id);

        if (!$user) {
            throw new Exception('User tidak ditemukan', 404);
        }

        if (isset($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']); // Jangan update password jika tidak diisi
        }

        if (isset($data['photo'])) {
            if ($user->photo) {
                Storage::disk('public')->delete($user->photo);
            }
            $data['photo'] = $data['photo']->store('photos', 'public');
        }

        return $this->userRepository->update($id, $data);
    }

    public function deleteUser(string $id)
    {
        return $this->userRepository->delete($id);
    }

    public function restoreUser(string $id)
    {
        return $this->userRepository->restore($id);
    }

    public function updateProfile(string $id, array $data)
    {
        // Hanya izinkan field tertentu untuk diupdate melalui profile
        $allowedData = [];
        if (isset($data['name'])) {
            $allowedData['name'] = $data['name'];
        }
        
        $user = $this->userRepository->find($id);
        
        if (isset($data['photo'])) {
            if ($user->photo) {
                Storage::disk('public')->delete($user->photo);
            }
            $allowedData['photo'] = $data['photo']->store('photos', 'public');
        }

        if (!empty($allowedData)) {
            $this->userRepository->update($id, $allowedData);
        }

        return $this->userRepository->find($id);
    }
}
