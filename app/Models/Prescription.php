<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Prescription extends Model
{
    protected $fillable = ['user_id', 'file_path', 'status'];
    public function user()
    {
        return $this->belongsTo(User::class);
    }

}
