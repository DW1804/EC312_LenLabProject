

@extends('admin.layout')

@section('title', $siteName . ' - Dashboard')

@php
    // Variables for header
    $pageTitle = 'Dashboard';
    $pageHeading = 'Chào mừng trở lại!';
    $pageDescription = 'Đây là tổng quan hoạt động của cửa hàng';
    $createUrl = '#';
@endphp

@section('content')
<!-- Stats Cards -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <!-- Customers Card -->
    <div class="bg-surface-light dark:bg-surface-dark rounded-xl p-6 border border-border-light dark:border-border-dark shadow-sm">
        <div class="flex items-center">
            <div class="flex-shrink-0">
                <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg flex items-center justify-center">
                    <span class="material-icons-round text-white text-xl">people</span>
                </div>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Khách hàng</p>
                <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $totalCustomers }}</p>
            </div>
        </div>
    </div>

    <!-- Products Card -->
    <div class="bg-surface-light dark:bg-surface-dark rounded-xl p-6 border border-border-light dark:border-border-dark shadow-sm">
        <div class="flex items-center">
            <div class="flex-shrink-0">
                <div class="w-12 h-12 bg-gradient-to-br from-green-500 to-green-600 rounded-lg flex items-center justify-center">
                    <span class="material-icons-round text-white text-xl">inventory_2</span>
                </div>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Sản phẩm</p>
                <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $totalProducts }}</p>
            </div>
        </div>
    </div>

    <!-- Orders Card -->
    <div class="bg-surface-light dark:bg-surface-dark rounded-xl p-6 border border-border-light dark:border-border-dark shadow-sm">
        <div class="flex items-center">
            <div class="flex-shrink-0">
                <div class="w-12 h-12 bg-gradient-to-br from-primary to-primary-hover rounded-lg flex items-center justify-center">
                    <span class="material-icons-round text-white text-xl">shopping_bag</span>
                </div>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Đơn hàng</p>
                <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $totalOrders }}</p>
            </div>
        </div>
    </div>

    <!-- Pending Orders Card -->
    <div class="bg-surface-light dark:bg-surface-dark rounded-xl p-6 border border-border-light dark:border-border-dark shadow-sm">
        <div class="flex items-center">
            <div class="flex-shrink-0">
                <div class="w-12 h-12 bg-gradient-to-br from-yellow-500 to-yellow-600 rounded-lg flex items-center justify-center">
                    <span class="material-icons-round text-white text-xl">schedule</span>
                </div>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Chờ xử lý</p>
                <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $pendingOrders }}</p>
            </div>
        </div>
    </div>
</div>

<!-- Charts and Recent Orders -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <!-- Order Status Chart -->
    <div class="bg-surface-light dark:bg-surface-dark rounded-xl p-6 border border-border-light dark:border-border-dark shadow-sm">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-6 text-center">Trạng thái đơn hàng</h3>
        
        <!-- Chart Container - Centered -->
        <div class="flex flex-col items-center">
            <div class="relative w-64 h-64 mb-6">
                <canvas id="orderStatusChart"></canvas>
            </div>
            
            <!-- Custom Legend - Larger Text -->
            <div class="grid grid-cols-1 gap-3 w-full max-w-xs">
                <div class="flex items-center justify-between p-3 bg-yellow-50 dark:bg-yellow-900/20 rounded-lg">
                    <div class="flex items-center gap-3">
                        <div class="w-4 h-4 bg-yellow-500 rounded-full"></div>
                        <span class="text-base font-medium text-gray-900 dark:text-white">Chờ xử lý</span>
                    </div>
                    <span class="text-lg font-bold text-yellow-600 dark:text-yellow-400">{{ $pendingOrders ?? 0 }}</span>
                </div>
                
                <div class="flex items-center justify-between p-3 bg-green-50 dark:bg-green-900/20 rounded-lg">
                    <div class="flex items-center gap-3">
                        <div class="w-4 h-4 bg-green-500 rounded-full"></div>
                        <span class="text-base font-medium text-gray-900 dark:text-white">Đã giao</span>
                    </div>
                    <span class="text-lg font-bold text-green-600 dark:text-green-400">{{ $deliveredOrders ?? 0 }}</span>
                </div>
                
                <div class="flex items-center justify-between p-3 bg-red-50 dark:bg-red-900/20 rounded-lg">
                    <div class="flex items-center gap-3">
                        <div class="w-4 h-4 bg-red-500 rounded-full"></div>
                        <span class="text-base font-medium text-gray-900 dark:text-white">Đã hủy</span>
                    </div>
                    <span class="text-lg font-bold text-red-600 dark:text-red-400">{{ $cancelledOrders ?? 0 }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Orders -->
    <div class="bg-surface-light dark:bg-surface-dark rounded-xl p-6 border border-border-light dark:border-border-dark shadow-sm">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Đơn hàng gần nhất</h3>
            <a href="{{ route('admin.orders.index') }}" class="text-primary hover:text-primary-hover text-sm font-medium">
                Xem tất cả
            </a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-border-light dark:border-border-dark">
                        <th class="text-left py-2 text-gray-500 dark:text-gray-400 font-medium">Mã đơn</th>
                        <th class="text-left py-2 text-gray-500 dark:text-gray-400 font-medium">Khách hàng</th>
                        <th class="text-left py-2 text-gray-500 dark:text-gray-400 font-medium">Tổng tiền</th>
                        <th class="text-left py-2 text-gray-500 dark:text-gray-400 font-medium">Trạng thái</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border-light dark:divide-border-dark">
                    @forelse($recentOrders as $order)
                    <tr>
                        <td class="py-3 text-gray-900 dark:text-white font-mono">ORD-{{ $order->order_id }}</td>
                        <td class="py-3 text-gray-900 dark:text-white">{{ $order->full_name ?? 'N/A' }}</td>
                        <td class="py-3 text-gray-900 dark:text-white font-medium">{{ number_format($order->total_amount ?? 0) }}₫</td>
                        <td class="py-3">
                            @switch($order->status)
                                @case('pending')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-300">
                                        Chờ xử lý
                                    </span>
                                    @break
                                @case('confirmed')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300">
                                        Đã xác nhận
                                    </span>
                                    @break
                                @case('shipping')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800 dark:bg-purple-900/30 dark:text-purple-300">
                                        Đang giao
                                    </span>
                                    @break
                                @case('delivered')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300">
                                        Đã giao
                                    </span>
                                    @break
                                @case('cancelled')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-300">
                                        Đã hủy
                                    </span>
                                    @break
                                @default
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-900/30 dark:text-gray-300">
                                        {{ $order->status }}
                                    </span>
                            @endswitch
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="py-8 text-center">
                            <div class="flex flex-col items-center gap-2">
                                <span class="material-icons-round text-4xl text-gray-400">shopping_bag</span>
                                <p class="text-gray-500 dark:text-gray-400">Không có đơn hàng nào</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Top 5 Products -->
<div class="bg-surface-light dark:bg-surface-dark rounded-xl p-6 border border-border-light dark:border-border-dark shadow-sm">
    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Top 5 Sản phẩm bán chạy</h3>
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr class="border-b border-gray-200 dark:border-gray-700">
                    <th class="text-left py-3 px-4 font-semibold text-gray-900 dark:text-white">Tên sản phẩm</th>
                    <th class="text-right py-3 px-4 font-semibold text-gray-900 dark:text-white">Đã bán</th>
                </tr>
            </thead>
            <tbody>
                @forelse($topProducts as $product)
                <tr class="border-b border-gray-100 dark:border-gray-800 hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-colors">
                    <td class="py-3 px-4 text-gray-900 dark:text-white">{{ $product->name }}</td>
                    <td class="py-3 px-4 text-right">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300">
                            {{ $product->total_sold }} cái
                        </span>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="2" class="py-8 px-4 text-center text-gray-500 dark:text-gray-400">
                        Chưa có dữ liệu sản phẩm
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>


<!-- Quick Actions -->
<div class="mt-8">
    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Thao tác nhanh</h3>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
        <a href="{{ route('admin.products.create') }}" class="bg-surface-light dark:bg-surface-dark rounded-xl p-4 border border-border-light dark:border-border-dark shadow-sm hover:shadow-md transition-shadow group">
            <div class="flex items-center">
                <div class="w-10 h-10 bg-primary/10 rounded-lg flex items-center justify-center group-hover:bg-primary/20 transition-colors">
                    <span class="material-icons-round text-primary">add</span>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-gray-900 dark:text-white">Thêm sản phẩm</p>
                    <p class="text-xs text-gray-500 dark:text-gray-400">Tạo sản phẩm mới</p>
                </div>
            </div>
        </a>

        <a href="{{ route('admin.orders.index') }}" class="bg-surface-light dark:bg-surface-dark rounded-xl p-4 border border-border-light dark:border-border-dark shadow-sm hover:shadow-md transition-shadow group">
            <div class="flex items-center">
                <div class="w-10 h-10 bg-blue-500/10 rounded-lg flex items-center justify-center group-hover:bg-blue-500/20 transition-colors">
                    <span class="material-icons-round text-blue-500">shopping_bag</span>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-gray-900 dark:text-white">Quản lý đơn hàng</p>
                    <p class="text-xs text-gray-500 dark:text-gray-400">Xem tất cả đơn hàng</p>
                </div>
            </div>
        </a>

        <a href="{{ route('admin.customers.index') }}" class="bg-surface-light dark:bg-surface-dark rounded-xl p-4 border border-border-light dark:border-border-dark shadow-sm hover:shadow-md transition-shadow group">
            <div class="flex items-center">
                <div class="w-10 h-10 bg-green-500/10 rounded-lg flex items-center justify-center group-hover:bg-green-500/20 transition-colors">
                    <span class="material-icons-round text-green-500">people</span>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-gray-900 dark:text-white">Khách hàng</p>
                    <p class="text-xs text-gray-500 dark:text-gray-400">Quản lý khách hàng</p>
                </div>
            </div>
        </a>

        <a href="#" class="bg-surface-light dark:bg-surface-dark rounded-xl p-4 border border-border-light dark:border-border-dark shadow-sm hover:shadow-md transition-shadow group">
            <div class="flex items-center">
                <div class="w-10 h-10 bg-purple-500/10 rounded-lg flex items-center justify-center group-hover:bg-purple-500/20 transition-colors">
                    <span class="material-icons-round text-purple-500">analytics</span>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-gray-900 dark:text-white">Báo cáo</p>
                    <p class="text-xs text-gray-500 dark:text-gray-400">Xem thống kê</p>
                </div>
            </div>
        </a>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Order Status Chart
    const ctx = document.getElementById('orderStatusChart').getContext('2d');
    new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: ['Chờ xử lý', 'Đã xác nhận', 'Đang giao', 'Đã giao', 'Đã hủy'],
            datasets: [{
                data: [{{ $pendingOrders }}, 0, 0, 0, 0],
                backgroundColor: [
                    '#fbbf24', // yellow-400
                    '#3b82f6', // blue-500
                    '#8b5cf6', // purple-500
                    '#10b981', // green-500
                    '#ef4444'  // red-500
                ],
                borderWidth: 0,
                hoverOffset: 4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        padding: 20,
                        usePointStyle: true,
                        font: {
                            size: 12
                        }
                    }
                }
            },
            cutout: '60%'
        }
    });
    <div class="card">
    <div class="card-header">Doanh thu 7 ngày qua</div>
    <div class="card-body">
        <canvas id="revenueChart"></canvas>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script> <script>
    const ctx = document.getElementById('revenueChart').getContext('2d');
    const revenueChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: {!! json_encode($dates) !!}, // Dữ liệu ngày từ Controller
            datasets: [{
                label: 'Doanh thu (VNĐ)',
                data: {!! json_encode($totals) !!}, // Dữ liệu tiền từ Controller
                borderColor: 'rgb(75, 192, 192)',
                tension: 0.1,
                fill: true
            }]
        }
    });
</script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        
        // --- 1. BIỂU ĐỒ DOANH THU (Line Chart) ---
        const ctxRevenue = document.getElementById('revenueChart');
        if (ctxRevenue) {
            new Chart(ctxRevenue.getContext('2d'), {
                type: 'line',
                data: {
                    labels: {!! json_encode($dates) !!}, // Ngày
                    datasets: [{
                        label: 'Doanh thu (VNĐ)',
                        data: {!! json_encode($totals) !!}, // Tiền
                        borderColor: '#0d6efd',
                        backgroundColor: 'rgba(13, 110, 253, 0.1)',
                        fill: true,
                        tension: 0.4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: { 
                            beginAtZero: true,
                            ticks: { callback: (val) => new Intl.NumberFormat('vi-VN').format(val) + ' đ' }
                        }
                    }
                }
            });
        }

        // --- 2. BIỂU ĐỒ TRẠNG THÁI ĐƠN HÀNG (Doughnut Chart) ---
        const ctxStatus = document.getElementById('orderStatusChart');
        if (ctxStatus) {
            new Chart(ctxStatus.getContext('2d'), {
                type: 'doughnut',
                data: {
                    labels: ['Chờ xử lý', 'Đã giao', 'Đã hủy'],
                    datasets: [{
                        data: [
                            {{ $pendingOrders ?? 0 }}, 
                            {{ $deliveredOrders ?? 0 }}, 
                            {{ $cancelledOrders ?? 0 }}
                        ],
                        backgroundColor: [
                            '#eab308', // Yellow-500
                            '#22c55e', // Green-500  
                            '#ef4444'  // Red-500
                        ],
                        borderWidth: 3,
                        borderColor: '#ffffff',
                        hoverBorderWidth: 4,
                        hoverBorderColor: '#ffffff'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: true,
                    cutout: '65%',
                    plugins: {
                        legend: {
                            display: false // Tắt legend mặc định vì chúng ta có custom legend
                        },
                        tooltip: {
                            backgroundColor: 'rgba(0, 0, 0, 0.8)',
                            titleColor: '#ffffff',
                            bodyColor: '#ffffff',
                            borderColor: '#374151',
                            borderWidth: 1,
                            cornerRadius: 8,
                            displayColors: true,
                            callbacks: {
                                label: function(context) {
                                    const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                    const percentage = total > 0 ? ((context.parsed / total) * 100).toFixed(1) : 0;
                                    return context.label + ': ' + context.parsed + ' (' + percentage + '%)';
                                }
                            }
                        }
                    },
                    animation: {
                        animateRotate: true,
                        duration: 1000
                    },
                    elements: {
                        arc: {
                            borderWidth: 3
                        }
                    }
                }
            });
        }
    });
</script>
@endpush
