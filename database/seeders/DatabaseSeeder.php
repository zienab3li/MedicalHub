<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;


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

             //ClinicSeeder::class,
           // DoctorSeeder::class,
             //CategorySeeder::class,
            //ProductSeeder::class,
            // ServicesSeeder::class,
           // CartSeeder::class,


           // ClinicSeeder::class,
            //DoctorSeeder::class,
           // CategorySeeder::class,
           // ProductSeeder::class,
            //ServicesSeeder::class,
            //CartSeeder::class,
            HumanPostsSeeder::class,
          
            //HumanCommentsSeeder::class,
            //HumanSectionsSeeder::class,
            //AdminPostsSeeder::class,
            //AdminCommentsSeeder::class,
            //AdminSectionsSeeder::class,
            //AdminUsersSeeder::class,
            //AdminDoctorsSeeder::class,
            //AdminClinicsSeeder::class,
            //AdminCategoriesSeeder::class,
            //AdminProductsSeeder::class,
            //AdminServicesSeeder::class,

        ]);
    }

}
