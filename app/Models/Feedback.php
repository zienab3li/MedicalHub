<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Feedback extends Model
{
    protected $fillable = ['user_id', 'doctor_id', 'type', 'rating', 'comment'];
// App\Models\Feedback.php

public function user()
{
    return $this->belongsTo(User::class);
}

public function doctor()
{
    return $this->belongsTo(Doctor::class);
}

}
