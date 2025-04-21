<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        'order_id',
        'amount',
        'currency',
        'method',
        'status',
        'transaction_id',
        'transaction_date'
    ];

    protected $casts = [
        'transaction_date' => 'array'
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
