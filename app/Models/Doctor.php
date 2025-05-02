<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Laravel\Sanctum\HasApiTokens; 
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Doctor extends Authenticatable
{
    use HasFactory, SoftDeletes, HasApiTokens;

    protected $fillable = [
        'name',
        'email',
        'password',
        'clinic_id',
        'vet_id',
        'specialization',
        'bio',
        'clinic_address',
        'role',
        'address',
        'phone',
        'image',
        'clinic_type'
    ];
    protected $hidden=[
        'password'
    ]
    ;
    public function clinic(){
        return $this->belongsTo(Clinic::class);
    }

    public function vet()
    {
        return $this->belongsTo(Vet::class);
    }

    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }

    public function feedbacks()
    {
        return $this->hasMany(Feedback::class);
    }

    public function averageRating()
    {
        return $this->feedbacks()->avg('rating');
    }
}
