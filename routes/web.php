<?php

use App\Http\Controllers\PaymentController;
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

Route::get('/payments/{order}/stripe/confirm', [PaymentController::class, 'confirm'])->name('payments.stripe.confirm');
Route::get('/payments/{order}/stripe', [PaymentController::class, 'create'])->name('payments.stripe.form');
// Route::get('/{order}/stripe/confirm', [PaymentController::class, 'confirmPage'])->name('payments.stripe.confirm');

