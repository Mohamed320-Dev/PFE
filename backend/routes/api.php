<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\ExerciseController;
use App\Http\Controllers\FavoriteExerciseController;
use App\Http\Controllers\ForgetController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\SubscriptionController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::post('/your-endpoint', [ExerciseController::class, 'store']);
Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


// User management routes (admin only)
Route::middleware(['auth:sanctum', 'role:admin'])->group(function () {
    Route::get('/users', [UserController::class, 'index']);
    Route::get('/users/{id}', [UserController::class, 'show']);
    Route::post('/users', [UserController::class, 'store']);
    Route::put('/users/{id}', [UserController::class, 'update']);
    Route::delete('/users/{id}', [UserController::class, 'destroy']);
    Route::get('/user-stats', [UserController::class, 'stats']);
});


Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');

// Admin routes
Route::prefix('admin')->middleware(['auth:sanctum', 'role:admin'])->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard']);
});

// Client routes
Route::prefix('client')->middleware(['auth:sanctum', 'role:client'])->group(function () {
    Route::get('/dashboard', [ClientController::class, 'dashboard']);
});

// Admin dashboard route
Route::middleware(['auth:sanctum', 'role:admin'])->group(function () {
    Route::get('/admin/dashboard', function () {
        return response()->json([
            'message' => 'Admin dashboard data',
            'data' => [
                'totalUsers' => \App\Models\User::count(),
                'newPosts' => \App\Models\Post::where('created_at', '>=', now()->subDays(7))->count(),
                'activeSessions' => \Illuminate\Support\Facades\DB::table('sessions')->where('last_activity', '>=', now()->subMinutes(15)->getTimestamp())->count(),
            ]
        ]);
    });
});

// Client dashboard route
Route::middleware(['auth:sanctum', 'role:client'])->group(function () {
    Route::get('/client/dashboard', function () {
        $user = request()->user();
        return response()->json([
            'message' => 'Client dashboard data',
            'data' => [
                'yourPosts' => \App\Models\Post::where('user_id', $user->id)->count(),
                'notifications' => 5, // You would fetch actual notifications here
                'messages' => 8, // You would fetch actual messages here
            ]
        ]);
    });
});

// Exercise routes
Route::middleware(['auth:sanctum', 'role:admin'])->group(function () {
    Route::post('/exercises', [ExerciseController::class, 'store']);
    Route::put('/exercises/{id}', [ExerciseController::class, 'update']);
    Route::delete('/exercises/{id}', [ExerciseController::class, 'destroy']);
});

// Public exercise routes
Route::get('/exercises', [ExerciseController::class, 'index']);
Route::get('/exercises/{id}', [ExerciseController::class, 'show']);

// Favorite exercise routes (for clients)
Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('/exercises/{id}/favorite', [FavoriteExerciseController::class, 'toggleFavorite']);
    Route::get('/favorites', [FavoriteExerciseController::class, 'getFavorites']);
    Route::post('/process-payment', [PaymentController::class, 'processPayment']);
});

Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'show']);
    Route::post('/profile', [ProfileController::class, 'update']);
});

// Product management routes (admin only)
Route::middleware(['auth:sanctum', 'role:admin'])->group(function () {
    Route::post('/products', [ProductController::class, 'store']);
    Route::put('/products/{id}', [ProductController::class, 'update']);
    Route::delete('/products/{id}', [ProductController::class, 'destroy']);
    Route::get('/admin/products', [ProductController::class, 'index']);
});



// Public product routes
Route::get('/products', [ProductController::class, 'index']);
Route::get('/products/{id}', [ProductController::class, 'show']);

Route::middleware('auth:sanctum')->group(function () {
    // Cart Routes
    Route::get('/cart', [CartController::class, 'index']);
    Route::post('/cart/add', [CartController::class, 'addToCart']);
    Route::put('/cart/{id}', [CartController::class, 'updateCartItem']);
    Route::delete('/cart/{id}', [CartController::class, 'removeCartItem']);
    Route::delete('/cart', [CartController::class, 'clearCart']);

    // Checkout Routes
    Route::post('/checkout', [CheckoutController::class, 'checkout']);
    Route::get('/order-history', [CheckoutController::class, 'getOrderHistory']);
});

// Subscription management - Public routes
Route::get('/plans', [SubscriptionController::class, 'getPlans']);

// Subscription management - Client routes
Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('/subscribe', [SubscriptionController::class, 'createSubscription']);
    Route::get('/my-subscription', [SubscriptionController::class, 'getUserSubscription']);
    Route::post('/my-subscription/cancel', [SubscriptionController::class, 'cancelUserSubscription']);
});
// Admin dashboard route
Route::middleware(['auth:sanctum', 'role:admin'])->group(function () {
    Route::get('/admin/dashboard', function () {
        return response()->json([
            'message' => 'Admin dashboard data',
            'data' => [
                'totalClients' => \App\Models\User::where('role', 'client')->count(),
                'numberSubscriptions' => \App\Models\Subscription::count(),
                'numberSales' => \App\Models\Order::count(),
            ]
        ]);
    });
});
// Subscription management - Admin routes
Route::middleware(['auth:sanctum', 'role:admin'])->group(function () {
    Route::get('/admin/subscriptions', [SubscriptionController::class, 'adminGetSubscriptions']);
    Route::delete('/admin/subscriptions/{id}', [SubscriptionController::class, 'cancelSubscription']);

    // Plan management routes
    Route::post('/admin/plans', [SubscriptionController::class, 'createPlan']);
    Route::put('/admin/plans/{planType}', [SubscriptionController::class, 'updatePlan']);
    Route::delete('/admin/plans/{planType}', [SubscriptionController::class, 'deletePlan']);
});


Route::post('/forgot-password', [ForgetController::class, 'sendResetCode']);