<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

class DoctorFactory extends Factory
{
    public function definition(): array
    {
        static $doctorCount = 0;
        $doctorCount++;

        $clinicId = intdiv($doctorCount - 1, 5) + 1;

        return [
            'name' => 'Dr. ' . $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'password' => Hash::make('password123'),
            'specialization' => $this->faker->randomElement([
                'General Physician', 'Dentist', 'Pediatrician', 'Ophthalmologist', 'Dermatologist',
                'Cardiologist', 'Neurologist', 'Nutritionist', 'Psychiatrist', 'Orthopedic'
            ]),
            'bio' => $this->faker->sentence(10),
            'clinic_address' => $this->faker->address(),
            'role' => 'human',
            'clinic_type' => 'clinic',
            'address' => $this->faker->city(),
            'phone' => '01' . $this->faker->numerify('#########'),
            'image' => 'doctor' . $doctorCount . '.jpg',
            'clinic_id' => $clinicId,
        ];
    }
}
