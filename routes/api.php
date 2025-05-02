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
use App\Http\Controllers\DoctorRequestController;
use App\Http\Controllers\FeedbackController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PrescriptionController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RessetpasswordControll;
use App\Http\Controllers\ServiceBookingController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\SocialLoginController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\CheckOutController;
use App\Http\Controllers\CouponController;
use App\Http\Controllers\DoctorAppointmentController;
use App\Http\Controllers\VetController;


use App\Http\Controllers\SearchController;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');



Route::middleware('auth:sanctum')->group(function () {
    // Route::post('order', [CheckOutController::class, 'store']);
    Route::get('/orders', [OrderController::class, 'index']); 
    Route::get('/show', [OrderController::class, 'show']); 

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
    Route::get('/comments/{comment}/replies', [CommentController::class, 'getReplies']);

    Route::apiResource('posts', PostController::class);
    Route::apiResource('comments', CommentController::class);


    Route::post('/doctors/logout', [DoctorController::class, 'logout']); // Doctor logout


});
// routes/api.php
Route::post('/check-coupon', [CouponController::class, 'check']);


Route::put('/users/{id}/status', [AuthController::class, 'updateStatus']);


Route::prefix('admin')->group(function () {
    Route::post('/register', [AdminController::class, 'register']);
    Route::post('/login', [AdminController::class, 'login']);
});

Route::post('/password/reset-link', [RessetpasswordControll::class, 'sendResetLink']);
Route::post('/password/update', [RessetpasswordControll::class, 'updatePassword']);

// clinic routes
Route::apiResource('clinics', ClinicController::class);
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Routes for AppointmentController (Admin and User)
Route::get('/appointments', [AppointmentController::class, 'index']);
Route::post('/appointments', [AppointmentController::class, 'store']);
Route::get('/appointments/{id}', [AppointmentController::class, 'show']);
Route::put('/appointments/{id}', [AppointmentController::class, 'update']);
Route::delete('/appointments/{id}', [AppointmentController::class, 'destroy']);
Route::put('/doctor-patients/{patientId}/notes', [DoctorAppointmentController::class, 'updatePatientNotes']);
// Routes for DoctorAppointmentController (Doctor Dashboard)
Route::get('/doctor-appointments', [DoctorAppointmentController::class, 'index']);
Route::get('/doctor-patients', [DoctorAppointmentController::class, 'patients']);
// Route for DoctorAppointmentController (Doctor Dashboard)
Route::get('/doctor-appointments', [DoctorAppointmentController::class, 'index']);
// Doctor routes
Route::apiResource('vets', VetController::class);
Route::apiResource('doctors', DoctorController::class); // Add doctor routes
Route::apiResource('appointments', AppointmentController::class); // appointments routes
Route::apiResource('services', ServiceController::class);
Route::apiResource('servicesbooking', ServiceBookingController::class);
Route::apiResource('doctorrequest', DoctorRequestController::class);

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

Route::group([],function () {
    Route::post('/comments', [CommentController::class, 'store']);
    Route::put('/comments/{comment}', [CommentController::class, 'update']);
    Route::delete('/comments/{comment}', [CommentController::class, 'destroy']);
    Route::post('/comments/{comment}/reply', [CommentController::class, 'reply']);
    Route::put('/replies/{reply}', [CommentController::class, 'updateReply']);
    Route::delete('/replies/{reply}', [CommentController::class, 'destroyReply']);
});


//CHeckout route
Route::prefix('checkout')->group(function () {
    Route::post('/', [OrderContrller::class, 'store']);
    Route::get('/{id}', [OrderController::class, 'show']);
// Route::prefix('checkout')->group(function () {
//     Route::post('/', [OrderController::class, 'store']);
//     Route::get('/{id}', [OrderController::class, 'show']);
//     Route::put('/{id}', [OrderController::class, 'update']);
//     Route::delete('/{id}', [OrderController::class, 'destroy']);
// });

Route::prefix('checkout')->middleware('auth:sanctum')->group(function () {
    Route::post('/', [OrderController::class, 'store']);
    Route::put('/{id}', [OrderController::class, 'update']);
    Route::delete('/{id}', [OrderController::class, 'destroy']);
});


Route::post('/payments/{order}/stripe/confirm', [PaymentController::class, 'confirm']);
Route::post('/orders/{order}/payment-intent', [PaymentController::class, 'createStripePaymentIntent'])->name('api.orders.payment-intent');
Route::get('/orders/{id}', [OrderController::class, 'show']);
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/posts/inline-image', [PostController::class, 'uploadInlineImage']);
    Route::apiResource('posts', PostController::class);
});
// في routes/api.php
// Route::get('/payments/{order}/stripe/confirm', [PaymentController::class, 'confirm'])->name('api.payments.stripe.confirm');



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





Route::middleware('auth:sanctum')->group(function () {
    Route::post('/cart/add', [CartItemController::class, 'addToCart']);
    Route::get('/cart', [CartItemController::class, 'viewCart']);
    Route::put('/cart/update/{product_id}', [CartItemController::class, 'updateCart']);
    Route::delete('/cart/remove/{id}', [CartItemController::class, 'removeFromCart']);
    Route::delete('/cart/clear', [CartItemController::class, 'clearCart']);
    Route::get('/cart/total', [CartItemController::class, 'cartTotal']);

    // Chat routes
    Route::post('/chat/start', [ChatController::class, 'startConversation']);
    Route::get('/chat/conversations', [ChatController::class, 'getConversations']);
    Route::post('/chat/send', [ChatController::class, 'sendMessage']);
    Route::get('/chat/messages/{conversationId}', [ChatController::class, 'getMessages']);
    Route::put('/chat/messages/{messageId}/read', [ChatController::class, 'markAsRead']);
    Route::put('/chat/conversations/{conversationId}/end', [ChatController::class, 'endConversation']);
    Route::get('/chat/users-with-appointments', [ChatController::class, 'getUsersWithAppointments']);
    Route::get('/chat/existing', [ChatController::class, 'checkExistingConversation']);
});


Route::post('/feedback', [FeedbackController::class, 'store']);
Route::get('/feedback', [FeedbackController::class, 'index']);

// search route
Route::get('/search', [SearchController::class, 'search']);


Route::post('/feedback', [FeedbackController::class, 'store']);
Route::get('/feedback', [FeedbackController::class, 'index']);



Route::post('/coupons', [CouponController::class, 'store']);
