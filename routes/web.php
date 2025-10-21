<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\CatController;

Route::get('/', [\App\Http\Controllers\User\HomeController::class, 'index'])->name('home');

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');

    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.post');
});

Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

Route::get('/products', [\App\Http\Controllers\User\ProductController::class, 'index'])->name('user.products');
Route::get('/products/{product}', [\App\Http\Controllers\User\ProductController::class, 'show'])->name('user.products.show');

Route::get('/cats', [\App\Http\Controllers\User\CatController::class, 'index'])->name('user.cats');
Route::get('/cats/{cat}/book', [\App\Http\Controllers\User\CatController::class, 'showBookingForm'])->name('user.cats.book');
Route::post('/cats/{cat}/book', [\App\Http\Controllers\User\CatController::class, 'book'])->name('user.cats.book.store');

Route::get('/feedback', [\App\Http\Controllers\User\FeedbackController::class, 'index'])->name('user.feedback');
Route::post('/feedback', [\App\Http\Controllers\User\FeedbackController::class, 'store'])->middleware('auth')->name('user.feedback.store');

Route::middleware('auth')->group(function () {
    Route::get('/orders', [\App\Http\Controllers\User\OrderController::class, 'index'])->name('user.orders');

    Route::get('/cart', [\App\Http\Controllers\User\CartController::class, 'index'])->name('user.cart');
    Route::post('/cart/{product}', [\App\Http\Controllers\User\CartController::class, 'add'])->name('user.cart.add');
    Route::patch('/cart/{cart}', [\App\Http\Controllers\User\CartController::class, 'update'])->name('user.cart.update');
    Route::delete('/cart/{cart}', [\App\Http\Controllers\User\CartController::class, 'destroy'])->name('user.cart.destroy');

    // Checkout
    Route::get('/checkout', [\App\Http\Controllers\User\CheckoutController::class, 'show'])
        ->name('user.checkout');
    Route::post('/checkout', [\App\Http\Controllers\User\CheckoutController::class, 'placeOrder'])
        ->name('user.checkout.place');

    Route::get('/change-password', [\App\Http\Controllers\User\ProfileController::class, 'showChangePassword'])->name('user.change-password');
    Route::patch('/change-password', [\App\Http\Controllers\User\ProfileController::class, 'changePassword'])->name('user.change-password.update');

    Route::get('/bookings', [\App\Http\Controllers\User\BookingController::class, 'index'])->name('user.bookings');
});

Route::middleware(['auth','is_admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [\App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');
    Route::get('/stats', [\App\Http\Controllers\Admin\DashboardController::class, 'stats'])->name('stats');

    Route::resource('categories', CategoryController::class)->except(['show']);
    Route::resource('products', ProductController::class)->except(['show']);
    Route::patch('products/{product}/toggle-availability', [ProductController::class, 'toggleAvailability'])->name('products.toggleAvailability');
    Route::resource('cats', CatController::class)->except(['show']);

    Route::resource('orders', \App\Http\Controllers\Admin\OrderController::class)->only(['index', 'show', 'destroy']);
    Route::patch('orders/{order}/status', [\App\Http\Controllers\Admin\OrderController::class, 'updateStatus'])->name('orders.updateStatus');

    Route::resource('bookings', \App\Http\Controllers\Admin\BookingController::class)->only(['index', 'show', 'destroy']);
    Route::patch('bookings/{booking}/status', [\App\Http\Controllers\Admin\BookingController::class, 'updateStatus'])->name('bookings.updateStatus');

    Route::resource('feedbacks', \App\Http\Controllers\Admin\FeedbackController::class)->only(['index', 'destroy']);

    Route::resource('customers', \App\Http\Controllers\Admin\CustomerController::class)->only(['index', 'destroy']);
    Route::patch('customers/{customer}/toggle', [\App\Http\Controllers\Admin\CustomerController::class, 'toggleActive'])->name('customers.toggleActive');
});
