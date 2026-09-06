<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Patient;
use App\Models\Physiotherapist;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Appointment>
 */
class AppointmentFactory extends Factory
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
            'appointment_date' => $this->faker->dateTimeBetween('+1 days', '+1 month')->format('Y-m-d'),
            'appointment_time' => $this->faker->time('H:i:s'),
            'status' => 'pending',
            'complaint' => $this->faker->sentence(),
            'notes' => null,
        ];
    }
}
