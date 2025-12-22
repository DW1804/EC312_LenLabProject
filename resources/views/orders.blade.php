<!DOCTYPE html>
<html class="dark" lang="vi">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Đơn hàng của tôi - LENLAB</title>
    <link href="https://fonts.googleapis.com/css2?family=Spline+Sans:wght@300;400;500;600;700&family=Noto+Sans:wght@400;500;700&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
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
        
        .orders-container {
            background: #0f0f0f;
            max-width: 400px;
            margin: 0 auto;
            min-height: 100vh;
        }
        
        .order-card {
            background: rgba(45, 45, 45, 0.6);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 16px;
            padding: 20px;
            margin-bottom: 16px;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .order-card:hover {
            background: rgba(60, 60, 60, 0.8);
            border-color: #FAC638;
        }
        
        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 11px;
            font-weight: 600;
        }
        
        .status-processing {
            background: rgba(251, 191, 36, 0.2);
            color: #fbbf24;
        }
        
        .status-shipping {
            background: rgba(59, 130, 246, 0.2);
            color: #3b82f6;
        }
        
        .status-delivered {
            background: rgba(34, 197, 94, 0.2);
            color: #22c55e;
        }
        
        .status-cancelled {
            background: rgba(239, 68, 68, 0.2);
            color: #ef4444;
        }
    </style>
</head>

<body class="bg-background-dark">
    <div class="orders-container">
        <!-- Header -->
        <div class="flex items-center justify-between p-4 border-b border-gray-700">
            <button onclick="window.location.href='/profile'" class="text-white hover:text-primary transition-colors">
                <span class="material-symbols-outlined text-2xl">arrow_back</span>
            </button>
            <h1 class="text-white font-semibold text-lg">Đơn hàng của tôi</h1>
            <div class="w-8"></div>
        </div>

        <!-- Orders List -->
        <div class="p-4">
            @if($orders->count() > 0)
                @foreach($orders as $order)
                <div class="order-card" onclick="window.location.href='/orders/{{ $order->order_id }}'">
                    <div class="flex items-center justify-between mb-3">
                        <div>
                            <p class="text-gray-400 text-xs mb-1">MÃ ĐƠN HÀNG</p>
                            <p class="text-white font-semibold">#{{ $order->order_id }}</p>
                        </div>
                        <div class="status-badge status-{{ $order->status }}">
                            <span class="material-symbols-outlined text-xs">{{ $order->status_icon }}</span>
                            {{ $order->status_text }}
                        </div>
                    </div>
                    
                    <div class="flex items-center gap-3 mb-3">
                        @php
                            $firstItem = $order->orderItems->first();
                            $itemCount = $order->orderItems->count();
                        @endphp
                        
                        @if($firstItem && $firstItem->product_name)
                        <img src="{{ $firstItem->product_image && $firstItem->product_image !== 'default.jpg' ? '/PRODUCT-IMG/' . $firstItem->product_image : 'https://via.placeholder.com/50x50/FAC638/FFFFFF?text=' . urlencode(substr($firstItem->product_name, 0, 2)) }}" 
                             alt="{{ $firstItem->product_name }}" 
                             class="w-12 h-12 object-cover rounded-lg"
                             onerror="this.src='https://via.placeholder.com/50x50/FAC638/FFFFFF?text={{ urlencode(substr($firstItem->product_name, 0, 2)) }}'">
                        <div class="flex-1">
                            <p class="text-white text-sm font-medium">{{ $firstItem->product_name }}</p>
                            @if($itemCount > 1)
                                <p class="text-gray-400 text-xs">và {{ $itemCount - 1 }} sản phẩm khác</p>
                            @else
                                <p class="text-gray-400 text-xs">{{ $itemCount }} sản phẩm</p>
                            @endif
                        </div>
                        @else
                        <div class="w-12 h-12 bg-gray-600 rounded-lg flex items-center justify-center">
                            <span class="material-symbols-outlined text-gray-400">inventory_2</span>
                        </div>
                        <div class="flex-1">
                            <p class="text-white text-sm font-medium">Đơn hàng #{{ $order->order_id }}</p>
                            <p class="text-gray-400 text-xs">{{ $itemCount }} sản phẩm</p>
                        </div>
                        @endif
                        
                        <p class="text-primary font-bold">{{ number_format($order->total_amount) }}đ</p>
                    </div>
                    
                    <div class="flex items-center justify-between text-xs text-gray-400">
                        <span>{{ date('d/m/Y') }}</span>
                        <div class="flex items-center gap-1">
                            <span>Xem chi tiết</span>
                            <span class="material-symbols-outlined text-xs">chevron_right</span>
                        </div>
                    </div>
                </div>
                @endforeach
            @else
                <!-- Empty State -->
                <div class="text-center py-12">
                    <div class="w-16 h-16 bg-gray-600 rounded-full flex items-center justify-center mx-auto mb-4">
                        <span class="material-symbols-outlined text-gray-400 text-2xl">receipt_long</span>
                    </div>
                    <h3 class="text-white text-lg font-semibold mb-2">Chưa có đơn hàng</h3>
                    <p class="text-gray-400 text-sm mb-6">Bạn chưa có đơn hàng nào</p>
                    <button onclick="window.location.href='/san-pham'" class="bg-primary text-background-dark px-6 py-3 rounded-2xl font-bold">
                        Mua sắm ngay
                    </button>
                </div>
            @endif
        </div>
    </div>
</body>
</html>