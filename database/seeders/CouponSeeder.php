<?php

use Illuminate\Database\Seeder;
use App\Models\Coupon;
use Carbon\Carbon;

class CouponSeeder extends Seeder
{
    public function run()
    {
        Coupon::create([
            'code' => 'AZDISCOUNT10',
            'discount_type' => 'percentage',
            'discount_value' => 10,
            'usage_limit' => 100,
            'used_times' => 0,
            'expires_at' => Carbon::now()->addDays(30),
            'is_active' => true,
        ]);

        Coupon::create([
            'code' => 'FLAT50',
            'discount_type' => 'fixed',
            'discount_value' => 50,
            'usage_limit' => 50,
            'used_times' => 0,
            'expires_at' => Carbon::now()->addDays(10),
            'is_active' => true,
        ]);
    }
}

