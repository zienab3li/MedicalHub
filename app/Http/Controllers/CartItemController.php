<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Repositories\Cart\CartRepository;

class CartItemController extends Controller
{
    protected $cart;

    public function __construct(CartRepository $cart)
    {
        $this->cart = $cart;
    }

    public function addToCart(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $product = Product::findOrFail($request->product_id);

        $cartItem = $this->cart->add($product, $request->quantity);

        return response()->json([
            'status' => 200,
            'message' => 'Product added to cart successfully',
            'cart_item' => $cartItem,
        ]);
    }

    public function viewCart()
    {
        $cartItems = $this->cart->get();

        return response()->json([
            'status' => 200,
            'message' => 'All items in cart',
            'cart_items' => $cartItems,
        ]);
    }

    public function updateCart(Request $request, $productId)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        $product = Product::findOrFail($productId);

        $this->cart->update($product, $request->quantity);

        return response()->json([
            'status' => 200,
            'message' => 'Cart updated successfully',
        ]);
    }

    public function removeFromCart($id)
    {
        $deleted = $this->cart->delete($id);

        if (!$deleted) {
            return response()->json([
                'status' => 404,
                'message' => 'Cart item not found',
            ]);
        }

        return response()->json([
            'status' => 200,
            'message' => 'Item removed from cart',
        ]);
    }

    public function clearCart()
    {
        $this->cart->empty();

        return response()->json([
            'status' => 200,
            'message' => 'Cart cleared successfully',
        ]);
    }

    public function cartTotal()
    {
        $total = $this->cart->total();

        return response()->json([
            'status' => 200,
            'total' => $total,
        ]);
    }
}
