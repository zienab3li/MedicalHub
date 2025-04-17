<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use App\Models\Clinic;

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
    }
}
