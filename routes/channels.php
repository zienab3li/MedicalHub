<?php

use Illuminate\Support\Facades\Broadcast;
use App\Models\Appointment;

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/

Broadcast::channel('chat.{id}', function ($user, $id) {
    // For users
    if ($user instanceof \App\Models\User) {
        return Appointment::where('user_id', $user->id)
            ->where('doctor_id', $id)
            ->where('status', '!=', 'cancelled')
            ->exists();
    }
    
    // For doctors
    if ($user instanceof \App\Models\Doctor) {
        return Appointment::where('doctor_id', $user->id)
            ->where('user_id', $id)
            ->where('status', '!=', 'cancelled')
            ->exists();
    }
    
    return false;
}); 