<?php

namespace App\Enums;

enum RoleEnum: string
{
    case ADMIN = 'admin';
    case OWNER = 'owner';
    case STAFF = 'staff';
    case FISIOTERAPIS = 'fisioterapis';
    case PASIEN = 'pasien';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
