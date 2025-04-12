<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Clinic;

class ClinicSeeder extends Seeder
{
    public function run(): void
    {
        $clinics = [
            ['name' => 'Al Shifa Clinic', 'description' => 'General medicine clinic.'],
            ['name' => 'Dental Care', 'description' => 'Specialized in dental services.'],
            ['name' => 'Eye Center', 'description' => 'Complete eye care solutions.'],
            ['name' => 'Pediatric Clinic', 'description' => 'Health services for children.'],
            ['name' => 'Dermatology Clinic', 'description' => 'Skin treatment and care.'],
            ['name' => 'Women\'s Health', 'description' => 'Obstetrics and gynecology.'],
        ];

        foreach ($clinics as $clinic) {
            Clinic::create($clinic);
        }
    }
}
