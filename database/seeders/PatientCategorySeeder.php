<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PatientCategory;

class PatientCategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Ny'],
            ['name' => 'Tn'],
            ['name' => 'An'],
        ];

        foreach ($categories as $category) {
            PatientCategory::firstOrCreate(['name' => $category['name']], $category);
        }
    }
}
