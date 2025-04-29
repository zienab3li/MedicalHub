<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    protected $fillable = [
        'code', 'discount_type', 'discount_value',
        'usage_limit', 'used_times', 'expires_at', 'is_active'
    ];

    // protected $dates = ['expires_at'];
    protected $casts = [
        'expires_at' => 'datetime',
    ];
    

    public function isValid()
    {
        return $this->is_active &&
            ($this->usage_limit === null || $this->used_times < $this->usage_limit) &&
            ($this->expires_at === null || $this->expires_at->isFuture());
    }
}
