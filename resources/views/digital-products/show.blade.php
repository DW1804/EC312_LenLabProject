@extends('layouts.app')

@section('title', $product->name . ' - ' . getSiteName())

@section('content')
<div class="min-h-screen bg-gray-50 dark:bg-gray-900 py-12">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Breadcrumb -->
        <div class="mb-8">
            <nav class="flex items-center gap-2 text-sm text-gray-500 dark:text-gray-400">
                <a href="{{ route('digital-products.index') }}" class="hover:text-primary transition-colors">Sản phẩm số</a>
                <span>/</span>
                <span class="text-gray-900 dark:text-white">{{ $product->name }}</span>
            </nav>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            <!-- Product Info -->
            <div class="lg:col-span-2">
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                    
                    <!-- Thumbnail -->
                    <div class="aspect-video bg-gray-100 dark:bg-gray-700 relative">
                        @if($product->thumbnail)
                            <img src="{{ $product->thumbnail_url }}" 
                                 alt="{{ $product->name }}" 
                                 class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full flex items-center justify-center">
                                <span class="material-icons-round text-6xl text-gray-400">
                                    @switch($product->type)
                                        @case('course') school @break
                                        @case('file') description @break
                                        @default link
                                    @endswitch
                                </span>
                            </div>
                        @endif
                        
                        <!-- Type Badge -->
                        <div class="absolute top-4 left-4">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                                @switch($product->type)
                                    @case('course') bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300 @break
                                    @case('file') bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300 @break
                                    @default bg-purple-100 text-purple-800 dark:bg-purple-900/30 dark:text-purple-300
                                @endswitch
                            ">
                                @switch($product->type)
                                    @case('course') Khóa học @break
                                    @case('file') Tài liệu @break
                                    @default Link
                                @endswitch
                            </span>
                        </div>
                    </div>

                    <!-- Content -->
                    <div class="p-6">
                        <h1 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">
                            {{ $product->name }}
                        </h1>
                        
                        @if($product->description)
                        <div class="prose prose-gray dark:prose-invert max-w-none mb-6">
                            <p class="text-gray-600 dark:text-gray-400 leading-relaxed">
                                {{ $product->description }}
                            </p>
                        </div>
                        @endif

                        <!-- Product Details -->
                        <div class="grid grid-cols-2 gap-4 mb-6">
                            <div class="bg-gray-50 dark:bg-gray-800 rounded-lg p-4">
                                <div class="flex items-center gap-2 mb-2">
                                    <span class="material-icons-round text-primary text-sm">file_download</span>
                                    <span class="text-sm font-medium text-gray-900 dark:text-white">Giới hạn tải</span>
                                </div>
                                <p class="text-lg font-bold text-gray-900 dark:text-white">{{ $product->download_limit }} lần</p>
                            </div>
                            
                            <div class="bg-gray-50 dark:bg-gray-800 rounded-lg p-4">
                                <div class="flex items-center gap-2 mb-2">
                                    <span class="material-icons-round text-primary text-sm">access_time</span>
                                    <span class="text-sm font-medium text-gray-900 dark:text-white">Thời gian truy cập</span>
                                </div>
                                <p class="text-lg font-bold text-gray-900 dark:text-white">{{ $product->access_days }} ngày</p>
                            </div>
                        </div>

                        @if($product->instructions)
                        <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-4">
                            <h3 class="text-sm font-semibold text-blue-900 dark:text-blue-300 mb-2">
                                Hướng dẫn sử dụng
                            </h3>
                            <p class="text-sm text-blue-800 dark:text-blue-200">
                                {{ $product->instructions }}
                            </p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Purchase Card -->
            <div class="lg:col-span-1">
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 sticky top-6">
                    
                    <!-- Price -->
                    <div class="text-center mb-6">
                        <div class="text-3xl font-bold text-primary mb-2">
                            {{ $product->formatted_price }}
                        </div>
                        <p class="text-sm text-gray-500 dark:text-gray-400">
                            Thanh toán một lần
                        </p>
                    </div>

                    <!-- Purchase Form -->
                    <div class="space-y-4">
                        <button type="button" id="add-to-cart-btn" 
                                data-product-id="{{ $product->id }}"
                                data-product-name="{{ $product->name }}"
                                data-product-price="{{ $product->price }}"
                                data-product-type="digital"
                                class="w-full bg-primary text-white font-semibold py-3 px-4 rounded-lg hover:bg-primary-hover transition-colors flex items-center justify-center gap-2">
                            <span class="material-icons-round">add_shopping_cart</span>
                            Thêm vào giỏ hàng
                        </button>
                        
                        <p class="text-xs text-gray-500 dark:text-gray-400 text-center">
                            Sau khi thanh toán, link tải xuống sẽ được gửi qua email
                        </p>
                    </div>

                    <!-- Features -->
                    <div class="mt-6 pt-6 border-t border-gray-200 dark:border-gray-700">
                        <h4 class="text-sm font-semibold text-gray-900 dark:text-white mb-3">
                            Bạn sẽ nhận được:
                        </h4>
                        <ul class="space-y-2 text-sm text-gray-600 dark:text-gray-400">
                            <li class="flex items-center gap-2">
                                <span class="w-2 h-2 bg-green-500 rounded-full"></span>
                                Truy cập ngay lập tức
                            </li>
                            <li class="flex items-center gap-2">
                                <span class="w-2 h-2 bg-green-500 rounded-full"></span>
                                Tải xuống {{ $product->download_limit }} lần
                            </li>
                            <li class="flex items-center gap-2">
                                <span class="w-2 h-2 bg-green-500 rounded-full"></span>
                                Hỗ trợ qua email
                            </li>
                            @if($product->files && count($product->files) > 0)
                            <li class="flex items-center gap-2">
                                <span class="w-2 h-2 bg-green-500 rounded-full"></span>
                                {{ count($product->files) }} file tài liệu
                            </li>
                            @endif
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <!-- Related Products -->
        <div class="mt-12">
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">
                Sản phẩm khác
            </h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @php
                    $relatedProducts = \App\Models\DigitalProduct::active()
                        ->where('id', '!=', $product->id)
                        ->where('type', $product->type)
                        ->limit(3)
                        ->get();
                @endphp
                
                @foreach($relatedProducts as $related)
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden hover:shadow-lg transition-shadow">
                    <div class="aspect-video bg-gray-100 dark:bg-gray-700 relative">
                        @if($related->thumbnail)
                            <img src="{{ $related->thumbnail_url }}" 
                                 alt="{{ $related->name }}" 
                                 class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full flex items-center justify-center">
                                <span class="material-icons-round text-4xl text-gray-400">description</span>
                            </div>
                        @endif
                    </div>
                    
                    <div class="p-4">
                        <h3 class="font-semibold text-gray-900 dark:text-white mb-2 line-clamp-2">
                            {{ $related->name }}
                        </h3>
                        <div class="flex items-center justify-between">
                            <span class="text-lg font-bold text-primary">
                                {{ $related->formatted_price }}
                            </span>
                            <a href="{{ route('digital-products.show', $related->id) }}" 
                               class="text-sm text-primary hover:text-primary-hover font-medium">
                                Xem chi tiết
                            </a>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

    </div>
</div>

<!-- Success Notification -->
<div id="success-notification" class="fixed top-4 right-4 z-50 transform translate-x-full transition-transform duration-300">
    <div class="bg-green-500 text-white px-6 py-4 rounded-lg shadow-lg flex items-center gap-3">
        <span class="material-icons-round">check_circle</span>
        <span>Đã thêm vào giỏ hàng thành công!</span>
        <button onclick="hideNotification()" class="ml-2 hover:opacity-70">
            <span class="material-icons-round text-sm">close</span>
        </button>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const addToCartBtn = document.getElementById('add-to-cart-btn');
    
    if (addToCartBtn) {
        addToCartBtn.addEventListener('click', function() {
            const productId = this.dataset.productId;
            const productName = this.dataset.productName;
            const productPrice = this.dataset.productPrice;
            const productType = this.dataset.productType;
            
            // Disable button temporarily
            this.disabled = true;
            this.innerHTML = '<span class="material-icons-round animate-spin">refresh</span> Đang thêm...';
            
            // Add to cart via API
            fetch('/api/cart/add', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({
                    product_id: productId,
                    quantity: 1,
                    product_type: productType,
                    name: productName,
                    price: productPrice
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showSuccessNotification();
                    // Update cart count if exists
                    updateCartCount();
                } else {
                    alert('Có lỗi xảy ra: ' + (data.message || 'Không thể thêm vào giỏ hàng'));
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Có lỗi xảy ra khi thêm vào giỏ hàng');
            })
            .finally(() => {
                // Re-enable button
                this.disabled = false;
                this.innerHTML = '<span class="material-icons-round">add_shopping_cart</span> Thêm vào giỏ hàng';
            });
        });
    }
});

function showSuccessNotification() {
    const notification = document.getElementById('success-notification');
    notification.classList.remove('translate-x-full');
    notification.classList.add('translate-x-0');
    
    // Auto hide after 3 seconds
    setTimeout(() => {
        hideNotification();
    }, 3000);
}

function hideNotification() {
    const notification = document.getElementById('success-notification');
    notification.classList.remove('translate-x-0');
    notification.classList.add('translate-x-full');
}

function updateCartCount() {
    // Update cart count in header if exists
    fetch('/api/cart')
        .then(response => response.json())
        .then(data => {
            if (data.success && data.cart) {
                const cartCount = data.cart.reduce((total, item) => total + item.quantity, 0);
                const cartCountElements = document.querySelectorAll('.cart-count');
                cartCountElements.forEach(el => {
                    el.textContent = cartCount;
                    if (cartCount > 0) {
                        el.classList.remove('hidden');
                    }
                });
            }
        })
        .catch(error => console.error('Error updating cart count:', error));
}
</script>
@endpush
@endsection