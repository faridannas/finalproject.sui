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
 
 Route::get('/', WelcomeController::class)->name('welcome');




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
Route::get('/categories/{category}', [CategoryController::class, 'show'])->middleware(['auth'])->name('categories.show');

// Cart Routes (Livewire handled)
Route::get('/cart', function () {
    return view('cart');
})->middleware(['auth'])->name('cart');

// Cart Actions
Route::post('/cart/add', [App\Http\Controllers\CartController::class, 'addToCart'])->middleware(['auth'])->name('cart.add');
Route::post('/cart/update', [App\Http\Controllers\CartController::class, 'updateCart'])->middleware(['auth'])->name('cart.update');
Route::post('/cart/buy-now', [App\Http\Controllers\CartController::class, 'buyNow'])->middleware(['auth'])->name('cart.buy-now');

// Checkout Routes
Route::get('/checkout', [OrderController::class, 'checkout'])->middleware(['auth'])->name('checkout');
Route::post('/orders', [OrderController::class, 'store'])->middleware(['auth'])->name('orders.store');
Route::get('/orders', [OrderController::class, 'index'])->middleware(['auth'])->name('orders.index');
Route::get('/orders/{order}', [OrderController::class, 'show'])->middleware(['auth'])->name('orders.show');
Route::put('/orders/{order}/cancel', [OrderController::class, 'update'])->middleware(['auth'])->name('orders.cancel');
Route::delete('/orders/{order}', [OrderController::class, 'destroy'])->middleware(['auth'])->name('orders.destroy');

// Payment Routes
// Payment Routes
Route::get('/payments/{order}', [PaymentController::class, 'show'])->middleware(['auth'])->name('payment.show');
Route::post('/payments/{payment}/upload', [PaymentController::class, 'uploadProof'])->middleware(['auth'])->name('payments.upload');

// Admin Payment Actions
Route::post('/admin/payments/{payment}/confirm', [PaymentController::class, 'confirm'])->middleware(['auth', \App\Http\Middleware\AdminMiddleware::class])->name('admin.payments.confirm');
Route::post('/admin/payments/{payment}/reject', [PaymentController::class, 'reject'])->middleware(['auth', \App\Http\Middleware\AdminMiddleware::class])->name('admin.payments.reject');


// Testimonials Routes
Route::get('/testimonials', [TestimonialController::class, 'index'])->name('testimonials.index');
Route::post('/testimonials', [TestimonialController::class, 'store'])->middleware(['auth'])->name('testimonials.store');

// Contents Routes
Route::get('/contents/{content}', [ContentController::class, 'show'])->name('contents.show');

// Admin Routes
Route::middleware(['auth', \App\Http\Middleware\AdminMiddleware::class])->prefix('admin')->name('admin.')->group(function () {
    // Dashboard
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/dashboard-data', [AdminController::class, 'getDashboardData'])->name('dashboard.data');

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

    // Admin Profile & Settings
    Route::get('/profile', [App\Http\Controllers\AdminProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [App\Http\Controllers\AdminProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [App\Http\Controllers\AdminProfileController::class, 'updatePassword'])->name('profile.password');
    Route::patch('/settings', [App\Http\Controllers\AdminProfileController::class, 'updateSettings'])->name('settings.update');
});

// User Dashboard and Profile
use App\Http\Controllers\HomeController;

Route::get('dashboard', [HomeController::class, 'dashboard'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::get('profile', [\App\Http\Controllers\ProfileController::class, 'edit'])
    ->middleware(['auth'])
    ->name('profile.edit');

Route::put('profile', [\App\Http\Controllers\ProfileController::class, 'update'])
    ->middleware(['auth'])
    ->name('profile.update');

Route::put('profile/password', [\App\Http\Controllers\ProfileController::class, 'updatePassword'])
    ->middleware(['auth'])
    ->name('profile.password.update');

// Transaction History (Livewire)
Route::get('/transaction-history', function () {
    return view('transaction-history');
})->middleware(['auth'])->name('transaction.history');


require __DIR__.'/auth.php';
