<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Cardiology',
                'description' => 'Heart-related treatments and checkups.',
                'image' => 'cardiology.jpg',
            ],
            [
                'name' => 'Dentistry',
                'description' => 'Dental care and oral surgery.',
                'image' => 'dentistry.jpg',
            ],
            [
                'name' => 'Pediatrics',
                'description' => 'Child health and diseases.',
                'image' => 'pediatrics.jpg',
            ],
            [
                'name' => 'Dermatology',
                'description' => 'Skin care and treatment.',
                'image' => 'dermatology.jpg',
            ],
            [
                'name' => 'Neurology',
                'description' => 'Nervous system diagnosis and treatment.',
                'image' => 'neurology.jpg',
            ],
            [
                'name' => 'Veterinary',
                'description' => 'Animal healthcare and surgery.',
                'image' => 'veterinary.jpg',
            ],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
