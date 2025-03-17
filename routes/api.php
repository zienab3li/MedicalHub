<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\OrderController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');




Route::post('/admin/register', [AdminController::class, 'register']);
Route::post('/admin/login', [AdminController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/admin/logout', [AdminController::class, 'logout']);
    Route::post('/user/logout', [AuthController::class, 'logout']);
});

Route::get('/orders', [OrderController::class, 'index']);       
Route::post('/orders', [OrderController::class, 'store']);     
Route::get('/orders/{id}', [OrderController::class, 'show']);  
Route::put('/orders/{id}', [OrderController::class, 'update']); 
Route::delete('/orders/{id}', [OrderController::class, 'destroy']); 

Route::post('/user/register', [AuthController::class, 'register']);
Route::post('/user/login', [AuthController::class, 'login']);
