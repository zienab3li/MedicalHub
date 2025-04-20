<?php

namespace App\Repositories\Cart;

use App\Models\CartItem;
use App\Models\Product;
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
        return CartItem::updateOrCreate([
            'user_id' => Auth::id(),
            'product_id' => $product->id,
        ], [
            'quantity' => $quantity,
        ]);
    }

    public function update(Product $product, $quantity)
    {
        return CartItem::where('user_id', Auth::id())
            ->where('product_id', $product->id)
            ->update(['quantity' => $quantity]);
    }

    public function delete($id)
    {
        return CartItem::where('user_id', Auth::id())
            ->where('id', $id)
            ->delete();
    }

    public function empty()
    {
        return CartItem::where('user_id', Auth::id())->delete();
    }

    public function total()
    {
        return CartItem::where('user_id', Auth::id())
            ->join('products', 'products.id', '=', 'cart_items.product_id')
            ->selectRaw('SUM(products.price * cart_items.quantity) as total')
            ->value('total');
    }
}
