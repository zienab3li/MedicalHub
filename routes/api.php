<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Laravel\Socialite\Facades\Socialite;
use App\Http\Controllers\SocialLoginController;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');






Route::middleware('auth:sanctum')->group(function () {

    //admin routs
    Route::post('/admin/logout', [AdminController::class, 'logout']);

    // user routs
    Route::post('/user/update', [AuthController::class, 'updateuser'])->name('user.update');
    Route::post('/user/logout', [AuthController::class, 'logout']);

    //dashboard
    Route::get('/dashboard/stats', [DashboardController::class, 'getStats'])->name('dashboard');
});

Route::prefix('admin')->group(function () {
    Route::post('/register', [AdminController::class, 'register']);
    Route::post('/login', [AdminController::class, 'login']);
});


// user routs
Route::prefix('user')->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login'])->name('user.login');
});


//social routs
Route::prefix('auth')->group(function () {
    Route::get('/{provider}/redirect', [SocialLoginController::class, 'redirect'])->name('auth.socialite.redirect');
    Route::get('/{provider}/callback', [SocialLoginController::class, 'callback'])->name('auth.socialite.callback');
});
