<?php

namespace Database\Factories;

use App\Models\Doctor;
use App\Models\Post;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Post>
 */
class PostFactory extends Factory
{
    public function definition(): array
    {
        return [
            'doctor_id' => Doctor::inRandomOrder()->first()?->id ?? Doctor::factory(),
            'title' => $this->faker->sentence(),
            'content' => $this->faker->paragraph(5),
        ];
    }
}
