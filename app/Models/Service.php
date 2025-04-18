<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    protected $fillable = [
        'name',
        'description',
        'image',
        'price',
        'duration',
        'is_active',
        'instructions',
    ];

    public function bookings()
    {
        return $this->hasMany(ServiceBooking::class);
    }
}
