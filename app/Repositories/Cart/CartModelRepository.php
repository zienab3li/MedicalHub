<?php

namespace App\Repositories\Cart;

use App\Models\Cart;
use App\Models\Product;
use App\Models\CartItem;
use Illuminate\Support\Facades\Auth;

class CartModelRepository implements CartRepository
{
    public function get()
    {
        return CartItem::with('product')
            ->where('user_id', Auth::id())
            ->get();
    }
    
    public function add(Product $product, $quantity = 1)
    {
        $item = CartItem::updateOrCreate([
            'user_id' => Auth::id(),
            'product_id' => $product->id,
        ], [
            'quantity' => $quantity,
        ]);
        
        return $item;
    }
    
    public function update(Product $product, $quantity)
    {
        CartItem::where('user_id', Auth::id())
            ->where('product_id', $product->id)
            ->update([
                'quantity' => $quantity,
            ]);
    }
    
    public function delete($id)
    {
        CartItem::where('user_id', Auth::id())
            ->where('id', $id)
            ->delete();
    }
    
    public function empty()
    {
        CartItem::where('user_id', Auth::id())->delete();
    }
    
    // public function total()
    // {
    //     $items = $this->get();
    //     return $items->sum(function($item) {
    //         return $item->quantity * $item->product->price;
    //     });
    // }
    public function total()
    {
        return Cart::where('user_id', Auth::id())
            ->join('products', 'products.id', '=', 'carts.product_id')
            ->selectRaw('SUM(products.price * carts.quantity) as total')
            ->value('total');
    }
} 