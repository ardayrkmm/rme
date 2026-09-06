<?php

namespace App\Policies;

use App\Models\User;
use App\Enums\RoleEnum;

class MedicalRecordPolicy
{
    /**
     * Tentukan apakah user bisa melihat daftar rekam medis.
     */
    public function viewAny(User $user): bool
    {
        return in_array($user->role, [
            RoleEnum::ADMIN, 
            RoleEnum::FISIOTERAPIS
        ]);
    }

    /**
     * Tentukan apakah user bisa melihat detail rekam medis.
     */
    public function view(User $user): bool
    {
        return in_array($user->role, [
            RoleEnum::ADMIN, 
            RoleEnum::FISIOTERAPIS
        ]);
    }

    /**
     * Tentukan apakah user bisa mengelola rekam medis (Buat, Ubah, Hapus).
     */
    public function manage(User $user): bool
    {
        return in_array($user->role, [
            RoleEnum::ADMIN, 
            RoleEnum::FISIOTERAPIS
        ]);
    }

    /**
     * Tentukan apakah user bisa menghapus rekam medis.
     */
    public function delete(User $user): bool
    {
        return in_array($user->role, [
            RoleEnum::ADMIN, 
            RoleEnum::OWNER
        ]);
    }
}
