<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function index()
{
    $orders = Order::with(['items', 'user:id,id,name'])->paginate(10);
    return response()->json($orders);
}

public function store(Request $request)
{
    $validated = $request->validate([
        'user_id' => 'required|exists:users,id',
        'status' => 'required|string',
        'total_price' => 'required|numeric',
        'payment_method' => 'required|string', 
        'coupon_code' => 'nullable|string',
        'items' => 'required|array|min:1',
        'items.*.product_id' => 'required|exists:products,id',
        'items.*.quantity' => 'required|integer|min:1',
        'items.*.price' => 'required|numeric|min:0',
    ]);

    DB::beginTransaction();
    try {
        $discount = 0;
        $couponCode = $validated['coupon_code'] ?? null;

        if ($couponCode) {
            $coupon = \App\Models\Coupon::where('code', $couponCode)->where('is_active', true)->first();

            if (!$coupon || !$coupon->isValid()) {
                return response()->json(['error' => 'Invalid or expired coupon'], 400);
            }

            if ($coupon->discount_type === 'percentage') {
                $discount = $validated['total_price'] * ($coupon->discount_value / 100);
            } elseif ($coupon->discount_type === 'fixed') {
                $discount = $coupon->discount_value;
            }

            $coupon->increment('used_times');
        }

        $finalPrice = $validated['total_price'] - $discount;

        $order = Order::create([
            'user_id' => $validated['user_id'],
            'status' => $validated['status'],
            'payment_method' => $validated['payment_method'],
            'coupon_code' => $couponCode,
            'discount' => $discount,
            'total_price' => max(0, $finalPrice),
        ]);

        foreach ($validated['items'] as $item) {
            $product = Product::find($item['product_id']);
            if (!$product || $product->stock < $item['quantity']) {
                DB::rollBack();
                return response()->json(['error' => 'Insufficient stock'], 400);
            }

            $order->items()->create($item);

            $product->stock -= $item['quantity'];
            $product->save();
        }

        DB::commit();
        return response()->json($order->load('items'), 201);
    } catch (\Exception $e) {
        DB::rollBack();
        return response()->json(['error' => 'Failed to create order', 'details' => $e->getMessage()], 500);
    }
}



    // public function store(Request $request)
    // {
    //     $validated = $request->validate([
    //         'user_id' => 'required|exists:users,id',
    //         'status' => 'required|string',
    //         'total_price' => 'required|numeric',
    //         'payment_method' => 'required|string', 
    //         'items' => 'required|array|min:1',
    //         'items.*.product_id' => 'required|exists:products,id',
    //         'items.*.quantity' => 'required|integer|min:1',
    //         'items.*.price' => 'required|numeric|min:0',
    //     ]);
    //     DB::beginTransaction();
    //     try {
    //         $order = Order::create([
    //             'user_id' => $validated['user_id'],
    //             'status' => $validated['status'],
    //             'total_price' => $validated['total_price'],
    //             'payment_method' => $validated['payment_method'] 
    //         ]);
    
    //         foreach ($validated['items'] as $item) {
    //             $product = Product::find($item['product_id']);
    //             if (!$product || $product->stock < $item['quantity']) {
    //                 DB::rollBack();
    //                 return response()->json(['error' => 'Insufficient stock'], 400);
    //             }
    
    //             $order->items()->create($item);
    
    //             $product->stock -= $item['quantity'];
    //             $product->save();
    //         }
    
    //         DB::commit();
    //         return response()->json($order->load('items'), 201);
    //     } catch (\Exception $e) {
    //         DB::rollBack();
    //         return response()->json(['error' => 'Failed to create order', 'details' => $e->getMessage()], 500);
    //     }
    // }


//     public function store(Request $request)
// {
//     $validated = $request->validate([
//         'user_id' => 'required|exists:users,id',
//         'status' => 'required|string',
//         'total_price' => 'required|numeric',
//         'payment_method' => 'required|string', 
//         'items' => 'required|array|min:1',
//         'items.*.product_id' => 'required|exists:products,id',
//         'items.*.quantity' => 'required|integer|min:1',
//         'items.*.price' => 'required|numeric|min:0',
//         // Validation للعناوين
//         'addr' => 'required|array',
//         'addr.billing' => 'required|array',
//         'addr.billing.first_name' => 'required|string',
//         'addr.billing.last_name' => 'required|string',
//         'addr.billing.email' => 'required|email',
//         'addr.billing.street_address' => 'required|string',
//         'addr.billing.phone_number' => 'required|string',
//         'addr.billing.city' => 'required|string',
//         'addr.billing.postal_code' => 'required|string',
//         'addr.billing.state' => 'required|string',
//         'addr.billing.country' => 'required|string',
//         'addr.shipping' => 'required|array',
//         'addr.shipping.first_name' => 'required|string',
//         'addr.shipping.last_name' => 'required|string',
//         'addr.shipping.email' => 'required|email',
//         'addr.shipping.street_address' => 'required|string',
//         'addr.shipping.phone_number' => 'required|string',
//         'addr.shipping.city' => 'required|string',
//         'addr.shipping.postal_code' => 'required|string',
//         'addr.shipping.state' => 'required|string',
//         'addr.shipping.country' => 'required|string',
//     ]);

//     DB::beginTransaction();
//     try {
//         $order = Order::create([
//             'user_id' => $validated['user_id'],
//             'status' => $validated['status'],
//             'total_price' => $validated['total_price'],
//             'payment_method' => $validated['payment_method']
//         ]);

//         foreach ($validated['items'] as $item) {
//             $product = Product::find($item['product_id']);
//             if (!$product || $product->stock < $item['quantity']) {
//                 DB::rollBack();
//                 return response()->json(['error' => 'Insufficient stock'], 400);
//             }

//             $order->items()->create($item);

//             $product->stock -= $item['quantity'];
//             $product->save();
//         }

//         // تخزين العناوين بعد إنشاء الطلب
//         $order->addresses()->create([
//             'type' => 'billing',
//             'first_name' => $validated['addr']['billing']['first_name'],
//             'last_name' => $validated['addr']['billing']['last_name'],
//             'email' => $validated['addr']['billing']['email'],
//             'street_address' => $validated['addr']['billing']['street_address'],
//             'phone_number' => $validated['addr']['billing']['phone_number'],
//             'city' => $validated['addr']['billing']['city'],
//             'postal_code' => $validated['addr']['billing']['postal_code'],
//             'state' => $validated['addr']['billing']['state'],
//             'country' => $validated['addr']['billing']['country'],
//         ]);

//         $order->addresses()->create([
//             'type' => 'shipping',
//             'first_name' => $validated['addr']['shipping']['first_name'],
//             'last_name' => $validated['addr']['shipping']['last_name'],
//             'email' => $validated['addr']['shipping']['email'],
//             'street_address' => $validated['addr']['shipping']['street_address'],
//             'phone_number' => $validated['addr']['shipping']['phone_number'],
//             'city' => $validated['addr']['shipping']['city'],
//             'postal_code' => $validated['addr']['shipping']['postal_code'],
//             'state' => $validated['addr']['shipping']['state'],
//             'country' => $validated['addr']['shipping']['country'],
//         ]);

//         DB::commit();
//         return response()->json($order->load('items', 'addresses'), 201);

//     } catch (\Exception $e) {
//         DB::rollBack();
//         return response()->json(['error' => 'Failed to create order', 'details' => $e->getMessage()], 500);
//     }
// }

    

    // public function show($id)
    // {
    //     $order = Order::with('items')->findOrFail($id);
    //     return response()->json($order);
    // }

    public function show()
{
    $userId = auth()->user()->id;

    $orders = Order::with(['items.product', 'user'])
        ->where('user_id', $userId)
        ->paginate(10);

    return response()->json($orders);
}


    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'status' => 'sometimes|required|string',
            'total_price' => 'sometimes|required|numeric',
            'items' => 'sometimes|required|array|min:1',
            'items.*.product_id' => 'required_with:items|exists:products,id',
            'items.*.quantity' => 'required_with:items|integer|min:1',
            'items.*.price' => 'required_with:items|numeric|min:0',
        ]);

        $order = Order::findOrFail($id);

        DB::beginTransaction();
        try {
            $order->update($validated);

            if (isset($validated['items'])) {
                $order->items()->delete();
                foreach ($validated['items'] as $item) {
                    $order->items()->create($item);
                }
            }

            DB::commit();
            return response()->json($order->load('items'));
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Failed to update order'], 500);
        }
    }

    public function destroy($id)
    {
        $order = Order::findOrFail($id);
        $order->delete();
        return response()->json(['message' => 'Order deleted successfully'], 204);
    }
    
}
