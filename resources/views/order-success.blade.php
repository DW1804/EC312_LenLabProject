<!DOCTYPE html>
<html class="dark" lang="vi">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Đặt hàng thành công - LENLAB</title>
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
        
        .success-container {
            background: #0f0f0f;
            max-width: 400px;
            margin: 0 auto;
            min-height: 100vh;
            position: relative;
        }
        
        .success-icon {
            width: 120px;
            height: 120px;
            background: linear-gradient(135deg, #FAC638, #f59e0b);
            border-radius: 20px;
            margin: 0 auto 24px;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            animation: successPulse 2s ease-in-out infinite;
        }
        
        .success-icon::after {
            content: '';
            position: absolute;
            top: -10px;
            right: -10px;
            width: 40px;
            height: 40px;
            background: #10b981;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .success-icon::before {
            content: '✓';
            position: absolute;
            top: -10px;
            right: -10px;
            width: 40px;
            height: 40px;
            color: white;
            font-size: 20px;
            font-weight: bold;
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 1;
        }
        
        @keyframes successPulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.05); }
        }
        
        .celebration-icon {
            font-size: 60px;
            color: #FAC638;
            animation: bounce 1s ease-in-out infinite;
        }
        
        @keyframes bounce {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-10px); }
        }
        
        .order-info {
            background: rgba(45, 45, 45, 0.6);
            border-radius: 16px;
            padding: 20px;
            margin: 24px 20px;
        }
        
        .info-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 16px;
        }
        
        .info-row:last-child {
            margin-bottom: 0;
        }
        
        .info-label {
            color: #9ca3af;
            font-size: 14px;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .info-value {
            color: white;
            font-weight: 600;
            text-align: right;
        }
        
        .total-amount {
            color: #FAC638;
            font-size: 24px;
            font-weight: bold;
        }
        
        .eco-notice {
            background: rgba(251, 146, 60, 0.1);
            border: 1px solid rgba(251, 146, 60, 0.3);
            border-radius: 12px;
            padding: 16px;
            margin: 20px;
            display: flex;
            align-items: start;
            gap: 12px;
        }
        
        .continue-btn {
            background: #FAC638;
            transition: all 0.3s ease;
            border-radius: 25px;
        }
        
        .continue-btn:hover {
            background: #e6b332;
            transform: translateY(-1px);
        }
        
        .order-detail-btn {
            background: transparent;
            border: 1px solid rgba(255, 255, 255, 0.3);
            color: white;
            transition: all 0.3s ease;
            border-radius: 25px;
        }
        
        .order-detail-btn:hover {
            border-color: #FAC638;
            color: #FAC638;
        }
        
        .help-text {
            color: #6b7280;
            font-size: 12px;
            text-align: center;
            margin-top: 16px;
        }
        
        .floating-elements {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 200px;
            pointer-events: none;
            overflow: hidden;
        }
        
        .floating-dot {
            position: absolute;
            width: 8px;
            height: 8px;
            background: #FAC638;
            border-radius: 50%;
            opacity: 0.6;
            animation: float 3s ease-in-out infinite;
        }
        
        .floating-dot:nth-child(1) {
            top: 20%;
            left: 10%;
            animation-delay: 0s;
        }
        
        .floating-dot:nth-child(2) {
            top: 40%;
            right: 15%;
            animation-delay: 1s;
        }
        
        .floating-dot:nth-child(3) {
            top: 60%;
            left: 20%;
            animation-delay: 2s;
        }
        
        @keyframes float {
            0%, 100% { transform: translateY(0) rotate(0deg); opacity: 0.6; }
            50% { transform: translateY(-20px) rotate(180deg); opacity: 1; }
        }
    </style>
</head>

<body class="bg-background-dark">
    <div class="success-container">
        <!-- Floating Elements -->
        <div class="floating-elements">
            <div class="floating-dot"></div>
            <div class="floating-dot"></div>
            <div class="floating-dot"></div>
        </div>

        <!-- Header -->
        <div class="flex items-center justify-between p-4">
            <div class="w-8"></div>
            <div class="w-8"></div>
        </div>

        <!-- Success Content -->
        <div class="text-center px-6 pt-8">
            <!-- Success Icon -->
            <div class="success-icon">
                <span class="celebration-icon">🎉</span>
            </div>
            
            <!-- Success Message -->
            <h1 class="text-white text-2xl font-bold mb-4">Đặt hàng thành công!</h1>
            <p class="text-gray-400 text-sm leading-relaxed mb-8">
                Cảm ơn bạn đã lựa chọn sản phẩm từ<br>
                cửa hàng của chúng tôi. Đơn hàng đang được<br>
                chuẩn bị bằng cả trái tim. 💝
            </p>
        </div>

        <!-- Order Information -->
        <div class="order-info">
            <div class="info-row">
                <span class="info-label">Mã đơn hàng</span>
                <span class="info-value">#{{ $orderCode ?? 'WOOL-8823' }}</span>
            </div>
            
            <div class="info-row">
                <span class="info-label">
                    <span class="material-symbols-outlined text-sm">local_shipping</span>
                    Giao hàng dự kiến
                </span>
                <span class="info-value">{{ $estimatedDelivery ?? '24 - 26 Tháng 10' }}</span>
            </div>
            
            <div class="info-row">
                <span class="info-label">
                    <span class="material-symbols-outlined text-sm">payment</span>
                    Phương thức
                </span>
                <span class="info-value">
                    @if(($paymentMethod ?? 'cod') === 'cod')
                        Thanh toán khi nhận hàng
                    @elseif(($paymentMethod ?? 'cod') === 'bank_transfer')
                        Chuyển khoản ngân hàng
                    @else
                        Ví điện tử MoMo
                    @endif
                </span>
            </div>
            
            <hr class="border-gray-600 my-4">
            
            <div class="info-row">
                <span class="info-label text-lg font-semibold text-white">Tổng thanh toán</span>
                <span class="total-amount">{{ number_format($total ?? 450000) }}đ</span>
            </div>
        </div>

        <!-- Eco Notice -->
        <div class="eco-notice">
            <span class="material-symbols-outlined text-orange-400 text-xl">eco</span>
            <div>
                <p class="text-orange-300 font-medium text-sm mb-1">ĐÓNG GÓI XANH</p>
                <p class="text-orange-200 text-xs leading-relaxed">
                    Đơn hàng này không sử dụng nilon. Chúng tôi 
                    sử dụng giấy tái chế thân thiện với môi 
                    trường.
                </p>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="px-6 space-y-3">
            <button onclick="continueShopping()" class="continue-btn w-full py-4 text-black font-bold text-lg flex items-center justify-center gap-2">
                Tiếp tục mua sắm
                <span class="material-symbols-outlined">arrow_forward</span>
            </button>
            
            <button onclick="viewOrderDetail()" class="order-detail-btn w-full py-3 font-medium">
                Xem chi tiết đơn hàng
            </button>
        </div>

        <!-- Help Text -->
        <p class="help-text px-6">
            <span class="material-symbols-outlined text-xs">help</span>
            Cần hỗ trợ về đơn hàng này?
        </p>
    </div>

    <script>
        $(document).ready(function() {
            // Add some celebration effects
            setTimeout(() => {
                createConfetti();
            }, 500);
        });

        function createConfetti() {
            // Simple confetti effect
            for (let i = 0; i < 20; i++) {
                setTimeout(() => {
                    const confetti = $(`
                        <div style="
                            position: fixed;
                            top: -10px;
                            left: ${Math.random() * 100}%;
                            width: 6px;
                            height: 6px;
                            background: ${Math.random() > 0.5 ? '#FAC638' : '#10b981'};
                            border-radius: 50%;
                            animation: confettiFall 3s linear forwards;
                            z-index: 1000;
                        "></div>
                    `);
                    
                    $('body').append(confetti);
                    
                    setTimeout(() => {
                        confetti.remove();
                    }, 3000);
                }, i * 100);
            }
        }

        function continueShopping() {
            window.location.href = '/';
        }

        function viewOrderDetail() {
            const orderCode = '{{ $orderCode ?? "" }}';
            if (orderCode) {
                window.location.href = `/orders/${orderCode}`;
            } else {
                alert('Không tìm thấy mã đơn hàng!');
            }
        }

        // Add confetti animation CSS
        $('<style>')
            .prop('type', 'text/css')
            .html(`
                @keyframes confettiFall {
                    0% {
                        transform: translateY(-10px) rotate(0deg);
                        opacity: 1;
                    }
                    100% {
                        transform: translateY(100vh) rotate(360deg);
                        opacity: 0;
                    }
                }
            `)
            .appendTo('head');
    </script>
</body>
</html>