@extends('admin.layout')

@section('title', getSiteName() . ' - Dashboard Nâng cao')

@section('content')
<div class="space-y-6">
    
    {{-- Welcome Section --}}
    <div class="bg-gradient-to-r from-primary to-yellow-500 rounded-xl p-6 text-white">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold mb-2">Chào mừng trở lại, {{ Auth::guard('admin')->user()->name }}!</h1>
                <p class="opacity-90">Đây là tổng quan về hoạt động của hệ thống hôm nay</p>
            </div>
            <div class="hidden md:block">
                <span class="material-icons-round text-6xl opacity-20">dashboard</span>
            </div>
        </div>
    </div>

    {{-- Stats Grid --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <x-admin.stats-widget 
            title="Tổng người dùng" 
            value="0" 
            icon="people" 
            color="blue"
            url="/admin/users" />
            
        <x-admin.stats-widget 
            title="Đánh giá chờ duyệt" 
            value="0" 
            icon="rate_review" 
            color="yellow"
            url="/admin/reviews?status=pending" />
            
        <x-admin.stats-widget 
            title="Đơn hàng tuần này" 
            value="0" 
            icon="shopping_cart" 
            color="green"
            url="/admin/orders" />
            
        <x-admin.stats-widget 
            title="Doanh thu tháng" 
            value="0đ" 
            icon="attach_money" 
            color="primary"
            url="/admin/reports" />
    </div>

    {{-- Charts Row --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <x-admin.chart-widget 
            title="Đánh giá theo ngày" 
            chartId="reviewsChart" 
            type="line" />
            
        <x-admin.chart-widget 
            title="Đơn hàng theo ngày" 
            chartId="ordersChart" 
            type="bar" />
    </div>

    {{-- Bottom Row --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        {{-- Rating Distribution --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Phân bố đánh giá</h3>
            <div id="rating-distribution" class="space-y-3">
                @for($i = 5; $i >= 1; $i--)
                    <div class="flex items-center gap-3">
                        <div class="flex items-center gap-1 w-12">
                            <span class="text-sm font-medium">{{ $i }}</span>
                            <span class="material-icons-round text-yellow-400 text-sm">star</span>
                        </div>
                        <div class="flex-1 bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                            <div class="bg-yellow-400 h-2 rounded-full transition-all duration-500" 
                                 id="rating-bar-{{ $i }}" style="width: 0%"></div>
                        </div>
                        <span class="text-sm text-gray-500 w-8 text-right" id="rating-count-{{ $i }}">0</span>
                    </div>
                @endfor
            </div>
            <div class="mt-4 pt-4 border-t border-gray-200 dark:border-gray-700">
                <div class="flex items-center justify-between text-sm">
                    <span class="text-gray-600 dark:text-gray-400">Điểm trung bình</span>
                    <span class="font-semibold text-gray-900 dark:text-white" id="average-rating">0.0</span>
                </div>
            </div>
        </div>

        {{-- Top Products --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Sản phẩm bán chạy</h3>
            <div id="top-products" class="space-y-3">
                <div class="text-center py-8 text-gray-500">
                    <div class="animate-spin rounded-full h-6 w-6 border-b-2 border-primary mx-auto mb-2"></div>
                    <p class="text-sm">Đang tải...</p>
                </div>
            </div>
        </div>

        {{-- Recent Activity --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Hoạt động gần đây</h3>
            <div id="recent-activity" class="space-y-3">
                <div class="text-center py-8 text-gray-500">
                    <div class="animate-spin rounded-full h-6 w-6 border-b-2 border-primary mx-auto mb-2"></div>
                    <p class="text-sm">Đang tải...</p>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
// Dashboard data loading
document.addEventListener('DOMContentLoaded', function() {
    loadDashboardStats();
    loadRatingDistribution();
    loadTopProducts();
    loadRecentActivity();
});

function loadDashboardStats() {
    fetch('/admin/dashboard/stats/overview')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const stats = data.stats;
                
                // Update stat widgets
                updateStatValue('tong-nguoi-dung', stats.total_users.toLocaleString());
                updateStatValue('danh-gia-cho-duyet', stats.pending_reviews.toLocaleString());
                updateStatValue('don-hang-tuan-nay', stats.recent_orders.toLocaleString());
                updateStatValue('doanh-thu-thang', formatCurrency(stats.revenue_this_month));
            }
        })
        .catch(error => console.error('Error loading dashboard stats:', error));
}

function updateStatValue(slug, value) {
    const element = document.getElementById('stat-' + slug);
    if (element) {
        element.textContent = value;
    }
}

function formatCurrency(amount) {
    return new Intl.NumberFormat('vi-VN', {
        style: 'currency',
        currency: 'VND'
    }).format(amount);
}

// Load reviews chart data
function loadReviewsChartData() {
    fetch('/admin/dashboard/stats/reviews-chart')
        .then(response => response.json())
        .then(data => {
            if (data.success && window.reviewsChartChart) {
                const chartData = data.data;
                
                window.reviewsChartChart.data = {
                    labels: chartData.map(item => new Date(item.date).toLocaleDateString('vi-VN')),
                    datasets: [{
                        label: 'Số đánh giá',
                        data: chartData.map(item => item.count),
                        borderColor: '#FAC638',
                        backgroundColor: 'rgba(250, 198, 56, 0.1)',
                        tension: 0.4,
                        fill: true
                    }]
                };
                
                window.reviewsChartChart.update();
                hideChartLoading('reviewsChart');
            }
        })
        .catch(error => {
            console.error('Error loading reviews chart:', error);
            hideChartLoading('reviewsChart');
        });
}

// Load orders chart data
function loadOrdersChartData() {
    fetch('/admin/dashboard/stats/orders-chart')
        .then(response => response.json())
        .then(data => {
            if (data.success && window.ordersChartChart) {
                const chartData = data.data;
                
                window.ordersChartChart.data = {
                    labels: chartData.map(item => new Date(item.date).toLocaleDateString('vi-VN')),
                    datasets: [{
                        label: 'Số đơn hàng',
                        data: chartData.map(item => item.count),
                        backgroundColor: '#FAC638',
                        borderColor: '#F59E0B',
                        borderWidth: 1
                    }]
                };
                
                window.ordersChartChart.update();
                hideChartLoading('ordersChart');
            }
        })
        .catch(error => {
            console.error('Error loading orders chart:', error);
            hideChartLoading('ordersChart');
        });
}

function loadRatingDistribution() {
    fetch('/admin/dashboard/stats/rating-distribution')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const stats = data.data;
                
                // Update rating bars
                for (let i = 1; i <= 5; i++) {
                    const percentage = stats.percentages[i] || 0;
                    const count = stats.counts[i] || 0;
                    
                    document.getElementById(`rating-bar-${i}`).style.width = percentage + '%';
                    document.getElementById(`rating-count-${i}`).textContent = count;
                }
                
                // Update average rating
                document.getElementById('average-rating').textContent = stats.average + '/5';
            }
        })
        .catch(error => console.error('Error loading rating distribution:', error));
}

function loadTopProducts() {
    fetch('/admin/dashboard/stats/top-products')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const products = data.data;
                const container = document.getElementById('top-products');
                
                if (products.length === 0) {
                    container.innerHTML = '<p class="text-gray-500 text-center py-4">Chưa có dữ liệu</p>';
                    return;
                }
                
                let html = '';
                products.forEach((product, index) => {
                    html += `
                        <div class="flex items-center gap-3 p-2 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                            <div class="w-8 h-8 rounded-full bg-primary/10 text-primary flex items-center justify-center text-sm font-bold">
                                ${index + 1}
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-gray-900 dark:text-white truncate">${product.name}</p>
                                <p class="text-xs text-gray-500">${product.total_quantity} đã bán</p>
                            </div>
                            <div class="text-right">
                                <p class="text-sm font-semibold text-gray-900 dark:text-white">${formatCurrency(product.total_revenue)}</p>
                            </div>
                        </div>
                    `;
                });
                
                container.innerHTML = html;
            }
        })
        .catch(error => console.error('Error loading top products:', error));
}

function loadRecentActivity() {
    fetch('/admin/dashboard/stats/recent-activity')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const activities = data.data;
                const container = document.getElementById('recent-activity');
                
                if (activities.length === 0) {
                    container.innerHTML = '<p class="text-gray-500 text-center py-4">Chưa có hoạt động</p>';
                    return;
                }
                
                let html = '';
                activities.forEach(activity => {
                    const iconMap = {
                        'review': 'rate_review',
                        'order': 'shopping_cart',
                        'user': 'person_add'
                    };
                    
                    const colorMap = {
                        'review': 'text-blue-500',
                        'order': 'text-green-500',
                        'user': 'text-purple-500'
                    };
                    
                    html += `
                        <div class="flex items-start gap-3 p-2 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors cursor-pointer" onclick="window.location.href='${activity.url || '#'}'">
                            <div class="w-8 h-8 rounded-full bg-gray-100 dark:bg-gray-700 flex items-center justify-center">
                                <span class="material-icons-round text-sm ${colorMap[activity.type] || 'text-gray-500'}">${iconMap[activity.type] || 'info'}</span>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-gray-900 dark:text-white">${activity.title}</p>
                                <p class="text-xs text-gray-500 truncate">${activity.description}</p>
                                <p class="text-xs text-gray-400 mt-1">${new Date(activity.created_at).toLocaleString('vi-VN')}</p>
                            </div>
                        </div>
                    `;
                });
                
                container.innerHTML = html;
            }
        })
        .catch(error => console.error('Error loading recent activity:', error));
}
</script>
@endpush

@endsection