<?php

namespace Database\Seeders;

use App\Models\Clinic;
use App\Models\Doctor;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class DoctorSeeder extends Seeder
{
    public function run(): void
    {

        $doctors = [
            [
                'name' => 'Dr. Ahmed Hassan',
                'email' => 'ahmed.hassan@example.com',
                'password' => Hash::make('password123'),
                'specialization' => 'General Physician',
                'bio' => 'Experienced in general health and internal medicine.',
                'clinic_address' => '123 Main St, City A',
                'role' => 'human',
                'clinic_type' => 'clinic',
                'address' => 'City A',
                'phone' => '01234567890',
                'image' => 'doctor1.jpg',
                'clinic_id' => 1,
            ],
            [
                'name' => 'Dr. Sarah Youssef',
                'email' => 'sara.youssef@example.com',
                'password' => Hash::make('password123'),
                'specialization' => 'Dentist',
                'bio' => 'Expert in dental surgery and cosmetic dentistry.',
                'clinic_address' => '456 Smile Ave, City B',
                'role' => 'human',
                'clinic_type' => 'clinic',
                'address' => 'City B',
                'phone' => '01234567891',
                'image' => 'doctor2.jpg',
                'clinic_id' => 2,
            ],
            [
                'name' => 'Dr. Mostafa Nabil',
                'email' => 'mostafa.nabil@example.com',
                'password' => Hash::make('password123'),
                'specialization' => 'Ophthalmologist',
                'bio' => 'Providing advanced eye care and surgery.',
                'clinic_address' => '789 Vision Rd, City C',
                'role' => 'human',
                'clinic_type' => 'clinic',
                'address' => 'City C',
                'phone' => '01234567892',
                'image' => 'doctor3.jpg',
                'clinic_id' => 3,
            ],
            [
                'name' => 'Dr. Mariam Khaled',
                'email' => 'mariam.khaled@example.com',
                'password' => Hash::make('password123'),
                'specialization' => 'Pediatrician',
                'bio' => 'Specialist in child healthcare.',
                'clinic_address' => '101 Kids Lane, City D',
                'role' => 'human',
                'clinic_type' => 'clinic',
                'address' => 'City D',
                'phone' => '01234567893',
                'image' => 'doctor4.jpg',
                'clinic_id' => 4,
            ],
            [
                'name' => 'Dr. Amr Adel',
                'email' => 'amr.adel@example.com',
                'password' => Hash::make('password123'),
                'specialization' => 'Dermatologist',
                'bio' => 'Skin care and cosmetic treatment expert.',
                'clinic_address' => '202 Skin Blvd, City E',
                'role' => 'human',
                'clinic_type' => 'clinic',
                'address' => 'City E',
                'phone' => '01234567894',
                'image' => 'doctor5.jpg',
                'clinic_id' => 5,
            ],
            [
                'name' => 'Dr. Lina Gamal',
                  'clinic_type' => 'clinic',
                'email' => 'lina.gamal@example.com',
                'password' => Hash::make('password123'),
                'specialization' => 'Nutritionist',
                'bio' => 'Helps patients achieve optimal health through diet and lifestyle.',
                'clinic_address' => '12 Wellness St, City X',
                'role' => 'vet',
                'clinic_type' => 'vet',
                'address' => 'City X',
                'phone' => '01122334455',
                'image' => 'doctor6.jpg',
                'clinic_id' => 1,
            ],
            [
                'name' => 'Dr. Karim Elsaid',
                'email' => 'karim.elsaid@example.com',
                'password' => Hash::make('password123'),
                'specialization' => 'vetness Coach',
                'bio' => 'Certified personal trainer focused on strength and mobility.',
                'clinic_address' => '78 vet Zone, City Y',
                'role' => 'vet',
                'clinic_type' => 'vet',
                'address' => 'City Y',
                'phone' => '01099887766',
                'image' => 'doctor7.jpg',
                'clinic_id' => 2,
            ],
            [
                'name' => 'Dr. Yasmin Nader',
                'email' => 'yasmin.nader@example.com',
                'password' => Hash::make('password123'),
                'specialization' => 'Physiotherapist',
                'bio' => 'Expert in musculoskeletal rehabilitation and therapy.',
                'clinic_address' => '33 Rehab Center, City Z',
                'role' => 'vet',
                'clinic_type' => 'vet',

                'address' => 'City Z',
                'phone' => '01233445566',
                'image' => 'doctor8.jpg',
                'clinic_id' => 3,
            ],
            [
                'name' => 'Dr. Hossam Badr',
                'email' => 'hossam.badr@example.com',
                'password' => Hash::make('password123'),
                'specialization' => 'Sports Medicine',
                'bio' => 'Treating athletes and improving performance recovery.',
                'clinic_address' => '90 Sports Med Blvd, City M',
                'role' => 'vet',
                'clinic_type' => 'vet',

                'address' => 'City M',
                'phone' => '01555667788',
                'image' => 'doctor9.jpg',
                'clinic_id' => 2,
            ],
            [
                'name' => 'Dr. Nouran Adel',
                'email' => 'nouran.adel@example.com',
                'password' => Hash::make('password123'),
                'specialization' => 'Yoga Therapist',
                'bio' => 'Combining yoga and therapy for holistic healing.',
                'clinic_address' => '22 Harmony Way, City N',
                'role' => 'vet',
                'clinic_type' => 'vet',
                'address' => 'City N',
                'phone' => '01777888990',
                'image' => 'doctor10.jpg',
                'clinic_id' => 1,
            ],
        ];


        foreach ($doctors as $doctorData) {
            $originalPath = public_path('images/human/' . $doctorData['image']);
            $storagePath = 'doctors/' . $doctorData['image'];

            if (File::exists($originalPath)) {
                Storage::disk('public')->put($storagePath, File::get($originalPath));
            }

            Doctor::create([
                'name' => $doctorData['name'],
                'email' => $doctorData['email'],
                'password' => $doctorData['password'],
                'specialization' => $doctorData['specialization'],
                'bio' => $doctorData['bio'],
                'clinic_address' => $doctorData['clinic_address'],
                'role' => $doctorData['role'],
                'address' => $doctorData['address'],
                'phone' => $doctorData['phone'],
                'image' => $storagePath,
                'clinic_id' => $doctorData['clinic_id'],
                // 'clinic_type' => $doctorData['clinic_type'],
                'clinic_type' => $doctorData['clinic_type'] === 'clinic' ? \App\Models\Clinic::class : \App\Models\vet::class,
            ]);
            
        }
    }
}
