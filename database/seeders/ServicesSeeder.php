<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Service;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class ServicesSeeder extends Seeder
{
    public function run(): void
    {
        $services = [
            [
                'name' => 'General Consultation',
                'description' => 'Consultation with a general physician.',
                'image' => 'general_consultation.jpg',
                'price' => 100.00,
                'duration' => 30,
                'is_active' => true,
                'instructions' => 'Please arrive 10 minutes early and bring your ID.',
            ],
            [
                'name' => 'Blood Test',
                'description' => 'Standard blood analysis to detect infections and conditions.',
                'image' => 'blood_test.jpg',
                'price' => 150.00,
                'duration' => 20,
                'is_active' => true,
                'instructions' => 'Do not eat or drink anything 8 hours before the test.',
            ],
            [
                'name' => 'X-Ray',
                'description' => 'Radiographic imaging for internal diagnosis.',
                'image' => 'xray.jpg',
                'price' => 200.00,
                'duration' => 15,
                'is_active' => true,
                'instructions' => 'Wear comfortable clothing and remove any metal items.',
            ],
        ];

        foreach ($services as $serviceData) {
            $originalPath = public_path('images/services/' . $serviceData['image']);
            $storagePath = 'services/' . $serviceData['image'];

            if (File::exists($originalPath)) {
                Storage::disk('public')->put($storagePath, File::get($originalPath));
            }

            Service::create([
                'name' => $serviceData['name'],
                'description' => $serviceData['description'],
                'image' => $storagePath,
                'price' => $serviceData['price'],
                'duration' => $serviceData['duration'],
                'is_active' => $serviceData['is_active'],
                'instructions' => $serviceData['instructions'],
            ]);
        }
    }
}
