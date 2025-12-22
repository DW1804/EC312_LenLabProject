<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon; 
class DashboardController extends Controller
{
    public function index()
    {
        // Kiểm tra bảng tồn tại trước khi query
        $customerCount = \Schema::hasTable('users') ? DB::table('users')->count() : 0;
        $productCount = \Schema::hasTable('products') ? DB::table('products')->count() : 0;
        $orderCount = \Schema::hasTable('orders') ? DB::table('orders')->count() : 0;
        $pendingOrderCount = \Schema::hasTable('orders') ? DB::table('orders')->where('status', 'pending')->count() : 0;
        
        $recentOrders = [];
        if (\Schema::hasTable('orders') && \Schema::hasTable('users')) {
            try {
                $recentOrders = DB::table('orders')
                    ->join('users', 'orders.user_id', '=', 'users.id')
                    ->select('orders.*', 'users.name as full_name')
                    ->orderBy('orders.created_at', 'desc')
                    ->limit(5)
                    ->get();
            } catch (\Exception $e) {
                $recentOrders = [];
            }
        }
        // 1. THỐNG KÊ DOANH THU 7 NGÀY GẦN NHẤT
    // Lấy đơn hàng đã hoàn thành (giả sử trạng thái 'completed' hoặc 'delivered')
  $revenueData = DB::table('orders')
        ->select(
            DB::raw('DATE(created_at) as date'), 
            DB::raw('SUM(total_amount) as total')
        )
        ->where('status', 'delivered') // Quan trọng: Khớp với dữ liệu trong ảnh của bạn
        ->where('created_at', '>=', Carbon::now()->subDays(30)) // Lấy 30 ngày gần nhất
        ->groupBy('date')
        ->orderBy('date', 'ASC')
        ->get();

    // Chuẩn bị dữ liệu cho biểu đồ
    $dates = $revenueData->pluck('date')->toArray();
    $totals = $revenueData->pluck('total')->toArray();

    // 2. SẢN PHẨM BÁN CHẠY NHẤT (Top 5)
    // Logic mới: Join 3 bảng (order_items -> orders -> products) để lọc đơn hủy
    $topProducts = DB::table('order_items')
        ->join('orders', 'order_items.order_id', '=', 'orders.order_id') // Nối bảng đơn hàng
        ->join('products', 'order_items.product_id', '=', 'products.id') // Nối bảng sản phẩm để lấy tên
        ->where('orders.status', 'delivered') // CHỈ ĐẾM ĐƠN ĐÃ GIAO
        ->select(
            'products.name', 
            DB::raw('SUM(order_items.quantity) as total_sold')
        )
        ->groupBy('products.id', 'products.name')
        ->orderByDesc('total_sold')
        ->limit(5)
        ->get();

    // 3. CÁC THỐNG KÊ TỔNG QUAN KHÁC
    $totalOrders = DB::table('orders')->count();
    $totalProducts = DB::table('products')->count();
    $totalCustomers = DB::table('orders')->distinct('email')->count(); // Đếm khách dựa trên email
    
    // Đơn chờ xử lý (status = 'pending' như trong ảnh)
    $pendingOrders = DB::table('orders')->where('status', 'pending')->count();
    $deliveredOrders = DB::table('orders')->where('status', 'delivered')->count();
    $cancelledOrders = DB::table('orders')->where('status', 'cancelled')->count();
    
    $recentOrders = DB::table('orders')
        ->orderBy('created_at', 'desc') // Sắp xếp ngày tạo giảm dần
        ->limit(5) // Chỉ lấy 5 đơn
        ->get();
    return view('admin.dashboard_modern', compact(
        'dates', 
        'totals', 
        'topProducts', 
        'totalOrders', 
        'totalProducts', 
        'totalCustomers',
        'pendingOrders',
        'deliveredOrders',
        'cancelledOrders',
        'recentOrders'
    ));
    }
}
