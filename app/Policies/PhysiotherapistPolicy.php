<?php

namespace App\Policies;

use App\Models\User;
use App\Enums\RoleEnum;

class PhysiotherapistPolicy
{
    /**
     * Tentukan apakah user bisa melihat daftar physiotherapist.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Tentukan apakah user bisa melihat detail physiotherapist.
     */
    public function view(User $user): bool
    {
        return true;
    }

    /**
     * Tentukan apakah user bisa mengelola physiotherapist (Buat, Ubah, Hapus).
     * Hanya Admin yang diizinkan.
     */
    public function manage(User $user): bool
    {
        return $user->role === RoleEnum::ADMIN;
    }
}
