<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\CheckoutController;

// ✅ ADMIN controllers
use App\Http\Controllers\Admin\AuthController as AdminAuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\UiConfigController;
use App\Http\Controllers\Admin\DigitalProductController;
// ✅ Site controllers
use App\Http\Controllers\ProductPageController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CartController;
//use App\Http\Controllers\PostController;
use App\Http\Controllers\ReviewController;
// (Nếu có marketing controllers thì import thêm, ví dụ)
// use App\Http\Controllers\Marketing\PostController as MarketingPostController;
// use App\Http\Controllers\Marketing\BannerController as MarketingBannerController;

Route::get('/', fn () => view('landingpage'));

// Dynamic CSS route
Route::get('/css/dynamic.css', function () {
    $css = \App\Helpers\SettingsHelper::generateDynamicCss();
    return response($css)->header('Content-Type', 'text/css');
})->name('dynamic.css');

// ---------------- USER PAGES ----------------
Route::get('/san-pham', [ProductPageController::class, 'index'])->name('products');
Route::get('/san-pham/{id}', [ProductPageController::class, 'show'])->name('product.detail');

// Digital Products for customers
Route::get('/san-pham-so', [\App\Http\Controllers\DigitalProductController::class, 'index'])->name('digital-products.index');
Route::get('/san-pham-so/{id}', [\App\Http\Controllers\DigitalProductController::class, 'show'])->name('digital-products.show');
Route::post('/san-pham-so/{id}/mua', [\App\Http\Controllers\DigitalProductController::class, 'purchase'])->name('digital-products.purchase');
Route::get('/tai-xuong/{orderCode}', [\App\Http\Controllers\DigitalProductController::class, 'download'])->name('digital-products.download');
Route::get('/tai-xuong/{orderCode}/file/{fileIndex}', [\App\Http\Controllers\DigitalProductController::class, 'downloadFile'])->name('digital-products.download-file');

Route::get('/gioi-thieu', fn () => view('intro'))->name('about');

Route::get('/gio-hang', [CartController::class, 'show'])->name('cart');

// Dashboard (user) -> redirect home
Route::get('/dashboard', fn () => redirect('/'))
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

// Profile
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

// Google Auth
Route::get('/auth/google', [GoogleController::class, 'redirectToGoogle']);
Route::get('/auth/google/callback', [GoogleController::class, 'handleGoogleCallback']);

// ---------------- ADMIN AUTH ----------------
// ✅ không để trong group admin middleware
Route::get('/admin/login', [AdminAuthController::class, 'showLogin'])->name('admin.login');
Route::post('/admin/login', [AdminAuthController::class, 'login'])->name('admin.login.post');
Route::post('/admin/logout', [AdminAuthController::class, 'logout'])->name('admin.logout');

// ---------------- ADMIN AREA ----------------
Route::prefix('admin')->middleware('admin')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');
    Route::get('/ui-configuration', [UiConfigController::class, 'index'])->name('admin.ui_config');
    Route::get('/products/digital', [DigitalProductController::class, 'index'])->name('admin.products.digital');
    // UI Configuration API routes
    Route::post('/ui-configuration/update', [UiConfigController::class, 'update'])->name('admin.ui_config.update');
    Route::get('/ui-configuration/settings', [UiConfigController::class, 'getSettings'])->name('admin.ui_config.settings');
    Route::delete('/ui-configuration/file', [UiConfigController::class, 'deleteFile'])->name('admin.ui_config.delete_file');
    
    // Customers
    Route::get('/customers', [CustomerController::class, 'index'])->name('admin.customers.index');
    Route::get('/customers/create', [CustomerController::class, 'create'])->name('admin.customers.create');
    Route::post('/customers', [CustomerController::class, 'store'])->name('admin.customers.store');
    Route::delete('/customers/{id}', [CustomerController::class, 'destroy'])->name('admin.customers.destroy');

    // Orders
    Route::get('/orders', [OrderController::class, 'index'])->name('admin.orders.index');
    Route::get('/orders/list', [OrderController::class, 'list'])->name('admin.orders.list');
    Route::get('/orders/create', [OrderController::class, 'create'])->name('admin.orders.create');
    Route::post('/orders', [OrderController::class, 'store'])->name('admin.orders.store');
    Route::get('/orders/{id}', [OrderController::class, 'show'])->name('admin.orders.show');
    Route::delete('/orders/{id}', [OrderController::class, 'destroy'])->name('admin.orders.delete');
    Route::delete('/orders', [OrderController::class, 'bulkDelete'])->name('admin.orders.bulkDelete');
    Route::post('/orders/{id}/status', [OrderController::class, 'updateStatus'])->name('admin.orders.updateStatus');
    Route::get('/products/{id}/price', [OrderController::class, 'productPrice'])->name('admin.product.price');

    // Products
    Route::get('/products', [ProductController::class, 'index'])->name('admin.products.index');
    Route::get('/products/list', [ProductController::class, 'list'])->name('admin.products.list');
    Route::get('/products/quick-search', [ProductController::class, 'quickSearch'])->name('admin.products.quickSearch');
    Route::get('/products/stats', [ProductController::class, 'getStats'])->name('admin.products.stats');
    Route::get('/products/create', [ProductController::class, 'create'])->name('admin.products.create');
    Route::post('/products', [ProductController::class, 'store'])->name('admin.products.store');
    Route::get('/products/{id}/edit', [ProductController::class, 'edit'])->name('admin.products.edit');
    Route::put('/products/{id}', [ProductController::class, 'update'])->name('admin.products.update');
    Route::delete('/products/{id}', [ProductController::class, 'destroy'])->name('admin.products.destroy');
    Route::delete('/products/bulk-delete', [ProductController::class, 'bulkDelete'])->name('admin.products.bulkDelete');
    Route::delete('/products', [ProductController::class, 'bulkDelete'])->name('admin.products.bulkDelete');
    Route::post('/products/{id}/toggle-active', [ProductController::class, 'toggleActive'])->name('admin.products.toggleActive');
    
    // Digital Products
    Route::get('/digital-products', [App\Http\Controllers\Admin\DigitalProductController::class, 'index'])->name('admin.digital-products.index');
    Route::post('/digital-products', [App\Http\Controllers\Admin\DigitalProductController::class, 'store'])->name('admin.digital-products.store');
    Route::post('/digital-products/upload', [App\Http\Controllers\Admin\DigitalProductController::class, 'upload'])->name('admin.digital-products.upload');
    Route::post('/digital-products/add-link', [App\Http\Controllers\Admin\DigitalProductController::class, 'addLink'])->name('admin.digital-products.add-link');
    Route::delete('/digital-products/delete', [App\Http\Controllers\Admin\DigitalProductController::class, 'delete'])->name('admin.digital-products.delete');
    Route::post('/digital-products/{id}/toggle-active', [App\Http\Controllers\Admin\DigitalProductController::class, 'toggleActive'])->name('admin.digital-products.toggle-active');
});

// ---------------- MARKETING AREA ----------------
// Nếu bạn đã fix middleware admin.role rồi thì mở lại
Route::prefix('marketing')->middleware(['admin', 'admin.role:marketing'])->group(function () {
    Route::get('/dashboard', fn () => 'MARKETING DASHBOARD')->name('marketing.dashboard');
    // Route::resource('/posts', MarketingPostController::class)->names('marketing.posts');
    // Route::resource('/banners', MarketingBannerController::class)->names('marketing.banners');
});

// Public post detail
//Route::get('/bai-viet/{id}', [PostController::class, 'show'])->name('post.show');

// ---------------- API (USER SITE) ----------------
Route::prefix('api')->group(function () {
    Route::get('/products', [ProductPageController::class, 'apiIndex']);
    Route::get('/landing/products', [ProductPageController::class, 'landingProducts']);
    Route::get('/products/{id}/variants', [ProductPageController::class, 'getVariants']);

    Route::get('/categories', [CategoryController::class, 'apiIndex']);
    Route::get('/categories/{id}/products', [CategoryController::class, 'getProductsByCategory']);

    Route::get('/cart', [CartController::class, 'index']);
    Route::post('/cart/add', [CartController::class, 'add'])->middleware('web');
    Route::post('/cart/update', [CartController::class, 'updateQuantity'])->middleware('web');
    Route::post('/cart/delete', [CartController::class, 'delete'])->middleware('web');
    Route::post('/cart/voucher', [CartController::class, 'applyVoucher'])->middleware('web');

    // Reviews
    Route::get('/reviews/{product_id}', [ReviewController::class, 'getReviews']);
    Route::post('/reviews', [ReviewController::class, 'submitReview'])->middleware('auth:api');
    Route::get('/products/related', [ReviewController::class, 'getRelatedProducts']);
});

// ---------------- CHECKOUT ----------------
Route::get('/checkout', [CheckoutController::class, 'showCheckout'])->middleware('auth');
Route::post('/checkout/save-draft', [CheckoutController::class, 'saveDraft'])->middleware('auth');
Route::get('/order/{code}', [CheckoutController::class, 'showOrderDetail'])->name('order.detail')->middleware('auth');

Route::prefix('api')->middleware('auth')->group(function () {
    Route::get('/checkout/summary', [CheckoutController::class, 'summary']);
    Route::post('/checkout/create', [CheckoutController::class, 'createOrder']);
    Route::get('/order/{code}', [CheckoutController::class, 'getOrder']);
    Route::post('/order/complete', [CheckoutController::class, 'completeOrder']);
});
