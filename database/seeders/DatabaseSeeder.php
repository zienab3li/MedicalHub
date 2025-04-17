<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use VetSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        $this->call([
            // ClinicSeeder::class,
            // DoctorSeeder::class,

            // CategorySeeder::class,
            // ProductSeeder::class,
            // ServicesSeeder::class

            // CategorySeeder::class,
            // ProductSeeder::class,
            // ServicesSeeder::class,
            // CartSeeder::class,
            VetSeeder::class,

        ]);
    }

}
