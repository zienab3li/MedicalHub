<?php

namespace Database\Seeders;

use App\Models\Clinic;
use Illuminate\Database\Seeder;
use App\Models\Doctor;
use Illuminate\Support\Facades\Hash;
use App\Models\Vet;

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
                'clinic_type' => 'human',
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
                'clinic_type' => 'human',
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
                'clinic_type' => 'human',
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
                'clinic_type' => 'human',
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
                'clinic_type' => 'human',
            ],
            [
                'name' => 'Dr. Rania Fathy',
                'email' => 'rania.fathy@example.com',
                'password' => Hash::make('password123'),
                'specialization' => 'Veterinarian',
                'bio' => 'Animal health and surgery specialist.',
                'clinic_address' => '303 Pet St, City F',
                'role' => 'vet',
                'address' => 'City F',
                'phone' => '01234567895',
                'image' => 'doctor6.jpg',
                'clinic_type' => 'vet', 
            ],
        ];

        foreach ($doctors as $doctorData) {
            if ($doctorData['clinic_type'] == 'clinic') {
                $clinic = Clinic::find($doctorData['clinic_id']); 
            } else {
                $clinic = Vet::find($doctorData['clinic_id']); 
            }

            $doctorData['clinic_id'] = $clinic->id;

            Doctor::create($doctorData);
        }
    }
}
