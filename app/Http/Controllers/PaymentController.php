<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Payment;
use Illuminate\Http\Request;
use Stripe\StripeClient;

class PaymentController extends Controller
{
    public function create(Order $order)
    {
        return view('front.payments.create', [
            'order' => $order,
        ]);
    }

    public function createStripePaymentIntent(Order $order)
    {
        $amount = $order->items->sum(function($item) {
            return $item->price * $item->quantity;
        });

        $stripe = new StripeClient(config('services.stripe.secret_key'));
        $paymentIntent = $stripe->paymentIntents->create([
            'amount' => $amount * 100, // Convert to cents
            'currency' => 'usd',
            'payment_method_types' => ['card'],
        ]);

        $payment = Payment::create([
            'order_id' => $order->id,
            'amount' => $amount,
            'currency' => 'usd',
            'method' => 'stripe',
            'transaction_id' => $paymentIntent->id,
            'transaction_date' => [
                'created' => now()->timestamp
            ]
        ]);

        return [
            'clientSecret' => $paymentIntent->client_secret,
        ];
    }

    public function confirm(Request $request, Order $order)
    {
        $stripe = new StripeClient(config('services.stripe.secret_key'));
        $paymentIntent = $stripe->paymentIntents->retrieve($request->payment_intent);

        if ($paymentIntent->status === 'succeeded') {
            $payment = Payment::where('transaction_id', $paymentIntent->id)->first();
            $payment->update([
                'status' => 'completed',
                'transaction_date' => array_merge($payment->transaction_date, [
                    'confirmed' => now()->timestamp
                ])
            ]);

            $order->update([
                'payment_status' => 'paid'
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Payment completed successfully'
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Payment failed'
        ], 400);
    }
}
