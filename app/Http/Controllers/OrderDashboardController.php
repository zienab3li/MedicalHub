<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class OrderDashboardController extends Controller
{
    /**
     * عرض تفاصيل طلب معين.
     */
    public function show($id)
    {
        $order = Order::with([
            'user:id,name,email',
            'items.product:id,name',
            'billingAddress',
            'shippingAddress',
            'payment'
        ])->findOrFail($id);

        $items = $order->items->map(function ($item) {
            return [
                'product_id' => $item->product_id,
                'product_name' => optional($item->product)->name,
                'quantity' => $item->quantity,
                'price' => $item->price,
                'subtotal' => $item->quantity * $item->price,
            ];
        });

        $formatAddress = function ($address) {
            if (!$address) return null;
            return [
                'name' => $address->first_name . ' ' . $address->last_name,
                'email' => $address->email,
                'phone' => $address->phone_number,
                'address' => $address->street_address,
                'city' => $address->city,
                'state' => $address->state,
                'postal_code' => $address->postal_code,
                'country' => $address->country,
            ];
        };

        return response()->json([
            'id' => $order->id,
            'number' => $order->number,
            'status' => $order->status,
            'payment_method' => $order->payment_method,
            'payment_status' => $order->payment_status,
            'user' => [
                'id' => $order->user->id,
                'name' => $order->user->name,
                'email' => $order->user->email
            ],
            'items_count' => $items->count(),
            'total_quantity' => $items->sum('quantity'),
            'total_price' => $items->sum('subtotal'),
            'items' => $items,
            'billing_address' => $formatAddress($order->billingAddress),
            'shipping_address' => $formatAddress($order->shippingAddress),
            'payment' => $order->payment ? [
                'amount' => $order->payment->amount,
                'currency' => $order->payment->currency,
                'method' => $order->payment->method,
                'status' => $order->payment->status,
                'transaction_id' => $order->payment->transaction_id,
                'transaction_date' => $order->payment->transaction_date,
            ] : null,
            'created_at' => $order->created_at->toDateTimeString()
        ]);
    }
}
