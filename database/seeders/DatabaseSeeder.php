<?php

namespace Database\Seeders;

use App\Models\Doctor;
use App\Models\User;
use CouponSeeder;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;


class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory(100)->create();
        // Doctor::factory()->count(20)->create();

        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        $this->call([


        //      ClinicSeeder::class,

        //   DoctorSeeder::class,
        //      CategorySeeder::class,
        //     ProductSeeder::class,
        //     ServicesSeeder::class,
        //     CouponSeeder::class,
        //     HumanPostsSeeder::class,

        //    DoctorSeeder::class,
        //      CategorySeeder::class,
        //     ProductSeeder::class,
        //     ServicesSeeder::class,
            // CouponSeeder::class,
            // HumanPostsSeeder::class,


    //  ClinicSeeder::class,
    //  DoctorSeeder::class,
//    CategorySeeder::class,
//      ProductSeeder::class,
//   ServicesSeeder::class,
    //    HumanPostsSeeder::class,
          
    // FeedbackSeeder::class,


            // HumanPostsSeeder::class,


            ClinicSeeder::class,
            DoctorSeeder::class,
        CategorySeeder::class,
            ProductSeeder::class,
        ServicesSeeder::class,
                


        ]);
    }

}
