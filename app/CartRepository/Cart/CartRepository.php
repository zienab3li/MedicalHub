<?php

namespace App\Reposotires\Cart;

use App\Models\Product;
interface CartRepository{
    public function get();
    public function add(Product $product,$quantity = 1);
    public function delete(Product $product);
    public function empty();
    public function total();
    public function update(Product $product,$quantity=1);
}