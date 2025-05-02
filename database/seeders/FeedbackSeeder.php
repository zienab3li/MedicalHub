<?php

namespace Database\Seeders;

use App\Models\Feedback;
use App\Models\User;
use App\Models\Doctor;
use Illuminate\Database\Seeder;

class FeedbackSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::first(); // Get the only user you have
        $doctors = Doctor::inRandomOrder()->take(5)->get();

        foreach (range(1, 20) as $i) {
            $type = fake()->randomElement(['doctor', 'website']);

            Feedback::create([
                'user_id' => $user->id,
                'doctor_id' => $type === 'doctor' && $doctors->isNotEmpty() ? $doctors->random()->id : null,
                'type' => $type,
                'rating' => fake()->numberBetween(1, 5),
                'comment' => fake()->optional()->sentence(12),
            ]);
        }
    }
}
