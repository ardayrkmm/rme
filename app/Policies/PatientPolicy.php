<?php

namespace App\Policies;

use App\Models\User;
use App\Enums\RoleEnum;

class PatientPolicy
{
    /**
     * Tentukan apakah user bisa melihat daftar pasien.
     */
    public function viewAny(User $user): bool
    {
        return in_array($user->role, [
            RoleEnum::ADMIN, 
            RoleEnum::FISIOTERAPIS,
            RoleEnum::STAFF
        ]);
    }

    /**
     * Tentukan apakah user bisa melihat detail pasien.
     */
    public function view(User $user): bool
    {
        return in_array($user->role, [
            RoleEnum::ADMIN, 
            RoleEnum::FISIOTERAPIS,
            RoleEnum::STAFF
        ]);
    }

    /**
     * Tentukan apakah user bisa mengelola pasien (Buat, Ubah, Hapus, Restore).
     */
    public function manage(User $user): bool
    {
        return in_array($user->role, [
            RoleEnum::ADMIN, 
            RoleEnum::OWNER,
            RoleEnum::STAFF
        ]);
    }
}
