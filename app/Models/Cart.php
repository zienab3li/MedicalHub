<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    protected $fillable = ['cookie_id','user_id','product_id','quantity','options'];
}
