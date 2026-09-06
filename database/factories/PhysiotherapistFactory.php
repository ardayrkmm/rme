<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Clinic;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Physiotherapist>
 */
class PhysiotherapistFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'clinic_id' => Clinic::factory(),
            'name' => $this->faker->name(),
            'sip' => $this->faker->unique()->numerify('SIP-####-####'),
            'phone' => $this->faker->phoneNumber(),
            'email' => $this->faker->unique()->safeEmail(),
            'address' => $this->faker->address(),
            'gender' => $this->faker->randomElement(['L', 'P']),
            'photo' => null,
            'status' => 'active',
        ];
    }
}
