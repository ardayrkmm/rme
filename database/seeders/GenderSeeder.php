<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Gender;

class GenderSeeder extends Seeder
{
    public function run(): void
    {
        $genders = [
            ['name' => 'L'],
            ['name' => 'P'],
        ];

        foreach ($genders as $gender) {
            Gender::firstOrCreate(['name' => $gender['name']], $gender);
        }
    }
}
