<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Models\Cart;
use App\Models\Product;

class CartSeeder extends Seeder
{
    public function run(): void
    {
        $products = Product::take(6)->get();

        if ($products->isEmpty()) {
            $this->command->warn('No products found. Please seed products before seeding the cart.');
            return;
        }

        foreach ($products as $index => $product) {
            Cart::create([
                'id' => Str::uuid(),
                'cookie_id' => Str::uuid(),
                'user_id' => null, // أو حط رقم مستخدم لو عايز
                'product_id' => $product->id,
                'quantity' => rand(1, 5),
                'option' => json_encode([
                    'size' => ['S', 'M', 'L', 'XL'][rand(0, 3)],
                    'color' => ['red', 'blue', 'green', 'black'][rand(0, 3)],
                ]),
            ]);
        }
    }
}
