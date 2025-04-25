<?php

namespace Database\Seeders;

use App\Models\Doctor;
use App\Models\Post;
use App\Models\Section;
use Illuminate\Database\Seeder;
use Faker\Generator as Faker;

class HumanPostsSeeder extends Seeder
{
    public function run(): void
    {
        $faker = \Faker\Factory::create();

        // جيب الدكاترة البشريين (role = 'human')
        $humanDoctors = Doctor::where('role', 'human')->get();

        // لو مافيش دكاترة بشريين، أطلع رسالة تحذير
        if ($humanDoctors->isEmpty()) {
            echo "No human doctors found. Please run DoctorSeeder first.\n";
            return;
        }

        // بوستات للدكاترة البشريين
        foreach ($humanDoctors as $doctor) {
            for ($i = 0; $i < 3; $i++) { // 3 بوستات لكل دكتور
                $post = Post::create([
                    'doctor_id' => $doctor->id,
                    'title' => $this->getHumanPostTitle($doctor->specialization, $faker),
                    'content' => $this->getHumanPostContent($doctor->specialization, $faker),
                    'image' => $faker->boolean(50) ? 'posts/human_post_' . $faker->numberBetween(1, 10) . '.jpg' : null,
                    'role' => 'human', // أضفنا role صراحة
                    'created_at' => $faker->dateTimeBetween('-1 year', 'now'),
                    'updated_at' => $faker->dateTimeBetween('-1 year', 'now'),
                ]);

                // إضافة sections عشوائية (1-3 sections لكل بوست)
                for ($j = 0; $j < $faker->numberBetween(1, 3); $j++) {
                    Section::create([
                        'post_id' => $post->id,
                        'title' => $faker->sentence(4),
                        'content' => $faker->paragraph(2),
                        'image' => $faker->boolean(30) ? 'sections/section_' . $faker->numberBetween(1, 5) . '.jpg' : null,
                        'created_at' => $post->created_at,
                        'updated_at' => $post->updated_at,
                    ]);
                }
            }
        }
    }

    /**
     * Generate a post title based on doctor's specialization
     */
    private function getHumanPostTitle(string $specialization, Faker $faker): string
    {
        $titles = [
            'General Physician' => [
                'Tips for a Healthy Lifestyle',
                'Understanding Common Cold Symptoms',
                'How to Manage Stress Effectively',
            ],
            'Dentist' => [
                'Best Practices for Oral Hygiene',
                'What to Know About Teeth Whitening',
                'Preventing Cavities in Children',
            ],
            'Ophthalmologist' => [
                'Protecting Your Eyes from Screen Fatigue',
                'Common Eye Conditions Explained',
                'The Importance of Regular Eye Checkups',
            ],
            'Pediatrician' => [
                'Nutrition Tips for Growing Kids',
                'Common Childhood Illnesses',
                'Vaccination Schedules for Children',
            ],
            'Dermatologist' => [
                'Skincare Routines for All Skin Types',
                'How to Treat Acne Effectively',
                'Protecting Your Skin from Sun Damage',
            ],
        ];

        return $titles[$specialization][array_rand($titles[$specialization])] ?? $faker->sentence(6);
    }

    /**
     * Generate post content based on doctor's specialization
     */
    private function getHumanPostContent(string $specialization, Faker $faker): string
    {
        $contents = [
            'General Physician' => 'Maintaining a healthy lifestyle involves regular exercise, a balanced diet, and adequate sleep. Here are some practical tips to help you stay healthy...',
            'Dentist' => 'Proper oral hygiene is crucial for preventing dental issues. Brushing twice a day, flossing, and regular dental checkups can keep your smile bright...',
            'Ophthalmologist' => 'Prolonged screen time can lead to eye strain. Follow the 20-20-20 rule: every 20 minutes, look at something 20 feet away for 20 seconds...',
            'Pediatrician' => 'Children need proper nutrition to support their growth. Ensure they get enough vitamins, minerals, and proteins through a balanced diet...',
            'Dermatologist' => 'A good skincare routine starts with cleansing, moisturizing, and sun protection. Avoid harsh products and consult a dermatologist for persistent issues...',
        ];

        return $contents[$specialization] ?? $faker->paragraphs(3, true);
    }
}