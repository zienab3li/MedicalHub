<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'status',
        'payment_method',
        'payment_status'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    protected static function booted()
    {
        static::creating(function(Order $order){
            $order->number=self::getNextOrderNumber();
        });
    }
    public static function getNextOrderNumber( ){
        $year=Carbon::now()->year;
        $number=Order::whereYear('created_at',$year)->max('number');
        if($number){
            return $number + 1;
        }
        return $year . '0001';
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }
    public function products()
    {
        return $this->belongsToMany(Product::class,'order_items','order_id','product_id','id','id')
        ->using(OrderItem::class)
        ->as('order_items')
        ->withPivot('quantity','price');
    }
    public function address(){
        return $this->hasMany(OrderAddress::class);
    }
    public function billingAddress(){
        return $this->hasOne(OrderAddress::class,'order_id','id')
        ->where('type','billing');
    }
    public function shippingAddress(){
        return $this->hasOne(OrderAddress::class,'order_id','id')
        ->where('type','shipping');
    }

}