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
        'specialization',
        'bio',
        'clinic_address',
        'role',
        'address',
        'phone',
        'image',
    ];
    protected $hidden=[
        'password'
    ]
    ;
    public function clinic(){
        return $this->belongsTo(Clinic::class);
    }
    // App\Models\Doctor.php

public function appointments()
{
    return $this->hasMany(Appointment::class);
}

}
