<!DOCTYPE html>
<html class="dark" lang="vi">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $product->name }} - LENLAB</title>
    <link href="https://fonts.googleapis.com/css2?family=Spline+Sans:wght@300;400;500;600;700&family=Noto+Sans:wght@400;500;700&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        "primary": "#FAC638",
                        "background-dark": "#1a1a1a",
                        "surface-dark": "#2d2d2d",
                        "card-dark": "#333333"
                    },
                    fontFamily: {
                        "display": ["Spline Sans", "sans-serif"],
                        "body": ["Noto Sans", "sans-serif"]
                    }
                }
            }
        }
    </script>
    <style>
        body {
            font-family: 'Spline Sans', sans-serif;
            background: #1a1a1a;
            min-height: 100vh;
            padding-bottom: 100px; /* Space for fixed bottom bar */
        }
        
        .product-container {
            background: #1a1a1a;
            max-width: 400px;
            margin: 0 auto;
            min-height: 100vh;
        }
        
        .image-carousel {
            position: relative;
            height: 300px;
            overflow: hidden;
            touch-action: pan-y;
        }
        
        .carousel-image {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: all 0.3s ease;
        }
        
        .carousel-image.changing {
            opacity: 0.7;
            transform: scale(0.98);
        }
        
        .carousel-dots {
            position: absolute;
            bottom: 16px;
            left: 50%;
            transform: translateX(-50%);
            display: flex;
            gap: 8px;
        }
        
        .carousel-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.4);
            transition: all 0.3s ease;
            cursor: pointer;
        }
        
        .carousel-dot.active {
            background: #FAC638;
            transform: scale(1.2);
        }
        
        .carousel-nav {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            background: rgba(0, 0, 0, 0.5);
            color: white;
            border: none;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s ease;
            z-index: 10;
        }
        
        .carousel-nav:hover {
            background: rgba(0, 0, 0, 0.7);
            color: #FAC638;
        }
        
        .carousel-nav-left {
            left: 16px;
        }
        
        .carousel-nav-right {
            right: 16px;
        }
        
        .image-counter {
            position: absolute;
            top: 16px;
            right: 16px;
            background: rgba(0, 0, 0, 0.6);
            color: white;
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: 500;
        }
        
        .color-option {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            border: 2px solid transparent;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .color-option.active {
            border-color: #FAC638;
            transform: scale(1.1);
        }
        
        .rating-stars {
            color: #FAC638;
        }
        
        .fixed-bottom-bar {
            position: fixed;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 100%;
            max-width: 400px;
            background: rgba(26, 26, 26, 0.95);
            backdrop-filter: blur(10px);
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            z-index: 50;
        }
        
        .quantity-btn {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: rgba(45, 45, 45, 0.8);
            border: 1px solid rgba(255, 255, 255, 0.2);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
        }
        
        .quantity-btn:hover {
            background: rgba(60, 60, 60, 0.9);
            border-color: #FAC638;
        }
        
        .add-to-cart-btn {
            background: linear-gradient(135deg, #FAC638, #f59e0b);
            transition: all 0.3s ease;
        }
        
        .add-to-cart-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(250, 198, 56, 0.4);
        }
        
        .related-product {
            background: rgba(45, 45, 45, 0.6);
            border: 1px solid rgba(255, 255, 255, 0.1);
            transition: all 0.3s ease;
        }
        
        .related-product:hover {
            background: rgba(60, 60, 60, 0.8);
            transform: translateY(-2px);
        }
    </style>
</head>

<body class="bg-background-dark">
    <div class="product-container">
        <!-- Header -->
        <div class="flex items-center justify-between p-4 border-b border-gray-700">
            <button onclick="goBack()" class="text-white hover:text-primary transition-colors">
                <span class="material-symbols-outlined text-2xl">arrow_back</span>
            </button>
            <h1 class="text-white font-semibold text-lg">Chi tiết sản phẩm</h1>
            <div class="flex items-center gap-2">
                <button class="text-white hover:text-primary transition-colors">
                    <span class="material-symbols-outlined text-2xl">share</span>
                </button>
            </div>
        </div>

        <!-- Image Carousel -->
        <div class="image-carousel">
            <img id="mainProductImage" 
                 src="/PRODUCT-IMG/{{ $productImages[0] ?? $product->image ?? 'default.jpg' }}" 
                 alt="{{ $product->name }}" 
                 class="carousel-image"
                 onerror="this.src='https://via.placeholder.com/400x300/FAC638/FFFFFF?text={{ urlencode($product->name) }}'">
            
            @if($hasMultipleImages)
            <!-- Navigation Arrows -->
            <button class="carousel-nav carousel-nav-left" onclick="previousImage()">
                <span class="material-symbols-outlined">chevron_left</span>
            </button>
            <button class="carousel-nav carousel-nav-right" onclick="nextImage()">
                <span class="material-symbols-outlined">chevron_right</span>
            </button>
            
            <!-- Image Counter -->
            <div class="image-counter">
                <span id="currentImageNumber">1</span> / {{ count($productImages) }}
            </div>
            
            <div class="carousel-dots" id="carouselDots">
                @foreach($productImages as $index => $image)
                    <div class="carousel-dot {{ $index === 0 ? 'active' : '' }}" 
                         data-index="{{ $index }}" 
                         data-image="{{ $image }}"
                         onclick="changeImage({{ $index }}, '{{ $image }}')"></div>
                @endforeach
            </div>
            @endif
        </div>

        <!-- Product Info -->
        <div class="p-6">
            <!-- Title and Rating -->
            <div class="mb-4">
                <h2 class="text-white text-xl font-bold mb-2">{{ $product->name }}</h2>
                <div class="flex items-center gap-2 mb-3">
                    <div class="rating-stars flex">
                        <span class="material-symbols-outlined text-sm">star</span>
                        <span class="material-symbols-outlined text-sm">star</span>
                        <span class="material-symbols-outlined text-sm">star</span>
                        <span class="material-symbols-outlined text-sm">star</span>
                        <span class="material-symbols-outlined text-sm">star_half</span>
                    </div>
                    <span class="text-primary font-semibold">4.8</span>
                </div>
                <p class="text-primary text-2xl font-bold">{{ number_format($product->price ?? 0) }}đ</p>
                <p class="text-green-400 text-sm mt-1">Còn hàng</p>
            </div>

            <!-- Variants - Chỉ hiển thị khi có nhiều biến thể -->
            @if($hasVariants)
            <div class="mb-6">
                <p class="text-white font-medium mb-3">Lựa chọn</p>
                <div class="grid grid-cols-2 gap-2">
                    @foreach($availableVariants as $index => $variant)
                        <button class="variant-option py-3 px-4 border {{ $index === 0 ? 'border-primary bg-primary/10 text-primary' : 'border-gray-600 text-white hover:border-primary' }} rounded-lg transition-colors text-sm"
                                data-variant="{{ $variant }}">
                            {{ $variant }}
                        </button>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- Description -->
            <div class="mb-6">
                <h3 class="text-white font-semibold mb-3">Mô tả sản phẩm</h3>
                <p class="text-gray-300 text-sm leading-relaxed">
                    {{ $product->description ?? 'Sản phẩm len thủ công cao cấp, được làm từ chất liệu len tự nhiên 100%. Thiết kế tinh tế, phù hợp cho mọi dịp. Sản phẩm được đan móc thủ công bởi những nghệ nhân có kinh nghiệm, đảm bảo chất lượng và độ bền cao.' }}
                </p>
                <button class="text-primary text-sm mt-2">Xem thêm</button>
            </div>

            <!-- Reviews -->
            <div class="mb-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-white font-semibold">Đánh giá & Nhận xét</h3>
                    <button class="text-primary text-sm">Xem tất cả</button>
                </div>
                
                <div class="bg-surface-dark/60 rounded-xl p-4 border border-gray-700">
                    <div class="flex items-center gap-2 mb-2">
                        <span class="text-primary text-3xl font-bold">4.8</span>
                        <div>
                            <div class="rating-stars flex mb-1">
                                <span class="material-symbols-outlined text-sm">star</span>
                                <span class="material-symbols-outlined text-sm">star</span>
                                <span class="material-symbols-outlined text-sm">star</span>
                                <span class="material-symbols-outlined text-sm">star</span>
                                <span class="material-symbols-outlined text-sm">star_half</span>
                            </div>
                            <p class="text-gray-400 text-xs">124 đánh giá</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Related Products -->
            <div class="mb-20">
                <h3 class="text-white font-semibold mb-4">Có thể bạn cũng thích</h3>
                <div class="grid grid-cols-2 gap-3" id="relatedProducts">
                    <!-- Related products will be loaded here -->
                </div>
            </div>
        </div>
    </div>

    <!-- Fixed Bottom Bar -->
    <div class="fixed-bottom-bar">
        <div class="flex items-center gap-4 p-4">
            <!-- Quantity Selector -->
            <div class="flex items-center gap-3">
                <button class="quantity-btn" onclick="decreaseQuantity()">
                    <span class="material-symbols-outlined text-sm">remove</span>
                </button>
                <span class="text-white font-semibold min-w-[20px] text-center" id="quantity">1</span>
                <button class="quantity-btn" onclick="increaseQuantity()">
                    <span class="material-symbols-outlined text-sm">add</span>
                </button>
            </div>
            
            <!-- Add to Cart Button -->
            <button class="add-to-cart-btn flex-1 py-3 rounded-2xl text-background-dark font-bold text-lg" onclick="addToCart()">
                Thêm vào giỏ
            </button>
        </div>
    </div>

    <script>
        // Handle back navigation with return URL support
        function goBack() {
            const urlParams = new URLSearchParams(window.location.search);
            const returnUrl = urlParams.get('return');
            
            if (returnUrl) {
                // Decode the return URL and navigate back
                window.location.href = decodeURIComponent(returnUrl);
            } else {
                // Default back to products page
                window.location.href = '/san-pham';
            }
        }
        
        let currentQuantity = 1;
        let selectedVariant = null;
        let productVariants = [];
        let productImages = @json($productImages ?? []);
        let currentImageIndex = 0;
        let touchStartX = 0;
        let touchEndX = 0;

        $(document).ready(function() {
            loadRelatedProducts();
            loadProductVariants();
            
            // Khởi tạo giá trị mặc định
            selectedVariant = $('.variant-option.border-primary').data('variant') || null;
            
            // Variant selection
            $(document).on('click', '.variant-option', function() {
                $('.variant-option').removeClass('border-primary bg-primary/10 text-primary')
                                   .addClass('border-gray-600 text-white hover:border-primary');
                $(this).removeClass('border-gray-600 text-white hover:border-primary')
                       .addClass('border-primary bg-primary/10 text-primary');
                selectedVariant = $(this).data('variant');
                updateVariantInfo();
            });

            // Touch events for image carousel
            $('.image-carousel').on('touchstart', function(e) {
                touchStartX = e.originalEvent.touches[0].clientX;
            });

            $('.image-carousel').on('touchend', function(e) {
                touchEndX = e.originalEvent.changedTouches[0].clientX;
                handleSwipe();
            });
        });

        function increaseQuantity() {
            currentQuantity++;
            $('#quantity').text(currentQuantity);
        }

        function decreaseQuantity() {
            if (currentQuantity > 1) {
                currentQuantity--;
                $('#quantity').text(currentQuantity);
            }
        }

        // Xử lý swipe gesture
        function handleSwipe() {
            const swipeThreshold = 50;
            const diff = touchStartX - touchEndX;
            
            if (Math.abs(diff) > swipeThreshold && productImages.length > 1) {
                if (diff > 0) {
                    // Swipe left - next image
                    nextImage();
                } else {
                    // Swipe right - previous image
                    previousImage();
                }
            }
        }

        // Chuyển đến hình ảnh tiếp theo
        function nextImage() {
            if (productImages.length > 1) {
                currentImageIndex = (currentImageIndex + 1) % productImages.length;
                changeImage(currentImageIndex, productImages[currentImageIndex]);
            }
        }

        // Chuyển đến hình ảnh trước đó
        function previousImage() {
            if (productImages.length > 1) {
                currentImageIndex = currentImageIndex === 0 ? productImages.length - 1 : currentImageIndex - 1;
                changeImage(currentImageIndex, productImages[currentImageIndex]);
            }
        }

        // Thay đổi hình ảnh chính với hiệu ứng
        function changeImage(index, imageSrc) {
            currentImageIndex = index;
            const imageUrl = imageSrc ? `/PRODUCT-IMG/${imageSrc}` : `https://via.placeholder.com/400x300/FAC638/FFFFFF?text={{ urlencode($product->name) }}`;
            
            const $img = $('#mainProductImage');
            
            // Thêm hiệu ứng chuyển đổi
            $img.addClass('changing');
            
            setTimeout(() => {
                $img.attr('src', imageUrl);
                $img.removeClass('changing');
            }, 150);
            
            // Cập nhật dots
            $('.carousel-dot').removeClass('active');
            $(`.carousel-dot[data-index="${index}"]`).addClass('active');
            
            // Cập nhật counter
            $('#currentImageNumber').text(index + 1);
        }

        function loadProductVariants() {
            $.get('/api/products/{{ $product->id }}/variants', function(response) {
                if (response.success) {
                    productVariants = response.variants;
                    console.log('Product variants loaded:', response);
                }
            }).fail(function() {
                console.log('Failed to load product variants');
            });
        }

        function updateVariantInfo() {
            console.log('Selected variant:', selectedVariant);
            
            // Tìm variant được chọn và thay đổi hình ảnh nếu có
            if (selectedVariant && productVariants.length > 0) {
                const variant = productVariants.find(v => v.variant_name === selectedVariant);
                
                if (variant && variant.image) {
                    // Thay đổi hình ảnh chính thành hình ảnh của variant
                    const variantImageUrl = `/PRODUCT-IMG/${variant.image}`;
                    $('#mainProductImage').addClass('changing');
                    
                    setTimeout(() => {
                        $('#mainProductImage').attr('src', variantImageUrl)
                            .attr('onerror', `this.src='https://via.placeholder.com/400x300/FAC638/FFFFFF?text={{ urlencode($product->name) }}'`)
                            .removeClass('changing');
                    }, 150);
                    
                    // Ẩn carousel controls khi hiển thị hình ảnh variant
                    $('#carouselDots, .carousel-nav, .image-counter').hide();
                } else {
                    // Quay lại hình ảnh gốc nếu variant không có hình ảnh riêng
                    if (productImages.length > 0) {
                        changeImage(0, productImages[0]);
                        if (productImages.length > 1) {
                            $('#carouselDots, .carousel-nav, .image-counter').show();
                        }
                    }
                }
                
                // Cập nhật giá nếu variant có giá khác
                if (variant && variant.price) {
                    const formattedPrice = parseFloat(variant.price).toLocaleString('vi-VN');
                    $('.text-primary.text-2xl.font-bold').text(formattedPrice + 'đ');
                }
            } else {
                // Quay lại hình ảnh và giá gốc
                if (productImages.length > 0) {
                    changeImage(0, productImages[0]);
                    if (productImages.length > 1) {
                        $('#carouselDots, .carousel-nav, .image-counter').show();
                    }
                }
                
                const originalPrice = parseFloat({{ $product->price ?? 0 }}).toLocaleString('vi-VN');
                $('.text-primary.text-2xl.font-bold').text(originalPrice + 'đ');
            }
        }

        function addToCart() {
            @auth
                // Kiểm tra xem có cần chọn variant không
                @if($hasVariants && !$availableVariants->isEmpty())
                    if (!selectedVariant) {
                        alert('Vui lòng chọn biến thể sản phẩm!');
                        return;
                    }
                @endif
                
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                
                const cartData = {
                    product_id: {{ $product->id }},
                    quantity: currentQuantity
                };
                
                // Thêm thông tin variant nếu có
                if (selectedVariant) {
                    // Tìm variant_id từ selectedVariant name
                    const variant = productVariants.find(v => v.variant_name === selectedVariant);
                    if (variant) {
                        cartData.variant_id = variant.id;
                    } else {
                        // Fallback: sử dụng variant đầu tiên nếu không tìm thấy
                        if (productVariants.length > 0) {
                            cartData.variant_id = productVariants[0].id;
                        }
                    }
                } else {
                    // Nếu không có variant được chọn, sử dụng variant đầu tiên
                    if (productVariants.length > 0) {
                        cartData.variant_id = productVariants[0].id;
                    }
                }
                
                // Đảm bảo có variant_id
                if (!cartData.variant_id) {
                    alert('Không thể xác định biến thể sản phẩm. Vui lòng thử lại!');
                    return;
                }
                
                $.post('/api/cart/add', cartData, function(response) {
                    if (response.success) {
                        // Show success animation
                        const btn = $('.add-to-cart-btn');
                        const originalText = btn.text();
                        
                        btn.text('✓ Đã thêm!').addClass('bg-green-500');
                        
                        setTimeout(() => {
                            btn.text(originalText).removeClass('bg-green-500');
                        }, 2000);
                    } else {
                        console.error('Cart add error:', response);
                        alert(response.message || 'Có lỗi xảy ra, vui lòng thử lại!');
                    }
                }).fail(function(xhr, status, error) {
                    console.error('Cart add failed:', xhr.responseText, status, error);
                    let errorMessage = 'Có lỗi xảy ra, vui lòng thử lại!';
                    
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMessage = xhr.responseJSON.message;
                    }
                    
                    alert(errorMessage);
                });
            @else
                // Show login popup or redirect
                if (confirm('Vui lòng đăng nhập để thêm sản phẩm vào giỏ hàng. Chuyển đến trang đăng nhập?')) {
                    window.location.href = '{{ route("login") }}';
                }
            @endauth
        }

        function loadRelatedProducts() {
            $.get('/api/products', function(response) {
                let html = '';
                const products = response.products.slice(0, 4);
                
                products.forEach(item => {
                    const imageUrl = item.image && item.image !== 'default.jpg' 
                        ? `/PRODUCT-IMG/${item.image}` 
                        : `https://via.placeholder.com/150x120/FAC638/FFFFFF?text=${encodeURIComponent(item.name.substring(0, 2))}`;
                    
                    const price = parseFloat(item.price) || 0;
                    const formattedPrice = price.toLocaleString('vi-VN');
                    
                    html += `
                        <div class="related-product rounded-xl p-3 cursor-pointer" onclick="window.location.href='/san-pham/${item.id}'">
                            <img src="${imageUrl}" alt="${item.name}" class="w-full h-24 object-cover rounded-lg mb-2"
                                 onerror="this.src='https://via.placeholder.com/150x120/FAC638/FFFFFF?text=${encodeURIComponent(item.name.substring(0, 2))}'">
                            <h4 class="text-white text-sm font-medium mb-1 line-clamp-2">${item.name}</h4>
                            <p class="text-primary text-sm font-bold">${formattedPrice}đ</p>
                        </div>
                    `;
                });
                
                $('#relatedProducts').html(html);
            }).fail(function() {
                $('#relatedProducts').html('<p class="text-gray-400 text-sm col-span-2 text-center">Không thể tải sản phẩm liên quan</p>');
            });
        }
    </script>
</body>
</html>