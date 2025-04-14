<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Pharmacy;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        // Ensure a default pharmacy exists
        $pharmacy = Pharmacy::firstOrCreate(
            ['id' => 1],
            [
                'name' => 'Main Pharmacy',
                'location' => 'Downtown',
                'type' => 'human',
            ]
        );

        $products = [
            [
                'category_id' => 1,
                'pharmacy_id' => $pharmacy->id,
                'name' => 'Heart Monitor',
                'description' => 'Advanced heart rate and ECG monitoring device.',
                'image' => 'heart_monitor.jpg',
                'price' => 2499.99,
                'stock' => 10,
            ],
            [
                'category_id' => 2,
                'pharmacy_id' => $pharmacy->id,
                'name' => 'Pain Reliever',
                'description' => 'Effective relief for headaches and muscle pain.',
                'image' => 'pain_reliever.jpg',
                'price' => 19.99,
                'stock' => 200,
            ],
            [
                'category_id' => 1,
                'pharmacy_id' => $pharmacy->id,
                'name' => 'Blood Pressure Cuff',
                'description' => 'Digital monitor for measuring blood pressure.',
                'image' => 'bp_cuff.jpg',
                'price' => 89.99,
                'stock' => 50,
            ],
        ];

        foreach ($products as $product) {
            Product::create($product);
        }
    }
}
