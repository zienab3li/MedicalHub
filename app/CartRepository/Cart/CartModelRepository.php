<?php

namespace App\Repositories\Cart;  

use App\Models\Cart;
use App\Models\Product;
use App\Reposotires\Cart\CartRepository;

use Illuminate\Support\Facades\Auth;

class CartModelRepository implements CartRepository
{
    public function get()
    {
        return Cart::with('product')
            ->where('user_id', Auth::id())
            ->get()
            ->map(function ($item) {
                return [
                    'id' => $item->id,
                    'product_id' => $item->product_id,
                    'product_name' => $item->product->name,
                    'price' => $item->product->price,
                    'quantity' => $item->quantity,
                    'total' => $item->product->price * $item->quantity
                ];
            });
    }

    public function add(Product $product, $quantity = 1)
    {
        $cartItem = Cart::where('user_id', Auth::id())
                      ->where('product_id', $product->id)
                      ->first();

        if ($cartItem) {
            $cartItem->increment('quantity', $quantity);
            return $cartItem;
        }

        return Cart::create([
            'user_id' => Auth::id(),
            'product_id' => $product->id,
            'quantity' => $quantity
        ]);
    }

    public function update(Product $product, $quantity = 1)
    {
        Cart::where('product_id', $product->id)
            ->where('user_id', Auth::id())
            ->update(['quantity' => $quantity]);
    }

    public function delete(Product $product)
    {
        Cart::where('product_id', $product->id)
            ->where('user_id', Auth::id())
            ->delete();
    }

    public function total()
    {
        return Cart::where('user_id', Auth::id())
            ->join('products', 'products.id', '=', 'carts.product_id')
            ->selectRaw('SUM(products.price * carts.quantity) as total')
            ->value('total');
    }

    public function empty()
    {
        Cart::where('user_id', Auth::id())->delete();
    }
}