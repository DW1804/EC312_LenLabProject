<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

// ✅ Facades / Helpers
use App\Helpers\SettingsHelper;
use App\Helpers\ShippingHelper;
use App\Models\Province;
use App\Models\Address;

// ✅ User Controllers
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\ProductPageController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CustomerReviewController;
use App\Http\Controllers\VoucherController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\AddressController;
use App\Http\Controllers\OrderController as UserOrderController; // Alias để tránh nhầm với Admin
use App\Http\Controllers\DigitalProductController as UserDigitalProductController; // Alias để tránh nhầm với Admin

// ✅ ADMIN Controllers
use App\Http\Controllers\Admin\AuthController as AdminAuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\UiConfigController;
use App\Http\Controllers\Admin\DigitalProductController;
use App\Http\Controllers\Admin\ReviewController as AdminReviewController;
use App\Http\Controllers\Admin\DashboardStatsController;
// (Nếu có marketing controllers thì import thêm, ví dụ)
// use App\Http\Controllers\Marketing\PostController as MarketingPostController;
// use App\Http\Controllers\Marketing\BannerController as MarketingBannerController;

Route::get('/', fn () => view('landingpage'));

// Dynamic CSS route
Route::get('/css/dynamic.css', function () {
    $css = SettingsHelper::generateDynamicCss();
    return response($css)->header('Content-Type', 'text/css');
})->name('dynamic.css');

// ---------------- USER PAGES ----------------
Route::get('/san-pham', [ProductPageController::class, 'index'])->name('products');
Route::get('/san-pham/{id}', [ProductPageController::class, 'show'])->name('product.detail');

// Digital Products for customers
Route::get('/san-pham-so', [UserDigitalProductController::class, 'index'])->name('digital-products.index');
Route::get('/san-pham-so/{id}', [UserDigitalProductController::class, 'show'])->name('digital-products.show');
Route::post('/san-pham-so/{id}/mua', [UserDigitalProductController::class, 'purchase'])->name('digital-products.purchase');
Route::get('/tai-xuong/{orderCode}', [UserDigitalProductController::class, 'download'])->name('digital-products.download');
Route::get('/tai-xuong/{orderCode}/file/{fileIndex}', [UserDigitalProductController::class, 'downloadFile'])->name('digital-products.download-file');

Route::get('/gioi-thieu', fn () => view('intro'))->name('about');

Route::get('/cart', [CartController::class, 'show'])->name('cart');

Route::get('/vouchers', [VoucherController::class, 'index'])->name('vouchers');

// Dashboard (user) -> redirect home
Route::get('/dashboard', fn () => redirect('/'))
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

// Profile
Route::middleware('auth')->group(function () {
    Route::get('/profile', function () {
        try {
            $user = Auth::user();
            if (!$user) {
                return redirect()->route('login')->with('error', 'Vui lòng đăng nhập');
            }
            return view('profile', compact('user'));
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    })->name('profile');
    
    Route::get('/addresses', function() {
        return view('addresses');
    })->name('addresses.index');
    Route::get('/addresses/create', function() {
        return view('address-form');
    })->name('addresses.create');
    Route::get('/addresses/{id}/edit', function($id) {
        $address = Address::where('user_id', Auth::id())->findOrFail($id);
        return view('address-form', compact('address'));
    })->name('addresses.edit');
    
    // Profile edit routes
    Route::get('/profile/edit', function() {
        return view('profile-edit');
    })->name('profile.edit');
    Route::post('/profile/update', [ProfileController::class, 'update'])->name('profile.update');
    
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

// Google Auth
Route::get('/auth/google', [GoogleController::class, 'redirectToGoogle']);
Route::get('/auth/google/callback', [GoogleController::class, 'handleGoogleCallback']);

// ---------------- ADMIN AUTH ----------------
Route::get('/admin/login', [AdminAuthController::class, 'showLogin'])->name('admin.login');
Route::post('/admin/login', [AdminAuthController::class, 'login'])->name('admin.login.post');
Route::post('/admin/logout', [AdminAuthController::class, 'logout'])->name('admin.logout');

// ---------------- ADMIN AREA ----------------
Route::prefix('admin')->middleware('admin')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');
    Route::get('/dashboard-enhanced', function() {
        return view('admin.dashboard-enhanced');
    })->name('admin.dashboard.enhanced');
    Route::get('/ui-configuration', [UiConfigController::class, 'index'])->name('admin.ui_config');
    Route::get('/products/digital', [DigitalProductController::class, 'index'])->name('admin.products.digital');
    // Dashboard Stats API
    Route::get('/dashboard/stats/overview', [DashboardStatsController::class, 'overview'])->name('admin.dashboard.stats.overview');
    Route::get('/dashboard/stats/reviews-chart', [DashboardStatsController::class, 'reviewsChart'])->name('admin.dashboard.stats.reviews-chart');
    Route::get('/dashboard/stats/orders-chart', [DashboardStatsController::class, 'ordersChart'])->name('admin.dashboard.stats.orders-chart');
    Route::get('/dashboard/stats/top-products', [DashboardStatsController::class, 'topProducts'])->name('admin.dashboard.stats.top-products');
    Route::get('/dashboard/stats/recent-activity', [DashboardStatsController::class, 'recentActivity'])->name('admin.dashboard.stats.recent-activity');
    Route::get('/dashboard/stats/rating-distribution', [DashboardStatsController::class, 'ratingDistribution'])->name('admin.dashboard.stats.rating-distribution');

    // Route Đánh giá
    Route::get('/reviews', [AdminReviewController::class, 'index'])->name('admin.reviews.index');
    Route::post('/reviews/{review}/approve', [AdminReviewController::class, 'approve'])->name('admin.reviews.approve');
    Route::post('/reviews/{review}/hide', [AdminReviewController::class, 'hide'])->name('admin.reviews.hide');
    Route::delete('/reviews/{review}', [AdminReviewController::class, 'destroy'])->name('admin.reviews.destroy');
    Route::post('/reviews/bulk-action', [AdminReviewController::class, 'bulkAction'])->name('admin.reviews.bulk-action');
    Route::get('/reviews/{review}/show', [AdminReviewController::class, 'show'])->name('admin.reviews.show');
    Route::get('/reviews/stats', [AdminReviewController::class, 'stats'])->name('admin.reviews.stats');
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
    // Dòng dưới bị trùng route delete/products (bulk delete), nên giữ 1 cái
    // Route::delete('/products', [ProductController::class, 'bulkDelete'])->name('admin.products.bulkDelete'); 
    Route::post('/products/{id}/toggle-active', [ProductController::class, 'toggleActive'])->name('admin.products.toggleActive');
    
    // Digital Products (Admin)
    Route::get('/digital-products', [DigitalProductController::class, 'index'])->name('admin.digital-products.index');
    Route::post('/digital-products', [DigitalProductController::class, 'store'])->name('admin.digital-products.store');
    Route::post('/digital-products/upload', [DigitalProductController::class, 'upload'])->name('admin.digital-products.upload');
    Route::post('/digital-products/add-link', [DigitalProductController::class, 'addLink'])->name('admin.digital-products.add-link');
    Route::delete('/digital-products/delete', [DigitalProductController::class, 'delete'])->name('admin.digital-products.delete');
    Route::post('/digital-products/{id}/toggle-active', [DigitalProductController::class, 'toggleActive'])->name('admin.digital-products.toggle-active');
});

// ---------------- MARKETING AREA ----------------
Route::prefix('marketing')->middleware(['admin', 'admin.role:marketing'])->group(function () {
    Route::get('/dashboard', fn () => 'MARKETING DASHBOARD')->name('marketing.dashboard');
});

// Checkout routes
Route::middleware('auth')->group(function () {
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout');
    Route::get('/checkout/confirm', [CheckoutController::class, 'confirm'])->name('checkout.confirm');
    Route::get('/checkout/payment', [CheckoutController::class, 'payment'])->name('checkout.payment');
    Route::get('/checkout/bank-transfer', [CheckoutController::class, 'bankTransfer'])->name('checkout.bank-transfer');
    Route::get('/order-success', [CheckoutController::class, 'orderSuccess'])->name('order.success');
});

// Policy page
Route::get('/chinh-sach', function () {
    return view('policy');
})->name('policy');

// Order detail routes (User)
Route::middleware('auth')->group(function () {
    Route::get('/orders', [UserOrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{orderId}', [UserOrderController::class, 'show'])->name('order.detail');
    Route::post('/orders/{orderId}/cancel', [UserOrderController::class, 'cancel'])->name('order.cancel');
});

// API Routes for user-facing website
Route::prefix('api')->group(function () {
    Route::get('/products', [ProductPageController::class, 'apiIndex']);
    Route::get('/products/{id}/variants', [ProductPageController::class, 'getVariants']);
    Route::get('/landing/products', [ProductPageController::class, 'landingProducts']);
    Route::get('/categories', [CategoryController::class, 'apiIndex']);
    Route::get('/categories/{id}/products', [CategoryController::class, 'getProductsByCategory']);
    
    // Cart APIs
    Route::get('/cart', [CartController::class, 'index']);
    Route::post('/cart/add', [CartController::class, 'add'])->middleware('web');
    Route::post('/cart/update', [CartController::class, 'updateQuantity'])->middleware('web');
    Route::post('/cart/delete', [CartController::class, 'delete'])->middleware('web');
    Route::post('/cart/voucher', [CartController::class, 'applyVoucher'])->middleware('web');
    
    // Voucher APIs
    Route::get('/vouchers', [VoucherController::class, 'getVouchers']);
    Route::post('/vouchers/apply', [VoucherController::class, 'applyVoucher'])->middleware('web');
    Route::post('/vouchers/remove', [VoucherController::class, 'removeVoucher'])->middleware('web');
    
    // Location APIs
    Route::get('/provinces', [LocationController::class, 'getProvinces']);
    Route::get('/provinces/{id}/wards', [LocationController::class, 'getWardsByProvince']);
    Route::get('/provinces/{slug}/wards', [LocationController::class, 'getWardsByProvinceSlug']);
    Route::get('/shipping-fee/{provinceId}', function($provinceId) {
        $province = Province::find($provinceId);
        if (!$province) {
            return response()->json(['success' => false, 'message' => 'Tỉnh không tồn tại']);
        }
        
        $shippingFee = ShippingHelper::calculateShippingFee($province->name);
        $zone = ShippingHelper::getZone($province->name);
        
        return response()->json([
            'success' => true,
            'shipping_fee' => $shippingFee,
            'zone' => $zone,
            'province_name' => $province->name
        ]);
    });
    
    // Address & Checkout APIs
    Route::middleware('auth')->group(function () {
        Route::get('/user/addresses', [AddressController::class, 'index']);
        Route::post('/user/addresses', [AddressController::class, 'store']);
        Route::put('/user/addresses/{id}', [AddressController::class, 'update']);
        Route::delete('/user/addresses/{id}', [AddressController::class, 'destroy']);
        Route::post('/user/addresses/{id}/default', [AddressController::class, 'setDefault']);
        
        Route::post('/checkout/set-selected-items', [CheckoutController::class, 'setSelectedItems']);
        Route::post('/checkout/set-address', [CheckoutController::class, 'setAddress']);
        Route::post('/checkout/set-note', [CheckoutController::class, 'setNote']);
        Route::post('/checkout/set-payment-method', [CheckoutController::class, 'setPaymentMethod']);
        Route::post('/checkout/prepare-bank-transfer', [CheckoutController::class, 'prepareBankTransfer']);
        Route::post('/checkout/complete-bank-transfer', [CheckoutController::class, 'completeBankTransfer']);
        Route::post('/checkout/create-order', [CheckoutController::class, 'createOrder']);
    });

    // Reviews
    Route::get('/reviews/{product_id}', [CustomerReviewController::class, 'getProductReviews']);
    Route::post('/reviews', [CustomerReviewController::class, 'store'])->middleware('auth');
    Route::put('/reviews/{review}', [CustomerReviewController::class, 'update'])->middleware('auth');
    Route::delete('/reviews/{review}', [CustomerReviewController::class, 'destroy'])->middleware('auth');
    Route::get('/user/reviews', [CustomerReviewController::class, 'getUserReviews'])->middleware('auth');
});
