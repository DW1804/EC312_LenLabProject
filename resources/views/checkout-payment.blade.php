<!DOCTYPE html>
<html class="dark" lang="vi">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Phương thức thanh toán - LENLAB</title>
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
        
        .payment-container {
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
            width: 100%; /* 100% for step 3 of 3 */
        }
        
        .payment-method {
            background: #1a1a1a;
            border: 2px solid #FAC638;
            border-radius: 12px;
            padding: 16px;
            transition: all 0.3s ease;
            cursor: pointer;
            margin-bottom: 12px;
        }
        
        .payment-method.unselected {
            background: #1a1a1a;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        .payment-method:hover {
            background: #2a2a2a;
        }
        
        .checkout-btn {
            background: #FAC638;
            transition: all 0.3s ease;
            border-radius: 25px;
        }
        
        .checkout-btn:hover {
            background: #e6b332;
            transform: translateY(-1px);
        }
        
        .checkout-btn:disabled {
            opacity: 0.5;
            cursor: not-allowed;
            transform: none;
        }
        
        .total-amount {
            color: #FAC638;
            font-size: 24px;
            font-weight: bold;
        }
    </style>
</head>

<body class="bg-background-dark">
    <div class="payment-container">
        <!-- Header -->
        <div class="flex items-center justify-between p-4">
            <button onclick="window.location.href='/checkout/confirm'" class="text-white hover:text-primary transition-colors">
                <span class="material-symbols-outlined text-2xl">arrow_back</span>
            </button>
            <h1 class="text-white font-semibold text-lg">Phương thức thanh toán</h1>
            <div class="w-8"></div>
        </div>

        <!-- Progress Bar -->
        <div class="px-4 mb-6">
            <div class="progress-bar">
                <div class="progress-fill"></div>
            </div>
        </div>

        <!-- Content -->
        <div class="px-6">
            <!-- Địa chỉ giao hàng -->
            <div class="mb-6">
                <div class="flex items-center justify-between mb-3">
                    <h3 class="text-gray-400 text-sm font-medium uppercase tracking-wider">ĐỊA CHỈ GIAO HÀNG</h3>
                    <button onclick="changeAddress()" class="text-primary text-sm">Thay đổi</button>
                </div>
                
                <div class="bg-surface-dark/60 border border-gray-700 rounded-lg p-4">
                    <p class="text-white font-medium mb-1">{{ $address['full_name'] }}</p>
                    <p class="text-gray-300 text-sm mb-2">{{ $address['phone'] }}</p>
                    <p class="text-gray-300 text-sm">
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

            <!-- Chọn phương thức -->
            <div class="mb-6">
                <h3 class="text-gray-400 text-sm font-medium mb-4 uppercase tracking-wider">CHỌN PHƯƠNG THỨC</h3>
                
                <div class="space-y-0">
                    <div class="payment-method" data-method="cod">
                        <div class="flex items-center gap-4">
                            <div class="w-10 h-10 bg-primary/20 rounded-lg flex items-center justify-center">
                                <span class="material-symbols-outlined text-primary text-xl">local_shipping</span>
                            </div>
                            <div class="flex-1">
                                <p class="text-white font-semibold">Thanh toán khi nhận hàng</p>
                                <p class="text-gray-400 text-sm">Thanh toán tiền mặt (COD)</p>
                            </div>
                            <div class="w-6 h-6 bg-primary rounded-full flex items-center justify-center">
                                <span class="material-symbols-outlined text-black text-sm">check</span>
                            </div>
                        </div>
                    </div>

                    <div class="payment-method unselected" data-method="bank_transfer">
                        <div class="flex items-center gap-4">
                            <div class="w-10 h-10 bg-gray-600/20 rounded-lg flex items-center justify-center">
                                <span class="material-symbols-outlined text-gray-400 text-xl">account_balance</span>
                            </div>
                            <div class="flex-1">
                                <p class="text-white font-semibold">Chuyển khoản ngân hàng</p>
                                <p class="text-gray-400 text-sm">Hỗ trợ quét mã QR</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Thông tin bảo mật và chính sách -->
            <div class="mb-8 space-y-4">
                <!-- Thông tin bảo mật -->
                <div class="flex items-start gap-3">
                    <span class="material-symbols-outlined text-gray-400 text-lg mt-0.5">lock</span>
                    <p class="text-gray-400 text-sm leading-relaxed">Thông tin thanh toán được bảo mật tuyệt đối.</p>
                </div>
                
                <!-- Chính sách mua hàng -->
                <div class="text-gray-400 text-sm leading-relaxed">
                    <p class="mb-2">Bằng cách hoàn tất thanh toán, bạn đã đồng ý với 
                    <a href="/chinh-sach" class="text-primary underline hover:text-primary/80 transition-colors"> các điều khoản và chính sách mua hàng</a> 
                    của chúng tôi.</p>
                </div>
            </div>

            <!-- Spacer for bottom content -->
            <div class="mb-32"></div>
        </div>

        <!-- Fixed Bottom Section -->
        <div class="fixed bottom-0 left-1/2 transform -translate-x-1/2 w-full max-w-[400px] bg-background-dark p-6">
            <!-- Total Amount Breakdown -->
            <div class="mb-4 space-y-2">
                <div class="flex justify-between items-center">
                    <span class="text-gray-400 text-sm">Tiền hàng</span>
                    <span class="text-white text-sm">{{ number_format($subtotal) }}đ</span>
                </div>
                
                <div class="flex justify-between items-center">
                    <span class="text-gray-400 text-sm">Phí vận chuyển</span>
                    <span class="text-white text-sm">{{ number_format($shippingFee) }}đ</span>
                </div>
                
                @if(isset($deliveryTime))
                <div class="flex justify-between items-center">
                    <span class="text-gray-400 text-sm">Thời gian giao hàng</span>
                    <span class="text-primary text-sm">{{ $deliveryTime }}</span>
                </div>
                @endif
                
                @php
                    // Calculate final total
                    $finalTotal = $subtotal + $shippingFee - ($voucherDiscount ?? 0);
                @endphp
                
                @if(isset($voucherDiscount) && $voucherDiscount > 0)
                <div class="flex justify-between items-center">
                    <span class="text-gray-400 text-sm">Giảm giá</span>
                    <span class="text-green-400 text-sm">-{{ number_format($voucherDiscount) }}đ</span>
                </div>
                @if(isset($voucherCode))
                <div class="flex justify-between items-center">
                    <span class="text-gray-400 text-xs">Mã: {{ $voucherCode }}</span>
                </div>
                @endif
                @endif
                
                <hr class="border-gray-700 my-2">
                
                <div class="flex justify-between items-center">
                    <span class="text-white font-bold text-lg">Tổng cộng</span>
                    <div class="text-right">
                        <div class="total-amount">{{ number_format($finalTotal) }}đ</div>
                    </div>
                </div>
            </div>
            
            <!-- Continue Button -->
            <button onclick="placeOrder()" class="checkout-btn w-full py-4 text-black font-bold text-lg flex items-center justify-center gap-2">
                Đặt hàng
                <span class="material-symbols-outlined">check_circle</span>
            </button>
        </div>
    </div>

    <script>
        let selectedPaymentMethod = 'cod';

        $(document).ready(function() {
            // Payment method selection
            $('.payment-method').click(function() {
                // Remove selected state from all methods
                $('.payment-method').removeClass('selected').addClass('unselected');
                $('.payment-method .w-6').remove();
                
                // Add selected state to clicked method
                $(this).removeClass('unselected').addClass('selected');
                $(this).find('.flex-1').after(`
                    <div class="w-6 h-6 bg-primary rounded-full flex items-center justify-center">
                        <span class="material-symbols-outlined text-black text-sm">check</span>
                    </div>
                `);
                
                selectedPaymentMethod = $(this).data('method');
                
                // Update icon colors
                if (selectedPaymentMethod === 'cod') {
                    $(this).find('.w-10 div').removeClass('bg-gray-600/20').addClass('bg-primary/20');
                    $(this).find('.material-symbols-outlined').removeClass('text-gray-400').addClass('text-primary');
                } else {
                    // Reset COD method
                    $('.payment-method[data-method="cod"] .w-10 div').removeClass('bg-primary/20').addClass('bg-gray-600/20');
                    $('.payment-method[data-method="cod"] .material-symbols-outlined').removeClass('text-primary').addClass('text-gray-400');
                }
            });
        });

        function changeAddress() {
            window.location.href = '/checkout/confirm';
        }

        function placeOrder() {
            const paymentData = {
                payment_method: selectedPaymentMethod
            };

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            // Disable button
            $('.checkout-btn').prop('disabled', true).html(`
                <span class="animate-spin material-symbols-outlined mr-2">refresh</span>
                Đang xử lý...
            `);

            // If bank transfer, redirect to bank transfer page
            if (selectedPaymentMethod === 'bank_transfer') {
                $.post('/api/checkout/prepare-bank-transfer', paymentData, function(response) {
                    if (response.success) {
                        window.location.href = `/checkout/bank-transfer?order_code=${response.order_code}&total=${response.total}`;
                    } else {
                        alert('Có lỗi xảy ra: ' + response.message);
                        $('.checkout-btn').prop('disabled', false).html(`
                            Đặt hàng
                            <span class="material-symbols-outlined">check_circle</span>
                        `);
                    }
                }).fail(function(xhr) {
                    let errorMessage = 'Có lỗi xảy ra, vui lòng thử lại!';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMessage = xhr.responseJSON.message;
                    }
                    
                    alert(errorMessage);
                    $('.checkout-btn').prop('disabled', false).html(`
                        Đặt hàng
                        <span class="material-symbols-outlined">check_circle</span>
                    `);
                });
            } else {
                // For COD, create order directly
                $.post('/api/checkout/create-order', paymentData, function(response) {
                    if (response.success) {
                        // Show success state
                        $('.checkout-btn').removeClass('bg-primary').addClass('bg-green-500').html(`
                            <span class="material-symbols-outlined mr-2">check_circle</span>
                            Đặt hàng thành công!
                        `);
                        
                        setTimeout(() => {
                            window.location.href = response.redirect_url;
                        }, 1000);
                    } else {
                        alert('Có lỗi xảy ra: ' + response.message);
                        $('.checkout-btn').prop('disabled', false).html(`
                            Đặt hàng
                            <span class="material-symbols-outlined">check_circle</span>
                        `);
                    }
                }).fail(function(xhr) {
                    let errorMessage = 'Có lỗi xảy ra, vui lòng thử lại!';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMessage = xhr.responseJSON.message;
                    }
                    
                    alert(errorMessage);
                    $('.checkout-btn').prop('disabled', false).html(`
                        Đặt hàng
                        <span class="material-symbols-outlined">check_circle</span>
                    `);
                });
            }
        }
    </script>
</body>
</html>