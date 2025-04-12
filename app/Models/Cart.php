<?php

namespace App\Models;

use App\Observers\CartObserver;
use Illuminate\Database\Eloquent\Model;
// use Illuminate\Support\Str;

class Cart extends Model
{
    protected $fillable = ['cookie_id','user_id','product_id','quantity','options'];
    //Event (observer)
    //creating,created,updating,updated

    protected static function booted(){

        static::observe(CartObserver::class);
        // static::creating(function(Cart $cart){
        //     $cart->id=str::uuid();
        // })
    }
    public function user(){
        $this->belongsTo(User::class);
    }
    public function product(){
        $this->belongsTo(Product::class);
    }
}
