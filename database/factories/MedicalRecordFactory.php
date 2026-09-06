<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Patient;
use App\Models\Physiotherapist;
use App\Models\Appointment;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\MedicalRecord>
 */
class MedicalRecordFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'patient_id' => Patient::factory(),
            'physiotherapist_id' => Physiotherapist::factory(),
            'appointment_id' => Appointment::factory(),
            'examination_date' => $this->faker->date(),
            'complaint' => $this->faker->sentence(),
            'diagnosis' => $this->faker->sentence(),
            'treatment' => $this->faker->paragraph(),
            'notes' => $this->faker->paragraph(),
            'prescription' => $this->faker->words(3, true),
            'attachment' => null,
        ];
    }
}
