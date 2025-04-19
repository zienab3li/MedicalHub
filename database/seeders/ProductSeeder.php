<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $humanCategory = Category::where('type', 'human')->first();
        $veterinaryCategory = Category::where('type', 'veterinary')->first();
        $equipmentCategory = Category::where('type', 'equipment')->first();

        // Human Medicine Products
        Product::create([
            'name' => 'Paracetamol 500mg',
            'description' => 'Pain reliever and fever reducer',
            'price' => 5.99,
            'stock' => 100,
            'category_id' => $humanCategory->id,
            'image' => 'paracetamol.jpg'
        ]);

        Product::create([
            'name' => 'Ibuprofen 400mg',
            'description' => 'Anti-inflammatory and pain reliever',
            'price' => 7.99,
            'stock' => 80,
            'category_id' => $humanCategory->id,
            'image' => 'ibuprofen.jpg'
        ]);

        // Veterinary Medicine Products
        Product::create([
            'name' => 'Dog Flea Treatment',
            'description' => 'Monthly flea prevention for dogs',
            'price' => 24.99,
            'stock' => 50,
            'category_id' => $veterinaryCategory->id,
            'image' => 'flea_treatment.jpg'
        ]);

        // Medical Equipment
        Product::create([
            'name' => 'Digital Thermometer',
            'description' => 'Fast and accurate temperature measurement',
            'price' => 12.99,
            'stock' => 30,
            'category_id' => $equipmentCategory->id,
            'image' => 'thermometer.jpg'
        ]);

        Product::create([
            'name' => 'Blood Pressure Monitor',
            'description' => 'Automatic blood pressure measurement device',
            'price' => 49.99,
            'stock' => 20,
            'category_id' => $equipmentCategory->id,
            'image' => 'bp_monitor.jpg'
        ]);
    }
}
