<!DOCTYPE html>
<html class="dark" lang="vi">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Voucher của tôi - LENLAB</title>
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
                        "background-dark": "#0f0f0f",
                        "surface-dark": "#1a1a1a",
                        "card-dark": "#2a2a2a"
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
            background: #0f0f0f;
            min-height: 100vh;
        }
        
        .voucher-container {
            background: #0f0f0f;
            max-width: 400px;
            margin: 0 auto;
            min-height: 100vh;
        }
        
        .voucher-card {
            background: linear-gradient(135deg, rgba(45, 45, 45, 0.8), rgba(35, 35, 35, 0.9));
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 16px;
            padding: 20px;
            margin-bottom: 16px;
            position: relative;
            overflow: hidden;
        }
        
        .voucher-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, #FAC638, #f59e0b);
        }
        
        .voucher-icon {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
        }
        
        .shipping-icon {
            background: linear-gradient(135deg, #f97316, #ea580c);
            color: white;
        }
        
        .discount-icon {
            background: linear-gradient(135deg, #eab308, #ca8a04);
            color: white;
        }
        
        .money-icon {
            background: linear-gradient(135deg, #10b981, #059669);
            color: white;
        }
        
        .gift-icon {
            background: linear-gradient(135deg, #3b82f6, #2563eb);
            color: white;
        }
        
        .use-btn {
            background: #FAC638;
            color: #0f0f0f;
            border-radius: 20px;
            padding: 8px 20px;
            font-weight: 600;
            font-size: 14px;
            transition: all 0.3s ease;
            border: none;
            cursor: pointer;
            min-width: 80px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .use-btn:hover {
            background: #e6b332;
            transform: translateY(-1px);
        }
        
        .use-btn:disabled {
            background: #6b7280;
            color: #9ca3af;
            cursor: not-allowed;
            transform: none;
        }
        
        .expired-btn {
            background: #6b7280;
            color: #9ca3af;
            cursor: not-allowed;
        }
        
        .expired-btn:hover {
            background: #6b7280;
            transform: none;
        }
        
        .voucher-card.invalid {
            opacity: 0.7;
            border-color: rgba(239, 68, 68, 0.3);
        }
        
        .voucher-card.invalid .use-btn {
            background: #6b7280;
            color: #9ca3af;
            cursor: not-allowed;
        }
        
        .voucher-card.valid {
            border-color: rgba(34, 197, 94, 0.3);
        }
        
        .voucher-status {
            margin-top: 8px;
            padding-top: 8px;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
        }
        }
        
        .voucher-code {
            background: rgba(250, 198, 56, 0.1);
            border: 1px solid rgba(250, 198, 56, 0.3);
            border-radius: 8px;
            padding: 8px 12px;
            font-family: 'Courier New', monospace;
            font-size: 12px;
            color: #FAC638;
            display: inline-block;
            margin-top: 8px;
        }
        
        .hot-badge {
            background: linear-gradient(135deg, #ef4444, #dc2626);
            color: white;
            font-size: 10px;
            font-weight: bold;
            padding: 2px 8px;
            border-radius: 12px;
            position: absolute;
            top: 12px;
            right: 12px;
        }
        
        .search-container {
            background: #374151;
            border: 1px solid #4b5563;
        }
        
        .search-container:focus-within {
            border-color: #FAC638;
        }
    </style>
</head>

<body class="bg-background-dark">
    <div class="voucher-container">
        <!-- Header -->
        <div class="flex items-center justify-between p-4 border-b border-gray-700">
            <button onclick="goBack()" class="text-white hover:text-primary transition-colors">
                <span class="material-symbols-outlined text-2xl">arrow_back</span>
            </button>
            <h1 class="text-white font-semibold text-lg">Voucher của tôi</h1>
            <div class="w-8"></div>
        </div>

        <!-- Search Bar -->
        <div class="p-4">
            <div class="search-container bg-gray-700 border border-gray-600 rounded-xl p-3 flex items-center gap-3">
                <div class="w-6 h-6 flex items-center justify-center">
                    <span class="material-symbols-outlined text-gray-400 text-xl">local_offer</span>
                </div>
                <input type="text" 
                       class="flex-1 bg-transparent text-white placeholder-gray-400 border-none outline-none" 
                       placeholder="Nhập mã voucher" 
                       id="voucherSearch">
                <button class="bg-gray-600 hover:bg-gray-500 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                    Áp dụng
                </button>
            </div>
        </div>

        <!-- Voucher List -->
        <div class="px-4 pb-8" id="vouchersContainer">
            <!-- Cart Info Display (when coming from cart) -->
            <div id="cartInfoDisplay" class="hidden mb-4 p-4 bg-gray-800 rounded-xl border border-gray-600">
                <div class="flex items-center gap-3 mb-2">
                    <span class="material-symbols-outlined text-primary">shopping_cart</span>
                    <span class="text-white font-medium">Thông tin giỏ hàng</span>
                </div>
                <p class="text-gray-300 text-sm">Giá trị giỏ hàng: <span id="cartTotalDisplay" class="text-primary font-semibold">0đ</span></p>
            </div>

            @if($vouchers->count() > 0)
                @foreach($vouchers as $voucher)
                    <div class="voucher-card" data-voucher-code="{{ $voucher->code }}" data-min-order="{{ $voucher->min_order_value ?? 0 }}">
                        <div class="flex items-start justify-between">
                            <div class="flex items-start gap-4 flex-1">
                                <div class="voucher-icon {{ App\Http\Controllers\VoucherController::getIconColor($voucher->type) }}">
                                    <span class="material-symbols-outlined">{{ App\Http\Controllers\VoucherController::getIconType($voucher->type) }}</span>
                                </div>
                                <div class="flex-1">
                                    <h3 class="text-white font-semibold text-lg mb-1">{{ $voucher->code }}</h3>
                                    <p class="text-gray-300 text-sm mb-2">
                                        @if($voucher->type === 'percent')
                                            Giảm {{ $voucher->discount_value }}%
                                        @elseif($voucher->type === 'fixed')
                                            Giảm {{ number_format($voucher->discount_value) }}đ
                                        @elseif($voucher->type === 'free_shipping')
                                            Miễn phí vận chuyển
                                        @else
                                            {{ $voucher->type }}
                                        @endif
                                    </p>
                                    @if($voucher->min_order_value)
                                        <p class="text-gray-400 text-xs mb-2">Đơn hàng tối thiểu {{ number_format($voucher->min_order_value) }}đ</p>
                                    @endif
                                    <p class="text-gray-400 text-xs">Ngày hết hạn: {{ $voucher->end_date->format('d/m/Y') }}</p>
                                    
                                    <!-- Voucher Status Display -->
                                    <div class="voucher-status mt-2 hidden">
                                        <div class="status-valid hidden">
                                            <div class="flex items-center gap-2">
                                                <span class="material-symbols-outlined text-green-400 text-sm">check_circle</span>
                                                <span class="text-green-400 text-xs font-medium">Có thể sử dụng</span>
                                            </div>
                                        </div>
                                        <div class="status-invalid hidden">
                                            <div class="flex items-center gap-2">
                                                <span class="material-symbols-outlined text-red-400 text-sm">cancel</span>
                                                <span class="text-red-400 text-xs font-medium">Không đủ điều kiện</span>
                                            </div>
                                            <p class="text-red-300 text-xs mt-1 status-message"></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <button class="use-btn" onclick="useVoucher('{{ $voucher->code }}')">
                                <span class="btn-text">Dùng ngay</span>
                                <span class="btn-loading hidden">
                                    <span class="inline-block animate-spin rounded-full h-4 w-4 border-b-2 border-current"></span>
                                </span>
                            </button>
                        </div>
                    </div>
                @endforeach
            @else
                <div class="text-center py-12">
                    <div class="w-16 h-16 bg-gray-600 rounded-full flex items-center justify-center mx-auto mb-4">
                        <span class="material-symbols-outlined text-gray-400 text-2xl">local_offer</span>
                    </div>
                    <h3 class="text-white text-lg font-semibold mb-2">Chưa có voucher</h3>
                    <p class="text-gray-400 text-sm">Hiện tại chưa có voucher nào khả dụng</p>
                </div>
            @endif
        </div>
    </div>

    <script>
        function goBack() {
            // Check if we came from cart page
            const urlParams = new URLSearchParams(window.location.search);
            const returnPage = urlParams.get('return');
            
            if (returnPage === 'cart') {
                window.location.href = '/cart';
            } else {
                window.location.href = '/profile';
            }
        }

        function useVoucher(voucherCode) {
            const voucherCard = $(`.voucher-card[data-voucher-code="${voucherCode}"]`);
            const useBtn = voucherCard.find('.use-btn');
            
            // Check if voucher is disabled
            if (useBtn.prop('disabled')) {
                return;
            }
            
            // Show loading
            useBtn.find('.btn-text').addClass('hidden');
            useBtn.find('.btn-loading').removeClass('hidden');
            useBtn.prop('disabled', true);
            
            // Check if we came from cart page
            const urlParams = new URLSearchParams(window.location.search);
            const returnPage = urlParams.get('return');
            
            if (returnPage === 'cart') {
                // Return to cart with selected voucher
                window.location.href = `/cart?voucher=${voucherCode}`;
            } else {
                // Original behavior - go to products page
                applyVoucherToCart(voucherCode);
                sessionStorage.setItem('selected_voucher', voucherCode);
                window.location.href = '/san-pham';
            }
        }

        function validateVoucherForCart(voucherCode) {
            // Get cart total from referrer page or make API call
            $.get('/api/cart', function(response) {
                if (response.success && response.cart) {
                    let cartTotal = 0;
                    response.cart.forEach(item => {
                        const price = parseFloat(item.product?.price) || 0;
                        cartTotal += price * item.quantity;
                    });

                    // Find voucher details
                    const voucher = @json($vouchers).find(v => v.code === voucherCode);
                    
                    if (voucher && voucher.min_order_value && cartTotal < voucher.min_order_value) {
                        alert(`Đơn hàng tối thiểu ${new Intl.NumberFormat('vi-VN').format(voucher.min_order_value)}đ để sử dụng voucher này. Giá trị giỏ hàng hiện tại: ${new Intl.NumberFormat('vi-VN').format(cartTotal)}đ`);
                        return;
                    }

                    // If validation passes, return to cart with voucher
                    window.location.href = `/cart?voucher=${voucherCode}`;
                } else {
                    // If can't get cart info, proceed anyway
                    window.location.href = `/cart?voucher=${voucherCode}`;
                }
            }).fail(function() {
                // If API fails, proceed anyway
                window.location.href = `/cart?voucher=${voucherCode}`;
            });
        }

        $(document).ready(function() {
            // Check if coming from cart and load cart info
            const urlParams = new URLSearchParams(window.location.search);
            const returnPage = urlParams.get('return');
            
            if (returnPage === 'cart') {
                loadCartInfoAndValidateVouchers();
            }

            // Apply button click handler
            $('.search-container button').on('click', function() {
                const voucherCode = $('#voucherSearch').val().trim();
                if (voucherCode) {
                    applyVoucherByCode(voucherCode);
                } else {
                    alert('Vui lòng nhập mã voucher');
                }
            });

            // Enter key handler for search input
            $('#voucherSearch').on('keypress', function(e) {
                if (e.which === 13) { // Enter key
                    const voucherCode = $(this).val().trim();
                    if (voucherCode) {
                        applyVoucherByCode(voucherCode);
                    }
                }
            });
        });

        function loadCartInfoAndValidateVouchers() {
            $.get('/api/cart', function(response) {
                if (response.success && response.cart) {
                    let cartTotal = 0;
                    response.cart.forEach(item => {
                        const price = parseFloat(item.product?.price) || 0;
                        cartTotal += price * item.quantity;
                    });

                    // Display cart info
                    $('#cartTotalDisplay').text(new Intl.NumberFormat('vi-VN').format(cartTotal) + 'đ');
                    $('#cartInfoDisplay').removeClass('hidden');

                    // Validate each voucher
                    $('.voucher-card').each(function() {
                        const voucherCard = $(this);
                        const minOrder = parseFloat(voucherCard.data('min-order')) || 0;
                        const voucherCode = voucherCard.data('voucher-code');
                        
                        validateVoucherCard(voucherCard, cartTotal, minOrder, voucherCode);
                    });
                }
            }).fail(function() {
                console.log('Could not load cart info');
            });
        }

        function validateVoucherCard(voucherCard, cartTotal, minOrder, voucherCode) {
            const statusContainer = voucherCard.find('.voucher-status');
            const validStatus = voucherCard.find('.status-valid');
            const invalidStatus = voucherCard.find('.status-invalid');
            const useBtn = voucherCard.find('.use-btn');
            
            statusContainer.removeClass('hidden');
            
            if (minOrder > 0 && cartTotal < minOrder) {
                // Invalid - not enough order value
                voucherCard.addClass('invalid').removeClass('valid');
                validStatus.addClass('hidden');
                invalidStatus.removeClass('hidden');
                invalidStatus.find('.status-message').text(
                    `Cần thêm ${new Intl.NumberFormat('vi-VN').format(minOrder - cartTotal)}đ để sử dụng`
                );
                useBtn.prop('disabled', true);
            } else {
                // Valid
                voucherCard.addClass('valid').removeClass('invalid');
                invalidStatus.addClass('hidden');
                validStatus.removeClass('hidden');
                useBtn.prop('disabled', false);
            }
        }

        // Apply voucher by code (for search functionality)
        function applyVoucherByCode(voucherCode) {
            // Check if the voucher exists in available vouchers
            const voucher = @json($vouchers).find(v => v.code.toLowerCase() === voucherCode.toLowerCase());
            
            if (voucher) {
                useVoucher(voucher.code);
            } else {
                // Check if we came from cart page for validation
                const urlParams = new URLSearchParams(window.location.search);
                const returnPage = urlParams.get('return');
                
                if (returnPage === 'cart') {
                    // Try to validate with cart total
                    $.get('/api/cart', function(response) {
                        if (response.success && response.cart) {
                            let cartTotal = 0;
                            response.cart.forEach(item => {
                                const price = parseFloat(item.product?.price) || 0;
                                cartTotal += price * item.quantity;
                            });

                            // Try to apply voucher with validation
                            $.post('/api/vouchers/apply', {
                                voucher_code: voucherCode,
                                cart_total: cartTotal
                            }, function(validateResponse) {
                                if (validateResponse.success) {
                                    window.location.href = `/cart?voucher=${voucherCode}`;
                                } else {
                                    showSearchMessage(validateResponse.message || 'Voucher không hợp lệ', 'error');
                                }
                            }).fail(function() {
                                showSearchMessage('Có lỗi xảy ra khi kiểm tra voucher', 'error');
                            });
                        } else {
                            // If can't get cart, proceed anyway
                            window.location.href = `/cart?voucher=${voucherCode}`;
                        }
                    }).fail(function() {
                        window.location.href = `/cart?voucher=${voucherCode}`;
                    });
                } else {
                    // Original behavior for products page
                    applyVoucherToCart(voucherCode);
                    window.location.href = '/san-pham';
                }
            }
        }

        function showSearchMessage(message, type) {
            // Create or update search message
            let messageEl = $('#searchMessage');
            if (messageEl.length === 0) {
                messageEl = $('<div id="searchMessage" class="mt-2 text-sm px-4"></div>');
                $('.search-container').parent().append(messageEl);
            }
            
            messageEl.text(message)
                    .removeClass('text-green-400 text-red-400')
                    .addClass(type === 'success' ? 'text-green-400' : 'text-red-400');
            
            setTimeout(() => {
                messageEl.remove();
            }, 3000);
        }

        // Search functionality
        document.getElementById('voucherSearch').addEventListener('input', function(e) {
            const searchTerm = e.target.value.toLowerCase();
            const voucherCards = document.querySelectorAll('.voucher-card');
            
            voucherCards.forEach(card => {
                const title = card.querySelector('h3').textContent.toLowerCase();
                const description = card.querySelector('p').textContent.toLowerCase();
                
                if (title.includes(searchTerm) || description.includes(searchTerm)) {
                    card.style.display = 'block';
                } else {
                    card.style.display = 'none';
                }
            });
        });

        // Apply voucher functionality
        function applyVoucherToCart(voucherCode) {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.post('/api/vouchers/apply', { voucher_code: voucherCode }, function(response) {
                if (response.success) {
                    // Store in session storage for immediate use
                    sessionStorage.setItem('applied_voucher', JSON.stringify(response.voucher));
                }
            });
        }
    </script>
</body>
</html>