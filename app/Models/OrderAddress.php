<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderAddress extends Model
{
    public $timestamps = false;
protected $fillable = [
    'order_id',
    'type',
    'first_name',
    'last_name',
    'email',
    'street_address',
    'phone_number',
    'city',
    'postal_code',
    'state',
    'country'
];
}
