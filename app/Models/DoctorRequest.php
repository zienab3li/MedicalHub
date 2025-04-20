<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DoctorRequest extends Model
{
    //
    use HasFactory;

    protected $fillable = [
        'full_name',
        'email',
        'phone',
        'specialization',
        'card_image',
        'certificate_pdf',
        'notes',
        'is_read',
    ];
}
