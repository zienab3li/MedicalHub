<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;
    protected $fillable =['doctor_id','title','content'];
    public function doctor(){
    return $this->belongsTo(Doctor::class);
    }
    public function comments(){
    return $this->hasMany(Comment::class);
    }
}
