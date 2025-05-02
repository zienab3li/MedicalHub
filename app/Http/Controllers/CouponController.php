<?php

namespace App\Http\Controllers;

use App\Mail\SendCouponMail;
use App\Models\Coupon;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class CouponController extends Controller
{


    public function index()
    {
        $coupons = Coupon::where('is_active', true)
            ->where(function ($query) {
                $query->whereNull('expires_at')
                      ->orWhere('expires_at', '>', now());
            })
            ->orderBy('created_at', 'desc')
            ->get();
    
        return response()->json([
            'coupons' => $coupons
        ]);
    }
    
    public function check(Request $request)
{
    $validated = $request->validate([
        'coupon_code' => 'required|string',
        'total_price' => 'required|numeric|min:0',
    ]);

    $coupon = Coupon::where('code', $validated['coupon_code'])->where('is_active', true)->first();

    if (!$coupon || !$coupon->isValid()) {
        return response()->json(['valid' => false, 'message' => 'Coupon is invalid or expired'], 400);
    }

    $discount = 0;

    if ($coupon->discount_type === 'percentage') {
        $discount = $validated['total_price'] * ($coupon->discount_value / 100);
    } elseif ($coupon->discount_type === 'fixed') {
        $discount = $coupon->discount_value;
    }

    $finalPrice = max(0, $validated['total_price'] - $discount);

    return response()->json([
        'valid' => true,
        'discount' => round($discount, 2),
        'final_price' => round($finalPrice, 2),
        'message' => 'Coupon applied successfully'
    ]);
}


public function store(Request $request)
{
    $validated = $request->validate([
        'code' => 'required|unique:coupons,code',
        'discount_type' => 'required|in:percentage,fixed',
        'discount_value' => 'required|numeric|min:0',
        'usage_limit' => 'required|integer|min:1',
        'expires_at' => 'nullable|date',
    ]);

    $coupon = Coupon::create([
        'code' => strtoupper($validated['code']),
        'discount_type' => $validated['discount_type'],
        'discount_value' => $validated['discount_value'],
        'usage_limit' => $validated['usage_limit'],
        'expires_at' => $validated['expires_at'],
        'is_active' => true,
    ]);

    $users = User::all();
    foreach ($users as $user) {
        // Mail::to($user->email)->queue(new SendCouponMail($coupon));
        Mail::to($user->email)->send(new SendCouponMail($coupon));

    }

    return response()->json([
        'message' => 'Coupon created and emails sent successfully.',
        'coupon' => $coupon,
    ]);
}

}
