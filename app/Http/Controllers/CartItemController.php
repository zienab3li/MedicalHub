<?php

namespace App\Http\Controllers;

use App\Models\CartItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class CartItemController extends Controller
{


    public function addToCart(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);
        $userId = Auth::id();

        $cartItem = CartItem::updateOrCreate(
            [
                'user_id' => $userId,
                'product_id' => $request->product_id
            ],
            [
                'quantity' => $request->quantity
            ]
        );

        return  response()->json([
            'status' => 200,
            'message' => 'Product added to cart successfully',
            'cart_item' => $cartItem
        ]);
    }

    public function viewCart()
    {
        $userId = Auth::id();

        $cartItems = CartItem::where('user_id', $userId)->with('product')->get();
        return  response()->json([
            'status' => 200,
            'message' => 'This all items in cart',
            'cart_item' => $cartItems
        ]);
    }

    public function updateCart(Request $request, $id)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1'
        ]);

        $userId = Auth::id();
        $cartItem = CartItem::where('user_id', $userId)->find($id);
        if (!$cartItem) {
            return response()->json([
                'status' => 404,
                'message' => 'Cart item not found'
            ]);
        }

        $cartItem->update([
            'quantity' => $request->quantity
        ]);
        return response()->json([
            'status' => 200,
            'message' => 'Cart updated successfully',
            'cartItem' => $cartItem
        ]);
    }

    public function removeFromCart($id)
    {
        $userId = Auth::id();

        $cartItem = CartItem::where('user_id', $userId)->find($id);
        if (!$cartItem) {
            return response()->json([
                'status' => 404,
                'message' => 'Cart item not found'
            ]);
        }

        $cartItem->delete();
        return response()->json([
            'status' => 200,
            'message' => 'Item removed from cart'
        ]);
    }

    public function clearCart()
    {
        $userId = Auth::id();

        CartItem::where('user_id', $userId)->delete();
        return response()->json([
            'status' => 200,
            'message' => 'Cart cleared successfully'
        ]);
    }










    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(CartItem $cartItem)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(CartItem $cartItem)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, CartItem $cartItem)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CartItem $cartItem)
    {
        //
    }
}
