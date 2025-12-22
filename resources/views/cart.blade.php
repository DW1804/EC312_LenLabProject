<!DOCTYPE html>
<html class="dark" lang="vi">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Giỏ hàng - LENLAB</title>
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
        
        .cart-container {
            background: #1a1a1a;
            max-width: 400px;
            margin: 0 auto;
            min-height: 100vh;
        }
        
        .cart-item {
            background: rgba(45, 45, 45, 0.6);
            border: 1px solid rgba(255, 255, 255, 0.1);
            transition: all 0.3s ease;
        }
        
        .cart-item:hover {
            background: rgba(60, 60, 60, 0.8);
        }
        
        .quantity-btn {
            width: 32px;
            height: 32px;
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
        
        .quantity-btn.add {
            background: #FAC638;
            color: #1a1a1a;
        }
        
        .quantity-btn.add:hover {
            background: #f59e0b;
        }
        
        .voucher-section {
            background: rgba(45, 45, 45, 0.6);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        .voucher-input {
            background: rgba(26, 26, 26, 0.8);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        .voucher-input:focus-within {
            border-color: #FAC638;
        }
        
        .checkout-btn {
            background: linear-gradient(135deg, #FAC638, #f59e0b);
            transition: all 0.3s ease;
        }
        
        .checkout-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(250, 198, 56, 0.4);
        }
        
        .empty-cart {
            text-align: center;
            padding: 60px 20px;
        }
        
        .loading {
            text-align: center;
            padding: 40px 20px;
        }
    </style>
</head>

<body class="bg-background-dark">
    <div class="cart-container">
        <!-- Header -->
        <div class="flex items-center justify-between p-4 border-b border-gray-700">
            <button onclick="window.history.back()" class="text-white hover:text-primary transition-colors">
                <span class="material-symbols-outlined text-2xl">arrow_back</span>
            </button>
            <h1 class="text-white font-semibold text-lg">
                Giỏ hàng <span class="text-primary" id="cartCount">(0)</span>
            </h1>
            <button class="text-white hover:text-primary transition-colors">
                <span class="material-symbols-outlined text-2xl">more_vert</span>
            </button>
        </div>

        <!-- Cart Items -->
        <div class="p-4" id="cartItemsContainer">
            <!-- Loading -->
            <div class="loading" id="loadingCart">
                <div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-primary mb-4"></div>
                <p class="text-gray-400">Đang tải giỏ hàng...</p>
            </div>
            
            <!-- Cart Items will be loaded here -->
            <div id="cartItems" class="space-y-4"></div>
        </div>

        <!-- Voucher Section -->
        <div class="px-4 mb-6" id="voucherSection" style="display: none;">
            <div class="voucher-section rounded-xl p-4">
                <div class="flex items-center gap-3 mb-3">
                    <div class="w-8 h-8 bg-primary/20 rounded-full flex items-center justify-center">
                        <span class="material-symbols-outlined text-primary text-sm">local_offer</span>
                    </div>
                    <span class="text-white font-medium">Nhập mã giảm giá</span>
                    <span class="text-primary text-sm font-semibold ml-auto" id="voucherStatus">ÁP DỤNG</span>
                </div>
                
                <div class="voucher-input rounded-lg p-3 flex items-center gap-3">
                    <input type="text" 
                           id="voucherCode" 
                           placeholder="Nhập mã voucher" 
                           class="flex-1 bg-transparent text-white placeholder-gray-500 border-none outline-none">
                    <button onclick="applyVoucher()" class="text-primary font-semibold">
                        Áp dụng
                    </button>
                </div>
                
                <p id="voucherMessage" class="text-sm mt-2 hidden"></p>
            </div>
        </div>

        <!-- Summary Section -->
        <div class="px-4 mb-20" id="summarySection" style="display: none;">
            <div class="space-y-3 mb-6">
                <div class="flex justify-between items-center">
                    <span class="text-gray-400">Tạm tính</span>
                    <span class="text-white font-semibold" id="subtotal">0đ</span>
                </div>
                
                <div class="flex justify-between items-center">
                    <span class="text-gray-400">Phí vận chuyển</span>
                    <span class="text-green-400 font-semibold">Miễn phí</span>
                </div>
                
                <div id="discountRow" class="flex justify-between items-center hidden">
                    <span class="text-gray-400">Giảm giá</span>
                    <span class="text-green-400 font-semibold" id="discountAmount">-0đ</span>
                </div>
                
                <hr class="border-gray-700">
                
                <div class="flex justify-between items-center">
                    <span class="text-white text-lg font-bold">Tổng cộng</span>
                    <span class="text-primary text-xl font-bold" id="totalAmount">0đ</span>
                </div>
            </div>
        </div>

        <!-- Empty Cart -->
        <div class="empty-cart hidden" id="emptyCart">
            <div class="w-24 h-24 bg-gray-700 rounded-full flex items-center justify-center mx-auto mb-4">
                <span class="material-symbols-outlined text-gray-500 text-4xl">shopping_cart</span>
            </div>
            <h3 class="text-white text-xl font-semibold mb-2">Giỏ hàng trống</h3>
            <p class="text-gray-400 mb-6">Hãy thêm sản phẩm vào giỏ hàng để tiếp tục mua sắm</p>
            <button onclick="window.location.href='/san-pham'" class="checkout-btn px-8 py-3 rounded-2xl text-background-dark font-bold">
                Mua sắm ngay
            </button>
        </div>
    </div>

    <!-- Fixed Checkout Button -->
    <div class="fixed bottom-0 left-1/2 transform -translate-x-1/2 w-full max-width-400 bg-background-dark/95 backdrop-blur-md border-t border-gray-700 p-4" id="checkoutBar" style="display: none; max-width: 400px;">
        <button onclick="checkout()" class="checkout-btn w-full py-4 rounded-2xl text-background-dark font-bold text-lg flex items-center justify-center gap-2">
            Thanh toán
            <span class="material-symbols-outlined">arrow_forward</span>
        </button>
    </div>

    <script>
        let cart = [];
        let discountAmount = 0;
        let discountPercent = 0;

        $(document).ready(function() {
            loadCart();
            
            // Setup CSRF token
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
        });

        function loadCart() {
            $('#loadingCart').show();
            
            @auth
                $.get('/api/cart', function(response) {
                    cart = response.cart || [];
                    renderCart();
                    updateSummary();
                    $('#loadingCart').hide();
                }).fail(function() {
                    $('#loadingCart').hide();
                    showEmptyCart();
                });
            @else
                // For guest users, show empty cart or redirect to login
                $('#loadingCart').hide();
                showEmptyCart();
            @endauth
        }

        function renderCart() {
            const container = $('#cartItems');
            
            if (cart.length === 0) {
                showEmptyCart();
                return;
            }
            
            $('#cartCount').text(`(${cart.length})`);
            $('#voucherSection, #summarySection, #checkoutBar').show();
            $('#emptyCart').hide();
            
            let html = '';
            
            cart.forEach(item => {
                // Handle both normal products and digital products
                let product, imageUrl, productName, category, price;
                
                if (item.product_type === 'digital' && item.digital_product) {
                    product = item.digital_product;
                    productName = product.name;
                    category = getDigitalProductType(product.type);
                    price = parseFloat(product.price) || 0;
                    imageUrl = product.thumbnail || `https://via.placeholder.com/80x80/FAC638/FFFFFF?text=📄`;
                } else {
                    product = item.product || {};
                    productName = product.name || 'Sản phẩm';
                    category = product.category || '';
                    price = parseFloat(product.price) || 0;
                    imageUrl = product.image && product.image !== 'default.jpg' 
                        ? `/PRODUCT-IMG/${product.image}` 
                        : `https://via.placeholder.com/80x80/FAC638/FFFFFF?text=${encodeURIComponent(productName.substring(0, 2))}`;
                }
                
                const formattedPrice = price.toLocaleString('vi-VN');
                
                html += `
                    <div class="cart-item rounded-xl p-4">
                        <div class="flex items-center gap-4">
                            <!-- Product Image -->
                            <img src="${imageUrl}" 
                                 alt="${productName}" 
                                 class="w-20 h-20 object-cover rounded-xl"
                                 onerror="this.src='https://via.placeholder.com/80x80/FAC638/FFFFFF?text=${encodeURIComponent(productName.substring(0, 2))}'">
                            
                            <!-- Product Info -->
                            <div class="flex-1">
                                <h3 class="text-white font-semibold mb-1">${productName}</h3>
                                ${getVariantInfo(item, category)}
                                <p class="text-primary font-bold">${formattedPrice}đ</p>
                                ${item.product_type === 'digital' ? '<span class="inline-block px-2 py-1 bg-blue-500/20 text-blue-300 text-xs rounded-full mt-1">Sản phẩm số</span>' : ''}
                            </div>
                            
                            <!-- Delete Button -->
                            <button onclick="removeFromCart(${item.id})" class="text-gray-400 hover:text-red-400 transition-colors">
                                <span class="material-symbols-outlined">delete</span>
                            </button>
                        </div>
                        
                        <!-- Quantity Controls -->
                        <div class="flex items-center justify-end gap-3 mt-4">
                            ${item.product_type === 'digital' ? 
                                `<span class="text-gray-400 text-sm">Số lượng: ${item.quantity}</span>` :
                                `<button onclick="updateQuantity(${item.id}, 'decrease')" class="quantity-btn">
                                    <span class="material-symbols-outlined text-sm">remove</span>
                                </button>
                                <span class="text-white font-semibold min-w-[20px] text-center">${item.quantity}</span>
                                <button onclick="updateQuantity(${item.id}, 'increase')" class="quantity-btn add">
                                    <span class="material-symbols-outlined text-sm">add</span>
                                </button>`
                            }
                        </div>
                    </div>
                `;
            });
            
            container.html(html);
        }

        function getDigitalProductType(type) {
            switch(type) {
                case 'course': return 'Khóa học';
                case 'file': return 'Tài liệu';
                case 'link': return 'Link';
                default: return 'Sản phẩm số';
            }
        }

        function getVariantInfo(item, category = '') {
            let variantHtml = '';
            
            if (item.product_type === 'digital') {
                variantHtml = `<p class="text-gray-400 text-sm mb-2">${category}</p>`;
            } else if (item.variant_info) {
                let variantInfo;
                try {
                    variantInfo = typeof item.variant_info === 'string' 
                        ? JSON.parse(item.variant_info) 
                        : item.variant_info;
                } catch (e) {
                    variantInfo = {};
                }
                
                if (variantInfo.variant_name) {
                    variantHtml = `<p class="text-gray-400 text-sm mb-2">Loại: ${variantInfo.variant_name}</p>`;
                } else {
                    variantHtml = `<p class="text-gray-400 text-sm mb-2">${category}</p>`;
                }
            } else {
                variantHtml = `<p class="text-gray-400 text-sm mb-2">${category}</p>`;
            }
            
            return variantHtml;
        }

        function showEmptyCart() {
            $('#cartCount').text('(0)');
            $('#cartItems, #voucherSection, #summarySection, #checkoutBar').hide();
            $('#emptyCart').removeClass('hidden');
        }

        function updateQuantity(itemId, action) {
            $.post('/api/cart/update', {
                id: itemId,
                action: action
            }, function(response) {
                if (response.success) {
                    loadCart();
                } else {
                    alert('Có lỗi xảy ra, vui lòng thử lại!');
                }
            }).fail(function() {
                alert('Có lỗi xảy ra, vui lòng thử lại!');
            });
        }

        function removeFromCart(itemId) {
            if (confirm('Bạn có chắc muốn xóa sản phẩm này khỏi giỏ hàng?')) {
                $.post('/api/cart/delete', {
                    id: itemId
                }, function(response) {
                    if (response.success) {
                        loadCart();
                    } else {
                        alert('Có lỗi xảy ra, vui lòng thử lại!');
                    }
                }).fail(function() {
                    alert('Có lỗi xảy ra, vui lòng thử lại!');
                });
            }
        }

        function updateSummary() {
            let subtotal = 0;
            
            cart.forEach(item => {
                let price = 0;
                
                if (item.product_type === 'digital' && item.digital_product) {
                    price = parseFloat(item.digital_product.price) || 0;
                } else if (item.product) {
                    price = parseFloat(item.product.price) || 0;
                }
                
                subtotal += price * item.quantity;
            });
            
            const discount = discountPercent > 0 ? (subtotal * discountPercent / 100) : discountAmount;
            const total = subtotal - discount;
            
            $('#subtotal').text(subtotal.toLocaleString('vi-VN') + 'đ');
            $('#totalAmount').text(total.toLocaleString('vi-VN') + 'đ');
            
            if (discount > 0) {
                $('#discountRow').removeClass('hidden');
                $('#discountAmount').text('-' + discount.toLocaleString('vi-VN') + 'đ');
            } else {
                $('#discountRow').addClass('hidden');
            }
        }

        function applyVoucher() {
            const code = $('#voucherCode').val().trim();
            
            if (!code) {
                showVoucherMessage('Vui lòng nhập mã voucher', 'error');
                return;
            }
            
            $.post('/api/cart/voucher', {
                code: code
            }, function(response) {
                if (response.success) {
                    discountAmount = response.discount || 0;
                    discountPercent = response.discount_percent || 0;
                    showVoucherMessage('Áp dụng mã giảm giá thành công!', 'success');
                    $('#voucherStatus').text('ĐÃ ÁP DỤNG').addClass('text-green-400').removeClass('text-primary');
                    updateSummary();
                } else {
                    discountAmount = 0;
                    discountPercent = 0;
                    showVoucherMessage(response.message || 'Mã voucher không hợp lệ', 'error');
                    updateSummary();
                }
            }).fail(function() {
                showVoucherMessage('Có lỗi xảy ra, vui lòng thử lại!', 'error');
            });
        }

        function showVoucherMessage(message, type) {
            const messageEl = $('#voucherMessage');
            messageEl.text(message)
                    .removeClass('hidden text-green-400 text-red-400')
                    .addClass(type === 'success' ? 'text-green-400' : 'text-red-400');
            
            setTimeout(() => {
                messageEl.addClass('hidden');
            }, 3000);
        }

        function checkout() {
            if (cart.length === 0) {
                alert('Giỏ hàng trống!');
                return;
            }
            
            @auth
                // Redirect to checkout page
                window.location.href = '/checkout';
            @else
                if (confirm('Vui lòng đăng nhập để tiếp tục thanh toán. Chuyển đến trang đăng nhập?')) {
                    window.location.href = '{{ route("login") }}';
                }
            @endauth
        }
    </script>
</body>
</html>