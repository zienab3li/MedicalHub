<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Skin Care',
                'description' => 'Products for skin care such as creams and lotions that maintain the health and nourishment of the skin.',
                'image' => 'Skin Care.jpg',
                'type' => 'human',
            ],
            [
                'name' => 'Hair Care',
                'description' => 'Products for hair care such as shampoos, oils, and masks that improve hair health.',
                'image' => 'Hair Care.jpg',
                'type' => 'human',
            ],
            [
                'name' => 'Body Care',
                'description' => 'Body care products like moisturizers, oils, and exfoliators to maintain smooth and healthy skin.',
                'image' => 'Body Care.jpg',
                'type' => 'human',
            ],
            [
                'name' => 'Baby Care',
                'description' => 'Products for baby care like diapers, shampoos, and oils suitable for baby skin.',
                'image' => 'Baby Care.jpg',
                'type' => 'human',
            ],
            [
                'name' => 'Elderly Care',
                'description' => 'Products for elderly care including medications, supplements, and comfort essentials.',
                'image' => 'Elderly Care.jpg',
                'type' => 'human',
            ],
            [
                'name' => 'Sunscreen',
                'description' => 'Products for sun protection such as sunscreens that protect the skin from harmful rays.',
                'image' => 'Sunscreen.jpg',
                'type' => 'human',
            ],
            [
                'name' => 'Foot Care',
                'description' => 'Foot care products such as creams, oils, and moisturizers to maintain foot health.',
                'image' => 'Foot Care.jpg',
                'type' => 'human',
            ],
            [
                'name' => 'Shower Products',
                'description' => 'Shower products such as gels, soaps, and shower creams to clean and moisturize the skin.',
                'image' => 'Shower Products.jpg',
                'type' => 'human',
            ],
            [
                'name' => 'Vitamins & Supplements',
                'description' => 'Dietary supplements and vitamins that help improve general health and prevent diseases.',
                'image' => 'Vitamins & Supplements.jpg',
                'type' => 'human',
            ],
            [
                'name' => 'First Aid',
                'description' => 'First aid tools such as bandages, antiseptics, and plasters for emergency care.',
                'image' => 'First Aid.jpg',
                'type' => 'human',
            ],
            [
                'name' => 'Home Medical Devices',
                'description' => 'Home medical devices such as blood pressure monitors and glucose meters to track health at home.',
                'image' => 'Home Medical Devices.jpg',
                'type' => 'human',
            ],
            [
                'name' => 'Medications & Treatments',
                'description' => 'Medications for treating various health conditions, including chronic diseases and treatments.',
                'image' => 'Medications & Treatments.jpg',
                'type' => 'human',
            ],
        ];

        foreach ($categories as $categoryData) {
            $sourcePath = public_path('images/categories/' . $categoryData['image']);
            $destinationPath = 'categories/' . $categoryData['image'];

            if (File::exists($sourcePath)) {
                Storage::disk('public')->put($destinationPath, File::get($sourcePath));
            }

            Category::create([
                'name' => $categoryData['name'],
                'description' => $categoryData['description'],
                'image' => $categoryData['image'], // Just the image name
                'type' => $categoryData['type'],
            ]);
        }

        
    }


    
    }

