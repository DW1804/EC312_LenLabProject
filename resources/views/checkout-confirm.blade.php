<!DOCTYPE html>
<html class="dark" lang="vi">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Tổng quan đơn hàng - LENLAB</title>
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
            padding-bottom: 100px;
        }
        
        .confirm-container {
            background: #0f0f0f;
            max-width: 400px;
            margin: 0 auto;
            min-height: 100vh;
        }
        
        .progress-bar {
            height: 4px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 2px;
            overflow: hidden;
        }
        
        .progress-fill {
            height: 100%;
            background: #FAC638;
            border-radius: 2px;
            transition: width 0.3s ease;
            width: 66%; /* 66% for step 2 of 3 */
        }
        
        .section-card {
            background: #1a1a1a;
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 12px;
            padding: 16px;
            margin-bottom: 16px;
        }
        
        .product-item {
            background: rgba(45, 45, 45, 0.3);
            border-radius: 8px;
            padding: 12px;
            margin-bottom: 12px;
        }
        
        .product-item:last-child {
            margin-bottom: 0;
        }
        
        .confirm-btn {
            background: #FAC638;
            transition: all 0.3s ease;
            border-radius: 25px;
        }
        
        .confirm-btn:hover {
            background: #e6b332;
            transform: translateY(-1px);
        }
        
        .confirm-btn:disabled {
            opacity: 0.5;
            cursor: not-allowed;
            transform: none;
        }
        
        .total-amount {
            color: #FAC638;
            font-size: 24px;
            font-weight: bold;
        }
        
        .discount-text {
            color: #ef4444;
        }
        
        .voucher-card-valid {
            border-color: rgba(34, 197, 94, 0.3) !important;
        }
        
        .voucher-card-invalid {
            opacity: 0.7;
            border-color: rgba(239, 68, 68, 0.3) !important;
        }
        
        .voucher-icon {
            background: linear-gradient(135deg, #FAC638, #f59e0b);
        }
        
        /* Modal animation */
        #voucherModalContent {
            transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        #voucherListModal {
            transition: opacity 0.3s ease;
        }
        
        #voucherListModal.show {
            opacity: 1;
        }
        
        #voucherListModal.hide {
            opacity: 0;
        }
        
        /* Scrollbar styling for voucher list */
        #voucherListContainer::-webkit-scrollbar {
            width: 4px;
        }
        
        #voucherListContainer::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 2px;
        }
        
        #voucherListContainer::-webkit-scrollbar-thumb {
            background: #FAC638;
            border-radius: 2px;
        }
        
        #voucherListContainer::-webkit-scrollbar-thumb:hover {
            background: #e6b332;
        }
    </style>
</head>

<body class="bg-background-dark">
    <div class="confirm-container">
        <!-- Header -->
        <div class="flex items-center justify-between p-4">
            <button onclick="window.location.href='/checkout'" class="text-white hover:text-primary transition-colors">
                <span class="material-symbols-outlined text-2xl">arrow_back</span>
            </button>
            <h1 class="text-white font-semibold text-lg">Tổng quan đơn hàng</h1>
            <div class="w-8"></div>
        </div>

        <!-- Progress Bar -->
        <div class="px-4 mb-6">
            <div class="progress-bar">
                <div class="progress-fill"></div>
            </div>
        </div>

        <!-- Content -->
        <div class="px-4">
            <!-- Địa chỉ nhận hàng -->
            <div class="section-card">
                <div class="flex items-center justify-between mb-3">
                    <h3 class="text-white font-semibold">Địa chỉ nhận hàng</h3>
                    <button onclick="editAddress()" class="text-primary text-sm">Sửa</button>
                </div>
                
                <div class="text-gray-300">
                    <p class="font-medium mb-1">{{ $address['full_name'] }}</p>
                    <p class="text-sm mb-1">{{ $address['phone'] }}</p>
                    <p class="text-sm">
                        {{ $address['specific_address'] }}
                        @if(isset($address['ward_name']))
                            , {{ $address['ward_name'] }}
                        @endif
                        @if(isset($address['province_name']))
                            , {{ $address['province_name'] }}
                        @endif
                    </p>
                </div>
            </div>

            <!-- Sản phẩm -->
            <div class="section-card">
                <div class="flex items-center justify-between mb-3">
                    <h3 class="text-white font-semibold">Sản phẩm</h3>
                </div>
                
                <div class="space-y-3">
                    @foreach($cartItems as $item)
                        <div class="product-item">
                            <div class="flex items-center gap-3">
                                <img src="/PRODUCT-IMG/{{ $item->product->image ?? 'default.jpg' }}" 
                                     alt="{{ $item->product->name }}" 
                                     class="w-16 h-16 object-cover rounded-lg"
                                     onerror="this.src='https://via.placeholder.com/64x64/FAC638/FFFFFF?text=SP'">
                                
                                <div class="flex-1">
                                    <h4 class="text-white font-medium text-sm mb-1">{{ $item->product->name }}</h4>
                                    @if($item->variant_info)
                                        @php
                                            $variantInfo = is_string($item->variant_info) ? json_decode($item->variant_info, true) : $item->variant_info;
                                        @endphp
                                        @if(isset($variantInfo['variant_name']) && $variantInfo['variant_name'])
                                            <p class="text-gray-400 text-xs mb-1">{{ $variantInfo['variant_name'] }}</p>
                                        @endif
                                    @endif
                                    <p class="text-primary font-semibold text-sm">{{ number_format(($item->price_at_time ?? $item->product->price)) }}đ</p>
                                </div>
                                
                                <div class="text-right">
                                    <span class="text-white text-sm">x{{ $item->quantity }}</span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Ghi chú đơn hàng -->
            <div class="section-card">
                <h3 class="text-white font-semibold mb-4">Ghi chú đơn hàng</h3>
                <textarea id="orderNote" 
                          class="w-full bg-gray-700 border border-gray-600 rounded-lg p-3 text-white placeholder-gray-400 resize-none focus:outline-none focus:border-primary" 
                          rows="3" 
                          placeholder="Nếu bạn muốn gói quà để tặng hoặc không hiển thị giá trên đơn hàng, hãy để lại ghi chú với chúng tôi"></textarea>
            </div>

            <!-- Chi tiết thanh toán -->
            <div class="section-card">
                <h3 class="text-white font-semibold mb-4">Chi tiết thanh toán</h3>
                
                <!-- Voucher Section -->
                @if(!isset($voucherCode) || !$voucherCode)
                <div class="mb-4 p-3 bg-gray-800 rounded-lg border border-gray-600">
                    <div class="space-y-3">
                        <!-- Input row -->
                        <div class="flex items-center gap-2">
                            <input type="text" 
                                   id="voucherInput" 
                                   class="flex-1 bg-gray-700 border border-gray-600 rounded-lg px-3 py-2 text-white placeholder-gray-400 text-sm focus:outline-none focus:border-primary" 
                                   placeholder="Nhập mã giảm giá"
                                   readonly>
                            <button onclick="showVoucherList()" 
                                    id="selectVoucherBtn"
                                    class="bg-gray-600 text-white px-3 py-2 rounded-lg text-sm font-medium hover:bg-gray-500 transition-colors whitespace-nowrap">
                                Chọn
                            </button>
                        </div>
                        <!-- Button row -->
                        <div class="flex gap-2">
                            <button onclick="applyVoucher()" 
                                    id="applyVoucherBtn"
                                    class="flex-1 bg-primary text-black py-2 rounded-lg text-sm font-medium hover:bg-primary/80 transition-colors">
                                Áp dụng
                            </button>
                        </div>
                    </div>
                    <div id="voucherMessage" class="mt-2 text-sm hidden"></div>
                </div>
                @endif
                
                <div class="space-y-3">
                    <div class="flex justify-between items-center">
                        <span class="text-gray-300">Tạm tính</span>
                        <span class="text-white">{{ number_format($subtotal) }}đ</span>
                    </div>
                    
                    @php
                        // Use voucher discount passed from controller
                        $discountAmount = $voucherDiscount ?? 0;
                        $actualShippingFee = $shippingFee ?? 30000;
                        $finalTotal = $subtotal + $actualShippingFee - $discountAmount;
                    @endphp
                    
                    <div class="flex justify-between items-center">
                        <span class="text-gray-300">Phí vận chuyển</span>
                        <span class="text-white">{{ number_format($actualShippingFee) }}đ</span>
                    </div>
                    
                                       
                    @if($discountAmount > 0)
                        <div class="flex justify-between items-center">
                            <span class="text-gray-300">Giảm giá</span>
                            <span class="discount-text">-{{ number_format($discountAmount) }}đ</span>
                        </div>
                        @if(isset($voucherCode) && $voucherCode)
                            <div class="flex justify-between items-center">
                                <span class="text-gray-400 text-sm">Mã: {{ $voucherCode }}</span>
                                <button onclick="removeVoucher()" class="text-red-400 text-sm hover:text-red-300">Xóa</button>
                            </div>
                        @endif
                    @endif
                    
                    <hr class="border-gray-600">
                    
                    <div class="flex justify-between items-center pt-2">
                        <span class="text-white font-bold text-lg">Tổng cộng</span>
                        <div class="text-right">
                            <div class="total-amount">{{ number_format($finalTotal) }}đ</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Spacer for bottom button -->
            <div class="mb-20"></div>
        </div>

        <!-- Fixed Bottom Button -->
        <div class="fixed bottom-0 left-1/2 transform -translate-x-1/2 w-full max-w-[400px] bg-background-dark p-4">
            <button onclick="proceedToPayment()" class="confirm-btn w-full py-4 text-black font-bold text-lg">
                Tiếp tục thanh toán
            </button>
        </div>
    </div>

    <!-- Voucher List Modal -->
    <div id="voucherListModal" class="fixed inset-0 bg-black/60 backdrop-blur-sm z-50 hidden">
        <div class="flex items-end justify-center min-h-screen p-4">
            <div class="bg-background-dark w-full max-w-[400px] rounded-t-3xl transform translate-y-full transition-transform duration-300 max-h-[80vh] flex flex-col" id="voucherModalContent">
                <div class="w-12 h-1 bg-gray-600 rounded-full mx-auto mt-4 mb-4 flex-shrink-0"></div>
                
                <div class="px-4 pb-4 flex-1 flex flex-col min-h-0">
                    <div class="flex items-center justify-between mb-4 flex-shrink-0">
                        <h3 class="text-white text-lg font-semibold">Chọn mã giảm giá</h3>
                        <button onclick="hideVoucherList()" class="text-gray-400 hover:text-white p-1">
                            <span class="material-symbols-outlined">close</span>
                        </button>
                    </div>
                    
                    <!-- Loading State -->
                    <div id="voucherLoading" class="text-center py-8 flex-shrink-0">
                        <div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-primary"></div>
                        <p class="text-gray-400 mt-2 text-sm">Đang tải voucher...</p>
                    </div>
                    
                    <!-- Voucher List -->
                    <div id="voucherListContainer" class="space-y-2 flex-1 overflow-y-auto hidden">
                        <!-- Vouchers will be loaded here -->
                    </div>
                    
                    <!-- Empty State -->
                    <div id="voucherEmptyState" class="text-center py-8 flex-shrink-0 hidden">
                        <div class="w-12 h-12 bg-gray-600 rounded-full flex items-center justify-center mx-auto mb-3">
                            <span class="material-symbols-outlined text-gray-400 text-xl">local_offer</span>
                        </div>
                        <h4 class="text-white text-base font-semibold mb-2">Chưa có voucher</h4>
                        <p class="text-gray-400 text-sm">Hiện tại chưa có voucher nào khả dụng</p>
                    </div>
                    
                    <button onclick="hideVoucherList()" class="w-full mt-4 py-3 text-gray-400 hover:text-white transition-colors text-sm flex-shrink-0">
                        Đóng
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        let availableVouchers = [];
        let currentCartTotal = {{ $subtotal }};

        function editAddress() {
            window.location.href = '/checkout';
        }

        function showVoucherList() {
            const modal = $('#voucherListModal');
            const content = $('#voucherModalContent');
            
            // Show modal with fade in
            modal.removeClass('hidden').addClass('show');
            setTimeout(() => {
                content.removeClass('translate-y-full');
            }, 10);
            
            // Load vouchers
            loadAvailableVouchers();
        }

        function hideVoucherList() {
            const modal = $('#voucherListModal');
            const content = $('#voucherModalContent');
            
            modal.removeClass('show').addClass('hide');
            content.addClass('translate-y-full');
            setTimeout(() => {
                modal.addClass('hidden').removeClass('hide');
            }, 300);
        }

        function loadAvailableVouchers() {
            $('#voucherLoading').removeClass('hidden');
            $('#voucherListContainer').addClass('hidden');
            $('#voucherEmptyState').addClass('hidden');
            
            $.get('/api/vouchers')
                .done(function(response) {
                    $('#voucherLoading').addClass('hidden');
                    
                    if (response.success && response.vouchers && response.vouchers.length > 0) {
                        availableVouchers = response.vouchers;
                        renderVoucherList();
                        $('#voucherListContainer').removeClass('hidden');
                    } else {
                        $('#voucherEmptyState').removeClass('hidden');
                    }
                })
                .fail(function(xhr, status, error) {
                    $('#voucherLoading').addClass('hidden');
                    $('#voucherEmptyState').removeClass('hidden');
                    
                    // Show error message in empty state
                    $('#voucherEmptyState h4').text('Lỗi tải voucher');
                    $('#voucherEmptyState p').text('Không thể tải danh sách voucher. Vui lòng thử lại.');
                    
                    console.error('Error loading vouchers:', error);
                });
        }

        function renderVoucherList() {
            let html = '';
            
            availableVouchers.forEach(voucher => {
                const isValid = checkVoucherValidity(voucher);
                const cardClass = isValid ? 'voucher-card-valid' : 'voucher-card-invalid';
                const buttonClass = isValid ? 'bg-primary text-black hover:bg-primary/80' : 'bg-gray-600 text-gray-400 cursor-not-allowed';
                const buttonText = isValid ? 'Chọn' : 'Không đủ ĐK';
                
                html += `
                    <div class="voucher-card ${cardClass} bg-gray-800 border border-gray-600 rounded-xl p-3 ${isValid ? 'hover:border-primary' : ''} transition-colors">
                        <div class="flex items-start justify-between gap-3">
                            <div class="flex items-start gap-3 flex-1 min-w-0">
                                <div class="voucher-icon w-10 h-10 ${getVoucherIconClass(voucher.type)} rounded-lg flex items-center justify-center flex-shrink-0">
                                    <span class="material-symbols-outlined text-white text-lg">${getVoucherIcon(voucher.type)}</span>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <h4 class="text-white font-semibold text-base mb-1 truncate">${voucher.code}</h4>
                                    <p class="text-gray-300 text-sm mb-1">
                                        ${getVoucherDescription(voucher)}
                                    </p>
                                    ${voucher.min_order_value ? `<p class="text-gray-400 text-xs mb-1">Tối thiểu ${formatPrice(voucher.min_order_value)}đ</p>` : ''}
                                    <p class="text-gray-400 text-xs">HSD: ${formatDate(voucher.end_date)}</p>
                                    ${!isValid ? `<p class="text-red-400 text-xs mt-1">${getInvalidReason(voucher)}</p>` : ''}
                                </div>
                            </div>
                            <button onclick="selectVoucher('${voucher.code}')" 
                                    class="${buttonClass} px-3 py-2 rounded-lg text-xs font-medium transition-colors flex-shrink-0"
                                    ${!isValid ? 'disabled' : ''}>
                                ${buttonText}
                            </button>
                        </div>
                    </div>
                `;
            });
            
            $('#voucherListContainer').html(html);
        }

        function checkVoucherValidity(voucher) {
            // Check if voucher has minimum order requirement
            if (voucher.min_order_value && currentCartTotal < voucher.min_order_value) {
                return false;
            }
            
            // Check if voucher is still valid (not expired)
            if (voucher.end_date) {
                const now = new Date();
                const endDate = new Date(voucher.end_date);
                if (endDate < now) {
                    return false;
                }
            }
            
            // Check if voucher has started
            if (voucher.start_date) {
                const now = new Date();
                const startDate = new Date(voucher.start_date);
                if (startDate > now) {
                    return false;
                }
            }
            
            return true;
        }

        function getInvalidReason(voucher) {
            if (voucher.min_order_value && currentCartTotal < voucher.min_order_value) {
                const needed = voucher.min_order_value - currentCartTotal;
                return `Cần thêm ${formatPrice(needed)}đ`;
            }
            
            if (voucher.end_date) {
                const now = new Date();
                const endDate = new Date(voucher.end_date);
                if (endDate < now) {
                    return 'Đã hết hạn';
                }
            }
            
            if (voucher.start_date) {
                const now = new Date();
                const startDate = new Date(voucher.start_date);
                if (startDate > now) {
                    return 'Chưa có hiệu lực';
                }
            }
            
            return 'Không khả dụng';
        }

        function getVoucherDescription(voucher) {
            if (voucher.type === 'percent' || voucher.type === 'percentage') {
                return `Giảm ${voucher.discount_value}%`;
            } else if (voucher.type === 'fixed' || voucher.type === 'fixed_amount') {
                return `Giảm ${formatPrice(voucher.discount_value)}đ`;
            } else if (voucher.type === 'free_shipping') {
                return 'Miễn phí vận chuyển';
            }
            return 'Voucher giảm giá';
        }

        function getVoucherIcon(type) {
            switch(type) {
                case 'free_shipping': return 'local_shipping';
                case 'percent':
                case 'percentage': return 'percent';
                case 'fixed':
                case 'fixed_amount': return 'payments';
                default: return 'local_offer';
            }
        }

        function getVoucherIconClass(type) {
            switch(type) {
                case 'free_shipping': return 'bg-orange-500';
                case 'percent':
                case 'percentage': return 'bg-yellow-500';
                case 'fixed':
                case 'fixed_amount': return 'bg-green-500';
                default: return 'bg-primary';
            }
        }

        function formatPrice(price) {
            return new Intl.NumberFormat('vi-VN').format(price);
        }

        function formatDate(dateString) {
            if (!dateString) {
                return 'Không giới hạn';
            }
            const date = new Date(dateString);
            if (isNaN(date.getTime())) {
                return 'Không giới hạn';
            }
            return date.toLocaleDateString('vi-VN');
        }

        function selectVoucher(voucherCode) {
            // Show loading state on the selected voucher button
            const button = $(`button[onclick="selectVoucher('${voucherCode}')"]`);
            const originalText = button.text();
            button.prop('disabled', true).text('Đang chọn...');
            
            $('#voucherInput').val(voucherCode);
            hideVoucherList();
            
            // Auto apply the selected voucher
            setTimeout(() => {
                applyVoucher();
                // Reset button state in case modal is opened again
                setTimeout(() => {
                    button.prop('disabled', false).text(originalText);
                }, 1000);
            }, 300);
        }

        function applyVoucher() {
            const voucherCode = $('#voucherInput').val().trim();
            if (!voucherCode) {
                showVoucherMessage('Vui lòng nhập mã voucher', 'error');
                return;
            }

            // Show loading
            $('#applyVoucherBtn').prop('disabled', true).text('Đang xử lý...');

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            // Only send voucher code, backend will calculate based on selected items
            $.post('/api/vouchers/apply', { 
                voucher_code: voucherCode
            }, function(response) {
                if (response.success) {
                    showVoucherMessage('Áp dụng mã giảm giá thành công!', 'success');
                    // Reload page to show updated prices
                    setTimeout(() => {
                        window.location.reload();
                    }, 1000);
                } else {
                    showVoucherMessage(response.message || 'Mã voucher không hợp lệ', 'error');
                    $('#applyVoucherBtn').prop('disabled', false).text('Áp dụng');
                }
            }).fail(function(xhr) {
                let errorMessage = 'Có lỗi xảy ra, vui lòng thử lại!';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
                }
                showVoucherMessage(errorMessage, 'error');
                $('#applyVoucherBtn').prop('disabled', false).text('Áp dụng');
            });
        }

        function removeVoucher() {
            if (confirm('Bạn có chắc muốn xóa mã giảm giá?')) {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                $.post('/api/vouchers/remove', {}, function(response) {
                    if (response.success) {
                        window.location.reload();
                    } else {
                        alert('Có lỗi xảy ra khi xóa voucher');
                    }
                }).fail(function() {
                    alert('Có lỗi xảy ra, vui lòng thử lại!');
                });
            }
        }

        function showVoucherMessage(message, type) {
            const messageEl = $('#voucherMessage');
            messageEl.removeClass('text-green-400 text-red-400 hidden')
                     .addClass(type === 'success' ? 'text-green-400' : 'text-red-400')
                     .text(message);
            
            if (type === 'error') {
                setTimeout(() => {
                    messageEl.addClass('hidden');
                }, 3000);
            }
        }

        // Allow manual typing in voucher input
        $('#voucherInput').on('click', function() {
            $(this).prop('readonly', false);
            $(this).attr('placeholder', 'Nhập mã giảm giá');
        });

        $('#voucherInput').on('focus', function() {
            $(this).prop('readonly', false);
            $(this).attr('placeholder', 'Nhập mã giảm giá');
        });

        // Enter key handler for voucher input
        $('#voucherInput').on('keypress', function(e) {
            if (e.which === 13) { // Enter key
                applyVoucher();
            }
        });

        // Close modal when clicking outside
        $('#voucherListModal').on('click', function(e) {
            if (e.target === this) {
                hideVoucherList();
            }
        });

        function proceedToPayment() {
            // Get order note
            const orderNote = $('#orderNote').val().trim();
            
            // Save note to session
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            // Show loading state
            $('.confirm-btn').prop('disabled', true).html(`
                <span class="animate-spin material-symbols-outlined mr-2">refresh</span>
                Đang xử lý...
            `);

            $.post('/api/checkout/set-note', { note: orderNote }, function(response) {
                if (response.success) {
                    window.location.href = '/checkout/payment';
                } else {
                    alert('Có lỗi xảy ra: ' + response.message);
                    $('.confirm-btn').prop('disabled', false).text('Tiếp tục thanh toán');
                }
            }).fail(function(xhr) {
                let errorMessage = 'Có lỗi xảy ra, vui lòng thử lại!';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
                }
                
                alert(errorMessage);
                $('.confirm-btn').prop('disabled', false).text('Tiếp tục thanh toán');
            });
        }
    </script>
</body>
</html>