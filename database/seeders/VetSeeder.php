<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class VetSeeder extends Seeder
{
    public function run(): void
    {
        $vets = [
            [
                'name' => 'Al Shifa Vet Clinic',
                'description' => 'Specialized in general veterinary care for pets.',
                'image' => 'al_shifa_vet.jpg',
            ],
            [
                'name' => 'Dental Care Vet Clinic',
                'description' => 'Expert in dental surgery and care for animals.',
                'image' => 'dental_care_vet.jpg',
            ],
            [
                'name' => 'Vision Pet Care',
                'description' => 'Providing comprehensive eye care for animals.',
                'image' => 'vision_pet_care.jpg',
            ],
        ];

        foreach ($vets as $vet) {
            $originalPath = public_path('images/vets/' . $vet['image']);
            $storagePath = 'vets/' . $vet['image'];

            if (File::exists($originalPath)) {
                Storage::disk('public')->put($storagePath, File::get($originalPath));
            }

            DB::table('vets')->insert([
                'name' => $vet['name'],
                'description' => $vet['description'],
                'image' => 'storage/' . $storagePath,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
