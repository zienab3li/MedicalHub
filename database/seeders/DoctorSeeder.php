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
                'address' => 'City A',
                'phone' => '01234567890',
                'image' => 'doctor1.jpg',
                'clinic_id' => 1,
            ],
            [
                'name' => 'Dr. Sara Youssef',
                'email' => 'sara.youssef@example.com',
                'password' => Hash::make('password123'),
                'specialization' => 'Dentist',
                'bio' => 'Expert in dental surgery and cosmetic dentistry.',
                'clinic_address' => '456 Smile Ave, City B',
                'role' => 'human',
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
                'address' => 'City E',
                'phone' => '01234567894',
                'image' => 'doctor5.jpg',
                'clinic_id' => 5,
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
            ]);
            
        }
    }
}
