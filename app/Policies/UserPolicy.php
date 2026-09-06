<?php

namespace App\Policies;

use App\Models\User;
use App\Enums\RoleEnum;

class UserPolicy
{
    /**
     * Determine if the given user can manage users.
     */
    public function manage(User $user): bool
    {
        return in_array($user->role, [
            RoleEnum::ADMIN,
            RoleEnum::OWNER
        ]);
    }
}
