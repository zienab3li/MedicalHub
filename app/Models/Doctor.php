<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Doctor extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'email',
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
}
