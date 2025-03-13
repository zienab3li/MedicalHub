<?php

use App\Http\Controllers\SocialLoginController;
use Illuminate\Support\Facades\Route;
use Laravel\Socialite\Facades\Socialite;

Route::get('/', function () {
    return view('welcome');
});




Route::get('auth/{provider}/redirect' , [SocialLoginController::class , 'redirect'])->name('auth.socialite.redirect');
Route::get('auth/{provider}/callback',[SocialLoginController::class , 'callback'])->name('auth.socialite.callback');
// Route::get('auth/{provider}/callback', function ($provider) {
//     return "Callback reached with provider: $provider";
// });
