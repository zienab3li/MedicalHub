<?php
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CartItemController;
use App\Http\Controllers\ProductController;
use App\Models\Prescription;
use App\Http\Controllers\ClinicController;
use App\Http\Controllers\DoctorController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PrescriptionController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RessetpasswordControll;


use App\Http\Controllers\SocialLoginController;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');



Route::middleware('auth:sanctum')->group(function () {

    //admin routs
    Route::post('/admin/logout', [AdminController::class, 'logout']);

    //user routs
    Route::post('/user/update', [AuthController::class, 'updateuser'])->name('user.update');
    Route::post('/user/logout', [AuthController::class, 'logout']);

    //dashboard
    Route::get('/dashboard/stats', [DashboardController::class, 'getStats'])->name('dashboard');
  //cart orders
    Route::apiResource('orders',OrderController::class);
    Route::apiResource('orders', OrderController::class);

    Route::apiResource('posts',PostController::class);
    Route::apiResource('comments',CommentController::class);


    Route::post('/doctors/logout', [DoctorController::class, 'logout']); // Doctor logout


});

Route::prefix('admin')->group(function () {
    Route::post('/register', [AdminController::class, 'register']);
    Route::post('/login', [AdminController::class, 'login']);   
});

Route::post('/password/reset-link', [RessetpasswordControll::class, 'sendResetLink']);
Route::post('/password/update', [RessetpasswordControll::class, 'updatePassword']);

 // clinic routes
Route::apiResource('clinics',ClinicController::class);

// Doctor routes
Route::apiResource('doctors', DoctorController::class); // Add doctor routes

// Public routes (no authentication required)
Route::post('/doctors/login', [DoctorController::class, 'login']); // Doctor login



// user routes
Route::prefix('user')->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login'])->name('user.login');
});


//social routes
Route::prefix('auth')->group(function () {
    Route::get('/{provider}/redirect', [SocialLoginController::class, 'redirect'])->name('auth.socialite.redirect');
    Route::get('/{provider}/callback', [SocialLoginController::class, 'callback'])->name('auth.socialite.callback');
});


// products Routes
Route::get('/products', [ProductController::class, 'index']);
Route::get('/products/category/{category_id}', [ProductController::class, 'show']);

// cart Routes
Route::post('/cart', [CartItemController::class, 'addToCart']);
Route::get('/cart', [CartItemController::class, 'viewCart']);
Route::put('/cart/{id}', [CartItemController::class, 'updateCart']);
Route::delete('/cart/{id}', [CartItemController::class, 'removeFromCart']);
Route::delete('/cart', [CartItemController::class, 'clearCart']);


//prescriptions routes
Route::post('/prescriptions', [PrescriptionController::class, 'uploadPrescription']); // رفع الوصفة
// Route::middleware('auth:sanctum')->group(function () {
//     Route::post('/prescriptions', [PrescriptionController::class, 'uploadPrescription']); // رفع الوصفة

// });