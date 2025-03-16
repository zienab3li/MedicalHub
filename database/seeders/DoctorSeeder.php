<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\Doctor;
use App\Models\Clinic;

class DoctorSeeder extends Seeder
{
    public function run(): void
    {
        $clinic = Clinic::first(); 

        if (!$clinic) {
            $clinic = Clinic::create([
                'name' => 'Default Clinic',
                'address' => '123 Main Street',
                'phone' => '01012345678',
            ]);
        }

  
        Doctor::create([
            'name' => 'Dr. Ahmed Ali',
            'email' => 'ahmed@example.com',
            'password' => Hash::make('password123'),
            'clinic_id' => $clinic->id,
            'specialization' => 'Cardiologist',
            'bio' => 'Experienced heart specialist with over 10 years in the field.',
            'clinic_address' => $clinic->address,
            'role' => 'human',
            'address' => 'Cairo, Egypt',
            'phone' => '01234567890',
            'image' => 'doctors/ahmed.jpg', 
        ]);

        Doctor::create([
            'name' => 'Dr. Sara Mohamed',
            'email' => 'sara@example.com',
            'password' => Hash::make('password123'),
            'clinic_id' => $clinic->id,
            'specialization' => 'Dermatologist',
            'bio' => 'Expert in skincare and laser treatments.',
            'clinic_address' => $clinic->address,
            'role' => 'human',
            'address' => 'Alexandria, Egypt',
            'phone' => '01123456789',
            'image' => 'doctors/sara.jpg',
        ]);
    }
}
