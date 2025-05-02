<?php

namespace Database\Seeders;

use App\Models\Coupon;
use Illuminate\Database\Seeder;
use App\Models\Service;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Carbon\Carbon;


class ServicesSeeder extends Seeder
{
    public function run(): void
    {

        Coupon::create([
            'code' => 'AZDISCOUNT10',
            'discount_type' => 'percentage',
            'discount_value' => 10,
            'usage_limit' => 100,
            'used_times' => 0,
            'expires_at' => Carbon::now()->addDays(30),
            'is_active' => true,
        ]);

        Coupon::create([
            'code' => 'FLAT50',
            'discount_type' => 'fixed',
            'discount_value' => 50,
            'usage_limit' => 50,
            'used_times' => 0,
            'expires_at' => Carbon::now()->addDays(10),
            'is_active' => true,
        ]);

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
