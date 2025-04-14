<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $products = [
            [
                'category_id' => 1,
                'name' => 'Heart Monitor',
                'description' => 'Advanced heart rate and ECG monitoring device.',
                'image' => 'heart_monitor.jpg',
                'price' => 2499.99,
                'stock' => 10,
            ],
            [
                'category_id' => 2,
                'name' => 'Dental Kit',
                'description' => 'Professional dental care kit for home use.',
                'image' => 'dental_kit.jpg',
                'price' => 399.50,
                'stock' => 25,
            ],
            [
                'category_id' => 3,
                'name' => 'Baby Thermometer',
                'description' => 'Infrared thermometer for babies and children.',
                'image' => 'baby_thermometer.jpg',
                'price' => 149.99,
                'stock' => 15,
            ],
            [
                'category_id' => 4,
                'name' => 'Skin Care Cream',
                'description' => 'Dermatologically tested cream for sensitive skin.',
                'image' => 'skin_care.jpg',
                'price' => 89.00,
                'stock' => 30,
            ],
            [
                'category_id' => 5,
                'name' => 'Neuro Stimulator',
                'description' => 'Used in neurological rehabilitation.',
                'image' => 'neuro_stimulator.jpg',
                'price' => 5299.00,
                'stock' => 5,
            ],
            [
                'category_id' => 6,
                'name' => 'Pet Vaccine Pack',
                'description' => 'Full vaccine kit for dogs and cats.',
                'image' => 'pet_vaccine.jpg',
                'price' => 299.99,
                'stock' => 20,
            ],
        ];

        foreach ($products as $product) {
            Product::create($product);
        }
    }
}
