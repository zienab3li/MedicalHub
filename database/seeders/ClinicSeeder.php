<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use App\Models\Clinic;
use App\Models\Vet;

class ClinicSeeder extends Seeder
{
    public function run(): void
    {
        $clinics = [
            
            [
                'name' => 'Dental Care',
                'description' => 'Specialized in dental services.',
                'image_name' => 'dental_care.jpg',
            ],
            [
                'name' => 'Eye Center',
                'description' => 'Complete eye care solutions.',
                'image_name' => 'eye_center.jpg',
            ],
            [
                'name' => 'Pediatric Clinic',
                'description' => 'Health services for children.',
                'image_name' => 'pediatric.jpg',
            ],
            [
                'name' => 'Dermatology Clinic',
                'description' => 'Skin treatment and care.',
                'image_name' => 'dermatology.jpg',
            ],
            [
                'name' => 'Women\'s Health',
                'description' => 'Obstetrics and gynecology.',
                'image_name' => 'women_health.jpg',
            ],
        ];

        foreach ($clinics as $clinicData) {
            $originalPath = public_path('images/clinics/' . $clinicData['image_name']);

            $storagePath = 'clinics/' . $clinicData['image_name'];

            if (File::exists($originalPath)) {
                Storage::disk('public')->put($storagePath, File::get($originalPath));
            }

            Clinic::create([
                'name' => $clinicData['name'],
                'description' => $clinicData['description'],
                'image' => $storagePath, 
            ]);
        }

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

            Vet::create([
                'name' => $vet['name'],
                'description' => $vet['description'],
                'image' => $storagePath,
            ]);
        }
    }
}
