<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PromoController;
use App\Http\Controllers\BannerController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ContentController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\TestimonialController;
use App\Http\Controllers\WelcomeController;
use App\Http\Controllers\PublicProductController;

Route::get('/', function () {
    if (Auth::check() && Auth::user()->isAdmin()) {
        return redirect()->route('admin.dashboard');
    }
    return view('welcome');
})->name('welcome');

Route::post('/logout', function () {
    Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect('/login');
})->name('logout');

// Products Routes
Route::get('/products', [PublicProductController::class, 'index'])->name('products.index');
Route::get('/products/{product}', [PublicProductController::class, 'show'])->name('products.show');

// Categories Routes
Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
Route::get('/categories/{category}', [CategoryController::class, 'show'])->name('categories.show');

// Cart Routes (Livewire handled)
Route::get('/cart', function () {
    return view('cart');
})->middleware(['auth'])->name('cart');

// Checkout Routes
Route::get('/checkout', [OrderController::class, 'checkout'])->middleware(['auth'])->name('checkout');
Route::post('/orders', [OrderController::class, 'store'])->middleware(['auth'])->name('orders.store');
Route::get('/orders/{order}', [OrderController::class, 'show'])->middleware(['auth'])->name('orders.show');

// Payment Routes
Route::post('/payments', [PaymentController::class, 'store'])->middleware(['auth'])->name('payments.store');
Route::post('/payments/notification', [PaymentController::class, 'notification'])
    ->name('payments.notification')
    ->withoutMiddleware([\Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class]);

// Testimonials Routes
Route::get('/testimonials', [TestimonialController::class, 'index'])->name('testimonials.index');
Route::post('/testimonials', [TestimonialController::class, 'store'])->middleware(['auth'])->name('testimonials.store');

// Contents Routes
Route::get('/contents/{content}', [ContentController::class, 'show'])->name('contents.show');

// Admin Routes
Route::middleware(['auth', \App\Http\Middleware\AdminMiddleware::class])->prefix('admin')->name('admin.')->group(function () {
    // Dashboard
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

    // Products Management
    Route::resource('products', ProductController::class);

    // Orders Management
    Route::get('/orders', [OrderController::class, 'adminIndex'])->name('orders.index');
    Route::get('/orders/{order}', [OrderController::class, 'adminShow'])->name('orders.show');
    Route::put('/orders/{order}', [OrderController::class, 'update'])->name('orders.update');

    // Categories Management
    Route::resource('categories', CategoryController::class);

    // Banners Management
    Route::resource('banners', BannerController::class);

    // Promos Management
    Route::resource('promos', PromoController::class);

    // Contents Management
    Route::resource('contents', ContentController::class);

    // Testimonials Management
    Route::resource('testimonials', TestimonialController::class);

    // Reports
    Route::get('/reports/orders/export', [ReportController::class, 'exportOrders'])->name('reports.orders.export');
    Route::get('/reports/products/export', [ReportController::class, 'exportProducts'])->name('reports.products.export');
});

// User Dashboard and Profile
Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

// Transaction History (Livewire)
Route::get('/transaction-history', function () {
    return view('transaction-history');
})->middleware(['auth'])->name('transaction.history');

require __DIR__.'/auth.php';
