<?php

namespace App\Policies;

use App\Models\User;
use App\Enums\RoleEnum;

class AppointmentPolicy
{
    /**
     * Tentukan apakah user bisa melihat daftar appointment.
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
     * Tentukan apakah user bisa melihat detail appointment.
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
     * Tentukan apakah user bisa mengelola appointment (Buat, Ubah, Hapus).
     */
    public function manage(User $user): bool
    {
        return in_array($user->role, [
            RoleEnum::ADMIN, 
            RoleEnum::FISIOTERAPIS,
            RoleEnum::OWNER,
            RoleEnum::STAFF
        ]);
    }

    public function adminOnly(User $user): bool
    {
        return in_array($user->role, [
            RoleEnum::ADMIN, 
            RoleEnum::OWNER
        ]);
    }
}
