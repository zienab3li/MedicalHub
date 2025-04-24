<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $products = [
            [
                'category_id' => 1, 
                'name' => 'aloe vera gel',
                'description' => 'جل الصبار الطبيعي 99% لترطيب وتهدئة البشرة، مناسب لجميع أنواع البشرة',
                'image' => 'aloe vera gel.png',
                'price' => 35.99,
                'stock' => 150,
            ],
            [
                'category_id' => 1,
                'name' => 'Vitamin C Serum',
                'description' => 'مصل فيتامين سي بنسبة 20% لإشراقة البشرة وتوحيد اللون وتقليل التجاعيد',
                'image' => 'Vitamin C Serum.png',
                'price' => 89.99,
                'stock' => 75,
            ],
            [
                'category_id' => 1,
                'name' => 'Foaming Facial Cleanser',
                'description' => 'غسول وجه رغوي لطيف ينظف بعمق دون تجفيف البشرة، خالي من الصابون',
                'image' => 'Foaming Facial Cleanser.png',
                'price' => 45.50,
                'stock' => 0,
            ],
            [
                'category_id' => 1,
                'name' => 'Hydrating Facial Cream',
                'description' => 'كريم مرطب غني بالهيالورونيك أسيد للحفاظ على ترطيب البشرة 24 ساعة',
                'image' => 'Hydrating Facial Cream.png',
                'price' => 65.00,
                'stock' => 90,
            ],
            [
                'category_id' => 1,
                'name' => 'Micellar Water',
                'description' => 'ماء ميسيلار لإزالة الماكياج بلطف وتنظيف البشرة دون الحاجة للشطف',
                'image' => 'Micellar Water.png',
                'price' => 32.99,
                'stock' => 200,
            ],
            [
                'category_id' => 1,
                'name' => 'Sunscreen SPF 50+',
                'description' => 'واقي شمس واسع الطيف خفيف الوزن غير لزج للحماية اليومية من الأشعة فوق البنفسجية',
                'image' => 'Sunscreen.png',
                'price' => 55.00,
                'stock' => 110,
            ],
            [
                'category_id' => 1,
                'name' => 'Night Repair Cream',
                'description' => 'كريم ليلي غني بمضادات الأكسدة لتجديد البشرة أثناء النوم',
                'image' => 'Night Repair Cream.png',
                'price' => 78.50,
                'stock' => 25,
            ],
            [
                'category_id' => 1,
                'name' => 'Exfoliating Facial Scrub',
                'description' => 'مقشر وجه ناعم بحبيقات الجوجوبي لإزالة الخلايا الميتة وتحفيز تجديد البشرة',
                'image' => 'Exfoliating Facial Scrub.png',
                'price' => 42.99,
                'stock' => 85,
            ],
            [
                'category_id' => 1,
                'name' => 'Under Eye Cream',
                'description' => 'كريم خاص لمنطقة تحت العينين غني بالكافيين لتقليل الهالات والانتفاخات',
                'image' => 'Under Eye Cream.png',
                'price' => 59.99,
                'stock' => 0,
            ],
            [
                'category_id' => 1,
                'name' => 'Facial Toner',
                'description' => 'تونر لطيف يعيد توازن البشرة ويضيق المسام بعد التنظيف',
                'image' => 'Facial Toner.png',
                'price' => 28.50,
                'stock' => 130,
            ],
            [
                'category_id' => 1,
                'name' => 'Moisturizing Sheet Mask',
                'description' => 'قناع ورقي غني بمصل الترطيب للاستخدام الفوري لنتائج فورية',
                'image' => 'Moisturizing Sheet Mask.png',
                'price' => 15.99,
                'stock' => 180,
            ],
            [
                'category_id' => 1,
                'name' => 'vaseline',
                'description' => 'فازلين نقي لترطيب البشرة الجافة والشفتين، مناسب للبشرة الحساسة',
                'image' => 'vaseline.png',
                'price' => 12.99,
                'stock' => 250,
            ],
        ];

        foreach ($products as $product) {

            $sourcePath = public_path('images/products/' . $product['image']);
            $destinationPath = 'products/' . $product['image'];
            if (file_exists($sourcePath)) {
                Storage::disk('public')->put($destinationPath, File::get($sourcePath));
            }
            Product::create([
                'category_id' => $product['category_id'],
                'name' => $product['name'],
                'description' => $product['description'],
                'image' => $destinationPath,
                'price' => $product['price'],
                'stock' => $product['stock'],
            ]);
        }
    }
    
}
