<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ClinicController;
use App\Http\Controllers\DoctorController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Laravel\Socialite\Facades\Socialite;
use App\Http\Controllers\SocialLoginController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');



Route::post('/user/register', [AuthController::class, 'register']);



Route::middleware('auth:sanctum')->group(function () {

    //admin routs
    Route::post('/admin/register', [AdminController::class, 'register']);
    Route::post('/admin/login', [AdminController::class, 'login']);
    Route::post('/admin/logout', [AdminController::class, 'logout']);

    // user routs
    Route::post('/user/login', [AuthController::class, 'login'])->name('user.login');
    Route::post('/user/logout', [AuthController::class, 'logout']);

    // clinic routes
   

    // Doctor routes
   
    Route::post('/doctors/logout', [DoctorController::class, 'logout']); // Doctor logout
});
Route::apiResource('clinics',ClinicController::class);
Route::apiResource('doctors', DoctorController::class); // Add doctor routes
// Public routes (no authentication required)
Route::post('/doctors/login', [DoctorController::class, 'login']); // Doctor login





//social routs
Route::get('auth/{provider}/redirect' , [SocialLoginController::class , 'redirect'])->name('auth.socialite.redirect');
Route::get('auth/{provider}/callback',[SocialLoginController::class , 'callback'])->name('auth.socialite.callback');


