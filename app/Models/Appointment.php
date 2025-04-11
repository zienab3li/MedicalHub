<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    protected $fillable = [
        'user_id', 
        'doctor_id', 
        'appointment_date', 
        'appointment_time', 
        'notes', 
        'status'
    ];
    // App\Models\Appointment.php
public function doctor()
{
    return $this->belongsTo(Doctor::class);
}

public function user()
{
    return $this->belongsTo(User::class);
}

}
