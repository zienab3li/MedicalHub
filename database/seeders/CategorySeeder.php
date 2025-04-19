<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        Category::create([
            'name' => 'Human Medicine',
            'type' => 'human',
            'description' => 'Medicines for human use'
        ]);

        Category::create([
            'name' => 'Veterinary Medicine',
            'type' => 'veterinary',
            'description' => 'Medicines for animal use'
        ]);

        Category::create([
            'name' => 'Medical Equipment',
            'type' => 'equipment',
            'description' => 'Medical devices and equipment'
        ]);
    }
}
