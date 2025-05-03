<?php

namespace Database\Seeders;

use App\Models\Doctor;
use App\Models\Post;
use App\Models\Section;
use Illuminate\Database\Seeder;
use Faker\Generator as Faker;

class VetPostsSeeder extends Seeder
{
    public function run(): void
    {
        $faker = \Faker\Factory::create();

        // Get vet doctors (role = 'vet')
        $vetDoctors = Doctor::where('role', 'vet')->get();

        // If no vet doctors found, show warning
        if ($vetDoctors->isEmpty()) {
            echo "No vet doctors found. Please run DoctorSeeder first.\n";
            return;
        }

        // Create posts for vet doctors
        foreach ($vetDoctors as $doctor) {
            for ($i = 0; $i < 3; $i++) { // 3 posts per vet
                $post = Post::create([
                    'doctor_id' => $doctor->id,
                    'title' => $this->getVetPostTitle($doctor->specialization, $faker),
                    'content' => $this->getVetPostContent($doctor->specialization, $faker),
                    'image' => $faker->boolean(50) ? 'posts/vet_post_' . $faker->numberBetween(1, 10) . '.jpg' : null,
                    'role' => 'vet',
                    'created_at' => $faker->dateTimeBetween('-1 year', 'now'),
                    'updated_at' => $faker->dateTimeBetween('-1 year', 'now'),
                ]);

                // Add random sections (1-3 sections per post)
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
     * Generate a post title based on vet's specialization
     */
    private function getVetPostTitle(string $specialization, Faker $faker): string
    {
        $titles = [
            'Small Animal' => [
                'Essential Care for Your Dog',
                'Feline Nutrition Guide',
                'Common Small Animal Health Issues',
                'Preventive Care for Pets',
            ],
            'Large Animal' => [
                'Farm Animal Health Management',
                'Equine Care Essentials',
                'Livestock Vaccination Schedules',
                'Large Animal Nutrition Tips',
            ],
            'Exotic Animals' => [
                'Caring for Reptile Pets',
                'Avian Health and Nutrition',
                'Special Needs of Exotic Pets',
                'Creating Ideal Habitats for Exotics',
            ],
            'Avian' => [
                'Bird Health Checkups',
                'Common Avian Diseases',
                'Proper Nutrition for Pet Birds',
                'Understanding Bird Behavior',
            ],
            'Equine' => [
                'Horse Dental Care',
                'Equine Vaccination Guide',
                'Managing Horse Nutrition',
                'Common Equine Lameness Issues',
            ],
        ];
    
        if (!isset($titles[$specialization])) {
            return $faker->sentence(6);
        }
    
        return $titles[$specialization][array_rand($titles[$specialization])];
    }
    /**
     * Generate post content based on vet's specialization
     */
    private function getVetPostContent(string $specialization, Faker $faker): string
    {
        $contents = [
            'Small Animal' => 'Proper care for small animals includes regular checkups, balanced nutrition, and preventive treatments. Small pets like dogs and cats have specific needs that owners should be aware of...',
            'Large Animal' => 'Large animals require specialized care including proper housing, nutrition, and health management. Regular veterinary visits are crucial for maintaining the health of livestock and farm animals...',
            'Exotic Animals' => 'Exotic pets have unique requirements that differ from traditional pets. Their specialized diets, habitat needs, and health concerns require expert knowledge and care...',
            'Avian' => 'Birds are sensitive creatures that require specific environmental conditions and diets. Understanding avian health signs can help prevent serious illnesses in your feathered friends...',
            'Equine' => 'Horses need regular dental care, proper hoof maintenance, and balanced nutrition. Equine health management involves understanding their unique physiology and behavior...',
        ];

        return $contents[$specialization] ?? $faker->paragraphs(3, true);
    }
}