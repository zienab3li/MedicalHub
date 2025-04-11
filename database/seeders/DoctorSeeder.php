<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Doctor;
use Illuminate\Support\Facades\Hash;


class DoctorSeeder extends Seeder
{
    public function run(): void
    {
        $doctors = [
            [
                'name' => 'Dr. Ahmed Hassan',
                'email' => 'ahmed.hassan@example.com',
                'password' => Hash::make('password123'),
                'clinic_id' => 5,
                'specialization' => 'General Physician',
                'bio' => 'Experienced in general health and internal medicine.',
                'clinic_address' => '123 Main St, City A',
                'role' => 'human',
                'address' => 'City A',
                'phone' => '01234567890',
                'image' => 'doctor1.jpg',
            ],
            [
                'name' => 'Dr. Sara Youssef',
                'email' => 'sara.youssef@example.com',
                'password' => Hash::make('password123'),
                'clinic_id' => 6,
                'specialization' => 'Dentist',
                'bio' => 'Expert in dental surgery and cosmetic dentistry.',
                'clinic_address' => '456 Smile Ave, City B',
                'role' => 'human',
                'address' => 'City B',
                'phone' => '01234567891',
                'image' => 'doctor2.jpg',
            ],
            [
                'name' => 'Dr. Mostafa Nabil',
                'email' => 'mostafa.nabil@example.com',
                'password' => Hash::make('password123'),
                'clinic_id' => 3,
                'specialization' => 'Ophthalmologist',
                'bio' => 'Providing advanced eye care and surgery.',
                'clinic_address' => '789 Vision Rd, City C',
                'role' => 'human',
                'address' => 'City C',
                'phone' => '01234567892',
                'image' => 'doctor3.jpg',
            ],
            [
                'name' => 'Dr. Mariam Khaled',
                'email' => 'mariam.khaled@example.com',
                'password' => Hash::make('password123'),
                'clinic_id' => 6,
                'specialization' => 'Pediatrician',
                'bio' => 'Specialist in child healthcare.',
                'clinic_address' => '101 Kids Lane, City D',
                'role' => 'human',
                'address' => 'City D',
                'phone' => '01234567893',
                'image' => 'doctor4.jpg',
            ],
            [
                'name' => 'Dr. Amr Adel',
                'email' => 'amr.adel@example.com',
                'password' => Hash::make('password123'),
                'clinic_id' => 5,
                'specialization' => 'Dermatologist',
                'bio' => 'Skin care and cosmetic treatment expert.',
                'clinic_address' => '202 Skin Blvd, City E',
                'role' => 'human',
                'address' => 'City E',
                'phone' => '01234567894',
                'image' => 'doctor5.jpg',
            ],
            [
                'name' => 'Dr. Rania Fathy',
                'email' => 'rania.fathy@example.com',
                'password' => Hash::make('password123'),
                'clinic_id' => 6,
                'specialization' => 'Veterinarian',
                'bio' => 'Animal health and surgery specialist.',
                'clinic_address' => '303 Pet St, City F',
                'role' => 'vet',
                'address' => 'City F',
                'phone' => '01234567895',
                'image' => 'doctor6.jpg',
            ],
        ];

        foreach ($doctors as $doctor) {
            Doctor::create($doctor);
        }
    }
}
