<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Enums\RoleEnum;

class RoleAndAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Buat Super Admin
        User::updateOrCreate(
            ['email' => 'admin@klinik.com'],
            [
                'name'               => 'Admin Klinik',
                'password'           => 'password123', // cast 'hashed' pada model User akan meng-hash otomatis
                'role'               => RoleEnum::ADMIN,
                'email_verified_at'  => now(),
            ]
        );

        // Buat sampel user untuk role lain (opsional, untuk testing)
        User::updateOrCreate(
            ['email' => 'fisioterapis@klinik.com'],
            [
                'name'               => 'Fisioterapis',
                'password'           => 'password123', // cast 'hashed' pada model User akan meng-hash otomatis
                'role'               => RoleEnum::FISIOTERAPIS,
                'email_verified_at'  => now(),
            ]
        );
    }
}
