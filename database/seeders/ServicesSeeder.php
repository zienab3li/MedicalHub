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
            // --- Human Services (Home Visit) ---
            [
                'name' => 'Home Consultation',
                'description' => 'Consultation with a general physician at your home.',
                'image' => 'home_consultation.jpg',
                'price' => 120.00,
                'duration' => 40,
                'is_active' => true,
                'instructions' => 'Have a clean, quiet space prepared for the doctor.',
            ],
            [
                'name' => 'Home Blood Test',
                'description' => 'A nurse visits to collect blood samples for lab testing.',
                'image' => 'home_blood_test.jpg',
                'price' => 160.00,
                'duration' => 20,
                'is_active' => true,
                'instructions' => 'Do not eat or drink for 8 hours prior.',
            ],
            [
                'name' => 'Physiotherapy Session',
                'description' => 'In-home physical therapy session tailored to patient needs.',
                'image' => 'physiotherapy.jpg',
                'price' => 200.00,
                'duration' => 45,
                'is_active' => true,
                'instructions' => 'Wear comfortable clothes and ensure space to move.',
            ],
            [
                'name' => 'Elderly Care Visit',
                'description' => 'Routine wellness check for elderly patients at home.',
                'image' => 'elderly_care.jpg',
                'price' => 130.00,
                'duration' => 30,
                'is_active' => true,
                'instructions' => 'Make sure the patient’s medications are visible.',
            ],
            [
                'name' => 'Nutrition Counseling',
                'description' => 'In-person diet and health consultation at home.',
                'image' => 'nutrition_counseling.jpg',
                'price' => 100.00,
                'duration' => 30,
                'is_active' => true,
                'instructions' => 'Have any recent lab reports ready if available.',
            ],
        
            // --- Pet Services (Home Visit) ---
            [
                'name' => 'Pet Checkup at Home',
                'description' => 'Basic health examination for pets at your home.',
                'image' => 'pet_home_checkup.jpg',
                'price' => 110.00,
                'duration' => 25,
                'is_active' => true,
                'instructions' => 'Ensure your pet is calm and secure in one room.',
            ],
            [
                'name' => 'Pet Vaccination at Home',
                'description' => 'Get your cat or dog vaccinated without leaving the house.',
                'image' => 'pet_home_vaccination.jpg',
                'price' => 90.00,
                'duration' => 15,
                'is_active' => true,
                'instructions' => 'Have your pet’s vaccination record available.',
            ],
            [
                'name' => 'Pet Grooming at Home',
                'description' => 'Bath, brushing, and nail trimming done at your place.',
                'image' => 'pet_home_grooming.jpg',
                'price' => 85.00,
                'duration' => 35,
                'is_active' => true,
                'instructions' => 'Provide a sink or tub with warm water access.',
            ],
            [
                'name' => 'Pet Behavior Consultation',
                'description' => 'In-home evaluation of pet behavior by a specialist.',
                'image' => 'pet_behavior_consult.jpg',
                'price' => 140.00,
                'duration' => 40,
                'is_active' => true,
                'instructions' => 'Observe and note the behavior you want to address.',
            ],
            [
                'name' => 'Pet Nutrition Advice',
                'description' => 'Personalized diet planning for your pet.',
                'image' => 'pet_nutrition.jpg',
                'price' => 95.00,
                'duration' => 30,
                'is_active' => true,
                'instructions' => 'Have your pet’s current food and medical history available.',
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
