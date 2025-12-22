<!DOCTYPE html>
<html class="dark" lang="vi">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Chi tiết đơn hàng - LENLAB</title>
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
        
        .order-container {
            background: #0f0f0f;
            max-width: 400px;
            margin: 0 auto;
            min-height: 100vh;
        }
        
        .order-section {
            background: rgba(45, 45, 45, 0.6);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 16px;
            padding: 20px;
            margin-bottom: 16px;
        }
        
        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }
        
        .status-processing {
            background: rgba(251, 191, 36, 0.2);
            color: #fbbf24;
            border: 1px solid rgba(251, 191, 36, 0.3);
        }
        
        .status-shipping {
            background: rgba(59, 130, 246, 0.2);
            color: #3b82f6;
            border: 1px solid rgba(59, 130, 246, 0.3);
        }
        
        .status-delivered {
            background: rgba(34, 197, 94, 0.2);
            color: #22c55e;
            border: 1px solid rgba(34, 197, 94, 0.3);
        }
        
        .status-cancelled {
            background: rgba(239, 68, 68, 0.2);
            color: #ef4444;
            border: 1px solid rgba(239, 68, 68, 0.3);
        }
        
        .product-item {
            display: flex;
            align-items: center;
            gap: 16px;
            padding: 16px 0;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        .product-item:last-child {
            border-bottom: none;
        }
        
        .action-btn {
            transition: all 0.3s ease;
            position: fixed;
            bottom: 20px;
            left: 50%;
            transform: translateX(-50%);
            width: calc(100% - 40px);
            max-width: 360px;
        }
        
        .cancel-btn {
            background: transparent;
            border: 1px solid #ef4444;
            color: #ef4444;
        }
        
        .cancel-btn:hover {
            background: #ef4444;
            color: white;
        }
        
        .support-btn {
            background: linear-gradient(135deg, #FAC638, #f59e0b);
            color: #0f0f0f;
        }
        
        .support-btn:hover {
            transform: translateX(-50%) translateY(-2px);
            box-shadow: 0 8px 25px rgba(250, 198, 56, 0.4);
        }
        
        .timeline-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 0;
        }
        
        .timeline-icon {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 16px;
        }
        
        .timeline-processing {
            background: rgba(251, 191, 36, 0.2);
            color: #fbbf24;
        }
        
        .timeline-shipping {
            background: rgba(59, 130, 246, 0.2);
            color: #3b82f6;
        }
    </style>
</head>

<body class="bg-background-dark">
    <div class="order-container">
        <!-- Header -->
        <div class="flex items-center justify-between p-4 border-b border-gray-700">
            <button onclick="window.location.href='/profile'" class="text-white hover:text-primary transition-colors">
                <span class="material-symbols-outlined text-2xl">arrow_back</span>
            </button>
            <h1 class="text-white font-semibold text-lg">Chi tiết đơn hàng</h1>
            <div class="w-8"></div>
        </div>

        <!-- Order Content -->
        <div class="p-4 pb-24">
            <!-- Order Header -->
            <div class="order-section">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <p class="text-gray-400 text-sm mb-1">MÃ ĐỚN HÀNG</p>
                        <p class="text-white font-semibold text-lg">#{{ $order['id'] }}</p>
                    </div>
                    <div class="status-badge status-{{ $order['status'] }}">
                        <span class="material-symbols-outlined text-sm">{{ App\Http\Controllers\OrderController::getStatusIcon($order['status']) }}</span>
                        {{ $order['status_text'] }}
                    </div>
                </div>
                
                <!-- Timeline -->
                <div class="timeline-item">
                    <div class="timeline-icon timeline-processing">
                        <span class="material-symbols-outlined">check</span>
                    </div>
                    <div>
                        <p class="text-white font-medium text-sm">Đơn hàng đã đặt</p>
                        <p class="text-gray-400 text-xs">{{ $order['created_at'] }}</p>
                    </div>
                </div>
                
                @if($order['status'] === 'processing')
                <div class="timeline-item">
                    <div class="timeline-icon timeline-processing">
                        <span class="material-symbols-outlined">schedule</span>
                    </div>
                    <div>
                        <p class="text-yellow-400 font-medium text-sm">Đang xử lý</p>
                        <p class="text-gray-400 text-xs">Đang chuẩn bị hàng</p>
                    </div>
                </div>
                @endif
                
                @if($order['status'] === 'shipping')
                <div class="timeline-item">
                    <div class="timeline-icon timeline-shipping">
                        <span class="material-symbols-outlined">local_shipping</span>
                    </div>
                    <div>
                        <p class="text-blue-400 font-medium text-sm">Đang giao hàng</p>
                        <p class="text-gray-400 text-xs">Shipper đang giao hàng đến bạn</p>
                    </div>
                </div>
                @endif
                
                @if($order['status'] === 'delivered')
                <div class="timeline-item">
                    <div class="timeline-icon" style="background: rgba(34, 197, 94, 0.2); color: #22c55e;">
                        <span class="material-symbols-outlined">check_circle</span>
                    </div>
                    <div>
                        <p class="text-green-400 font-medium text-sm">Đã giao hàng</p>
                        <p class="text-gray-400 text-xs">Đơn hàng đã được giao thành công</p>
                    </div>
                </div>
                @endif
                
                <!-- Giao hàng info -->
                <div class="mt-4 pt-4 border-t border-gray-700">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 bg-gray-700 rounded-full flex items-center justify-center">
                            <span class="material-symbols-outlined text-gray-400 text-sm">local_shipping</span>
                        </div>
                        <div>
                            <p class="text-white font-medium text-sm">Giao hàng</p>
                            <p class="text-gray-400 text-xs">Dự kiến: 3 ngày nữa</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Products -->
            <div class="order-section">
                <h3 class="text-white font-semibold text-lg mb-4">Sản phẩm ({{ count($order['items']) }})</h3>
                
                @foreach($order['items'] as $item)
                <div class="product-item cursor-pointer hover:bg-gray-700/30 transition-colors rounded-lg" onclick="viewProduct({{ $item['product_id'] }})">
                    <img src="/PRODUCT-IMG/{{ $item['image'] ?? 'default.jpg' }}" 
                         alt="{{ $item['name'] }}" 
                         class="w-15 h-15 object-cover rounded-lg flex-shrink-0"
                         style="width: 60px; height: 60px; min-width: 60px; min-height: 60px;"
                         onerror="this.src='https://via.placeholder.com/60x60/FAC638/FFFFFF?text={{ urlencode(substr($item['name'], 0, 2)) }}'">
                    <div class="flex-1">
                        <h4 class="text-white font-semibold text-sm mb-1">{{ $item['name'] }}</h4>
                        <p class="text-gray-400 text-xs mb-2">{{ $item['variant'] }}</p>
                        <p class="text-gray-400 text-xs">SL: {{ $item['quantity'] }}</p>
                    </div>
                    <div class="flex items-center gap-2">
                        <p class="text-primary font-bold text-sm">{{ number_format($item['price']) }}đ</p>
                        <span class="material-symbols-outlined text-gray-400 text-sm">chevron_right</span>
                    </div>
                </div>
                @endforeach
            </div>

            <!-- Shipping Address -->
            <div class="order-section">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-8 h-8 bg-primary/20 rounded-full flex items-center justify-center">
                        <span class="material-symbols-outlined text-primary text-sm">location_on</span>
                    </div>
                    <h3 class="text-white font-semibold text-lg">ĐỊA CHỈ NHẬN HÀNG</h3>
                </div>
                
                <div class="ml-11">
                    <p class="text-white font-semibold text-sm mb-1">{{ $order['shipping_address']['name'] }}</p>
                    <p class="text-gray-400 text-sm mb-2">{{ $order['shipping_address']['phone'] }}</p>
                    <p class="text-gray-300 text-sm">{{ $order['shipping_address']['address'] }}</p>
                </div>
            </div>

            <!-- Payment -->
            <div class="order-section">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-8 h-8 bg-primary/20 rounded-full flex items-center justify-center">
                        <span class="material-symbols-outlined text-primary text-sm">credit_card</span>
                    </div>
                    <h3 class="text-white font-semibold text-lg">THANH TOÁN</h3>
                </div>
                
                <div class="ml-11">
                    <p class="text-gray-300 text-sm mb-4">{{ $order['payment']['method'] }}</p>
                    
                    <div class="space-y-2">
                        <div class="flex justify-between items-center">
                            <span class="text-gray-400 text-sm">Tạm tính</span>
                            <span class="text-white text-sm">{{ number_format($order['payment']['subtotal']) }}đ</span>
                        </div>
                        
                        <div class="flex justify-between items-center">
                            <span class="text-gray-400 text-sm">Phí vận chuyển</span>
                            <span class="text-white text-sm">{{ number_format($order['payment']['shipping']) }}đ</span>
                        </div>
                        
                        @if($order['payment']['discount'] > 0)
                        <div class="flex justify-between items-center">
                            <span class="text-gray-400 text-sm">Giảm giá</span>
                            <span class="text-green-400 text-sm">-{{ number_format($order['payment']['discount']) }}đ</span>
                        </div>
                        @endif
                        
                        <hr class="border-gray-700 my-3">
                        
                        <div class="flex justify-between items-center">
                            <span class="text-white font-semibold">Tổng cộng</span>
                            <span class="text-primary font-bold text-lg">{{ number_format($order['payment']['total']) }}đ</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Fixed Bottom Button -->
        @if($order['status'] === 'processing')
            <!-- Cancel Order Button -->
            <button onclick="showCancelModal()" class="action-btn cancel-btn py-4 rounded-2xl font-bold text-lg flex items-center justify-center gap-2">
                <span class="material-symbols-outlined">cancel</span>
                Hủy đơn hàng
            </button>
        @else
            <!-- Contact Support Button -->
            <button onclick="contactSupport()" class="action-btn support-btn py-4 rounded-2xl font-bold text-lg flex items-center justify-center gap-2">
                <span class="material-symbols-outlined">support_agent</span>
                Liên hệ hỗ trợ
            </button>
        @endif
    </div>

    <!-- Cancel Order Modal -->
    <div id="cancelModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center p-4">
        <div class="bg-surface-dark rounded-2xl w-full max-w-sm mx-auto">
            <!-- Modal Header -->
            <div class="flex items-center justify-between p-6 border-b border-gray-700">
                <h3 class="text-white font-semibold text-lg">Lý do hủy đơn hàng</h3>
                <button onclick="hideCancelModal()" class="text-gray-400 hover:text-white">
                    <span class="material-symbols-outlined">close</span>
                </button>
            </div>
            
            <!-- Modal Body -->
            <div class="p-6">
                <form id="cancelForm">
                    <div class="space-y-4">
                        <!-- Predefined reasons -->
                        <label class="flex items-center gap-3 cursor-pointer">
                            <input type="radio" name="cancel_reason" value="Thay đổi ý định" class="w-4 h-4 text-primary bg-gray-700 border-gray-600 focus:ring-primary focus:ring-2">
                            <span class="text-white text-sm">Thay đổi ý định</span>
                        </label>
                        
                        <label class="flex items-center gap-3 cursor-pointer">
                            <input type="radio" name="cancel_reason" value="Tìm thấy sản phẩm tốt hơn" class="w-4 h-4 text-primary bg-gray-700 border-gray-600 focus:ring-primary focus:ring-2">
                            <span class="text-white text-sm">Tìm thấy sản phẩm tốt hơn</span>
                        </label>
                        
                        <label class="flex items-center gap-3 cursor-pointer">
                            <input type="radio" name="cancel_reason" value="Đặt nhầm sản phẩm" class="w-4 h-4 text-primary bg-gray-700 border-gray-600 focus:ring-primary focus:ring-2">
                            <span class="text-white text-sm">Đặt nhầm sản phẩm</span>
                        </label>
                        
                        <label class="flex items-center gap-3 cursor-pointer">
                            <input type="radio" name="cancel_reason" value="other" class="w-4 h-4 text-primary bg-gray-700 border-gray-600 focus:ring-primary focus:ring-2">
                            <span class="text-white text-sm">Lý do khác</span>
                        </label>
                        
                        <!-- Custom reason textbox (hidden by default) -->
                        <div id="customReasonBox" class="hidden">
                            <textarea 
                                id="customReason" 
                                name="custom_reason" 
                                placeholder="Nhập lý do hủy đơn hàng..."
                                class="w-full p-3 bg-gray-700 border border-gray-600 rounded-lg text-white placeholder-gray-400 focus:ring-2 focus:ring-primary focus:border-primary resize-none"
                                rows="3"
                                required></textarea>
                            <p class="text-red-400 text-xs mt-1 hidden" id="customReasonError">Vui lòng nhập lý do hủy đơn hàng</p>
                        </div>
                    </div>
                </form>
            </div>
            
            <!-- Modal Footer -->
            <div class="flex gap-3 p-6 pt-0">
                <button onclick="hideCancelModal()" class="flex-1 py-3 px-4 bg-gray-700 text-white rounded-xl font-medium hover:bg-gray-600 transition-colors">
                    Hủy bỏ
                </button>
                <button onclick="confirmCancelOrder()" class="flex-1 py-3 px-4 bg-red-600 text-white rounded-xl font-medium hover:bg-red-700 transition-colors">
                    Xác nhận hủy
                </button>
            </div>
        </div>
    </div>

    <script>
        // Check if returning from product detail
        function checkReturnFromProduct() {
            const urlParams = new URLSearchParams(window.location.search);
            const returnFrom = urlParams.get('return_from');
            
            if (returnFrom === 'product') {
                // Clean the URL without refreshing the page
                const cleanUrl = window.location.pathname;
                window.history.replaceState({}, document.title, cleanUrl);
                
                // Optional: Show a brief message or highlight
                console.log('Returned from product detail');
            }
        }
        
        // Navigate to product detail with return URL
        function viewProduct(productId) {
            const currentUrl = window.location.pathname;
            const returnUrl = encodeURIComponent(currentUrl + '?return_from=product');
            window.location.href = `/san-pham/${productId}?return=${returnUrl}`;
        }
        
        // Initialize on page load
        document.addEventListener('DOMContentLoaded', function() {
            checkReturnFromProduct();
        });
        
        function showCancelModal() {
            document.getElementById('cancelModal').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }
        
        function hideCancelModal() {
            document.getElementById('cancelModal').classList.add('hidden');
            document.body.style.overflow = 'auto';
            
            // Reset form
            document.getElementById('cancelForm').reset();
            document.getElementById('customReasonBox').classList.add('hidden');
            document.getElementById('customReasonError').classList.add('hidden');
        }
        
        // Show/hide custom reason textbox
        document.addEventListener('DOMContentLoaded', function() {
            const radioButtons = document.querySelectorAll('input[name="cancel_reason"]');
            const customReasonBox = document.getElementById('customReasonBox');
            const customReasonTextarea = document.getElementById('customReason');
            
            radioButtons.forEach(radio => {
                radio.addEventListener('change', function() {
                    if (this.value === 'other') {
                        customReasonBox.classList.remove('hidden');
                        customReasonTextarea.setAttribute('required', 'required');
                    } else {
                        customReasonBox.classList.add('hidden');
                        customReasonTextarea.removeAttribute('required');
                        customReasonTextarea.value = '';
                        document.getElementById('customReasonError').classList.add('hidden');
                    }
                });
            });
        });
        
        function confirmCancelOrder() {
            const form = document.getElementById('cancelForm');
            const selectedReason = document.querySelector('input[name="cancel_reason"]:checked');
            const customReason = document.getElementById('customReason');
            const customReasonError = document.getElementById('customReasonError');
            
            // Validation
            if (!selectedReason) {
                alert('Vui lòng chọn lý do hủy đơn hàng');
                return;
            }
            
            if (selectedReason.value === 'other') {
                if (!customReason.value.trim()) {
                    customReasonError.classList.remove('hidden');
                    customReason.focus();
                    return;
                } else {
                    customReasonError.classList.add('hidden');
                }
            }
            
            // Get final reason
            let finalReason = selectedReason.value;
            if (selectedReason.value === 'other') {
                finalReason = customReason.value.trim();
            }
            
            // Send cancel request
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            
            $.post('/orders/{{ $order["id"] }}/cancel', {
                cancel_reason: finalReason
            }, function(response) {
                if (response.success) {
                    hideCancelModal();
                    alert('Đã hủy đơn hàng thành công');
                    window.location.reload();
                } else {
                    alert(response.message || 'Có lỗi xảy ra khi hủy đơn hàng');
                }
            }).fail(function() {
                alert('Có lỗi xảy ra, vui lòng thử lại!');
            });
        }
        
        function contactSupport() {
            // In real app, this could open a chat widget, redirect to support page, or open phone dialer
            const message = `Xin chào, tôi cần hỗ trợ về đơn hàng #{{ $order['id'] }}`;
            const phoneNumber = '0909123456';
            
            if (confirm('Liên hệ hỗ trợ qua điện thoại?')) {
                window.location.href = `tel:${phoneNumber}`;
            }
        }
    </script>
</body>
</html>