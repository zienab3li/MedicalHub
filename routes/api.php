<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CartItemController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Models\Prescription;
use App\Http\Controllers\ClinicController;
use App\Http\Controllers\DoctorController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PrescriptionController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RessetpasswordControll;
use App\Http\Controllers\ServiceBookingController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\SocialLoginController;

use App\Http\Controllers\PaymentController;

use App\Http\Controllers\VetController;

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
    Route::apiResource('orders', OrderController::class);
    Route::apiResource('orders', OrderController::class);

    Route::apiResource('posts', PostController::class);
    Route::apiResource('comments', CommentController::class);


    Route::post('/doctors/logout', [DoctorController::class, 'logout']); // Doctor logout


});

Route::prefix('admin')->group(function () {
    Route::post('/register', [AdminController::class, 'register']);
    Route::post('/login', [AdminController::class, 'login']);
});

Route::post('/password/reset-link', [RessetpasswordControll::class, 'sendResetLink']);
Route::post('/password/update', [RessetpasswordControll::class, 'updatePassword']);

// clinic routes
Route::apiResource('clinics', ClinicController::class);

// Doctor routes
Route::apiResource('vets', VetController::class);
Route::apiResource('doctors', DoctorController::class); // Add doctor routes
Route::apiResource('appointments', AppointmentController::class); // appointments routes
Route::apiResource('services', ServiceController::class);
Route::apiResource('servicesbooking', ServiceBookingController::class);

// Public routes (no authentication required)
Route::post('/doctors/login', [DoctorController::class, 'login']); // Doctor login



// user routes
Route::prefix('user')->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login'])->name('user.login');
    Route::get('/users', [AuthController::class, 'users']);
});


//social routes
Route::prefix('auth')->group(function () {
    Route::get('/{provider}/redirect', [SocialLoginController::class, 'redirect'])->name('auth.socialite.redirect');
    Route::get('/{provider}/callback', [SocialLoginController::class, 'callback'])->name('auth.socialite.callback');
});


// products Routes
// Route::get('/products', [ProductController::class, 'index']);
Route::prefix('products')->group(function () {
    Route::get('/', [ProductController::class, 'index']); // Get all products
    Route::post('/', [ProductController::class, 'store']); // Create a product
    Route::get('/{id}', [ProductController::class, 'showProduct']); // Get single product
    Route::post('/{product}', [ProductController::class, 'update']); // Update product
    Route::delete('/{product}', [ProductController::class, 'destroy']); // Delete product
    Route::get('/category/{category_id}', [ProductController::class, 'show']); // show products related to specific category
    Route::get('/type/{type}', [ProductController::class, 'showHumanProducts']);

});

// cart Routes
// Route::post('/cart', [CartItemController::class, 'addToCart']);
// Route::get('/cart', [CartItemController::class, 'viewCart']);
// Route::put('/cart/{id}', [CartItemController::class, 'updateCart']);
// Route::delete('/cart/{id}', [CartItemController::class, 'removeFromCart']);
// Route::delete('/cart', [CartItemController::class, 'clearCart']);

//cart route 
// Route::prefix('cart')->group(function () {

//     Route::get('/', [CartController::class, 'index']);


//     Route::post('/', [CartController::class, 'store']);


//     Route::put('/{id}', [CartController::class, 'update']);

//     Route::delete('/{id}', [CartController::class, 'destroy']);


//     // Route::get('/total', [CartController::class, 'total']); 
//     // Route::delete('/', [CartController::class, 'empty']); 
// });
Route::apiResource('cart', CartController::class)
    ->except(['show']) 
    ->parameters(['cart' => 'id']);
   

//CHeckout route
Route::prefix('checkout')->group(function () {
     Route::post('/', [OrderController::class, 'store']);
    Route::get('/', [OrderController::class, 'show']);
    Route::put('/{id}', [OrderController::class, 'update']);
    Route::delete('/{id}', [OrderController::class, 'destroy']);
});

// Payment routes
Route::prefix('payments')->group(function () {
    Route::post('/{order}/stripe/intent', [PaymentController::class, 'createStripePaymentIntent'])->name('payments.stripe.intent');
    Route::post('/{order}/stripe/confirm', [PaymentController::class, 'confirm']);
});

//prescriptions routes
Route::post('/prescriptions', [PrescriptionController::class, 'uploadPrescription']); 
// Route::middleware('auth:sanctum')->group(function () {
//     Route::post('/prescriptions', [PrescriptionController::class, 'uploadPrescription']); 

// });

// categories Routes     => only for admins
Route::post('/categories', [CategoryController::class, 'store']);
Route::post('categories/{category}', [CategoryController::class, 'update']);
Route::delete('/categories/{category}', [CategoryController::class, 'destroy']);
Route::get('/categories', [CategoryController::class, 'getAllCategories']);
Route::get('/categories/{category}', [CategoryController::class, 'show']);
Route::get('/categories/type/{type}', [CategoryController::class, 'getCategoriesByType']);




