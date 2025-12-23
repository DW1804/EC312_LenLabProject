<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Review;
use App\Models\Product;
use App\Models\DigitalProduct;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardStatsController extends Controller
{
    public function overview()
    {
        $stats = [
            // Basic counts
            'total_users' => User::count(),
            'total_products' => Product::count(),
            'total_digital_products' => DigitalProduct::count(),
            'total_orders' => Order::count(),
            'total_reviews' => Review::count(),
            
            // Recent activity
            'pending_reviews' => Review::pending()->count(),
            'recent_orders' => Order::where('created_at', '>=', now()->subDays(7))->count(),
            'new_users_this_week' => User::where('created_at', '>=', now()->subDays(7))->count(),
            
            // Revenue stats
            'total_revenue' => Order::where('payment_status', 'completed')->sum('total_amount'),
            'revenue_this_month' => Order::where('payment_status', 'completed')
                ->whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->sum('total_amount'),
            
            // Review stats
            'average_rating' => Review::approved()->avg('rating'),
            'reviews_with_images' => Review::withImages()->count(),
            
            // Product stats
            'active_products' => Product::where('is_active', true)->count(),
            'active_digital_products' => DigitalProduct::where('is_active', true)->count(),
        ];

        return response()->json([
            'success' => true,
            'stats' => $stats
        ]);
    }

    public function reviewsChart()
    {
        // Get reviews data for the last 30 days
        $reviewsData = Review::select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('COUNT(*) as count'),
                DB::raw('AVG(rating) as avg_rating')
            )
            ->where('created_at', '>=', now()->subDays(30))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Fill missing dates with 0
        $chartData = [];
        for ($i = 29; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $dayData = $reviewsData->firstWhere('date', $date);
            
            $chartData[] = [
                'date' => $date,
                'count' => $dayData ? $dayData->count : 0,
                'avg_rating' => $dayData ? round($dayData->avg_rating, 1) : 0
            ];
        }

        return response()->json([
            'success' => true,
            'data' => $chartData
        ]);
    }

    public function ordersChart()
    {
        // Get orders data for the last 30 days
        $ordersData = Order::select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('COUNT(*) as count'),
                DB::raw('SUM(total_amount) as revenue')
            )
            ->where('created_at', '>=', now()->subDays(30))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Fill missing dates with 0
        $chartData = [];
        for ($i = 29; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $dayData = $ordersData->firstWhere('date', $date);
            
            $chartData[] = [
                'date' => $date,
                'count' => $dayData ? $dayData->count : 0,
                'revenue' => $dayData ? $dayData->revenue : 0
            ];
        }

        return response()->json([
            'success' => true,
            'data' => $chartData
        ]);
    }

    public function topProducts()
    {
        // Top products by order count
        $topProducts = DB::table('order_items')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->select(
                'products.id',
                'products.name',
                'products.image',
                DB::raw('COUNT(*) as order_count'),
                DB::raw('SUM(order_items.quantity) as total_quantity'),
                DB::raw('SUM(order_items.total) as total_revenue')
            )
            ->groupBy('products.id', 'products.name', 'products.image')
            ->orderBy('order_count', 'desc')
            ->limit(5)
            ->get();

        return response()->json([
            'success' => true,
            'data' => $topProducts
        ]);
    }

    public function recentActivity()
    {
        $activities = collect();

        // Recent reviews
        $recentReviews = Review::with(['user', 'product'])
            ->latest()
            ->limit(5)
            ->get()
            ->map(function ($review) {
                return [
                    'type' => 'review',
                    'title' => $review->user->name . ' đã đánh giá sản phẩm',
                    'description' => $review->product->name,
                    'rating' => $review->rating,
                    'status' => $review->status,
                    'created_at' => $review->created_at,
                    'url' => '/admin/reviews?search=' . $review->product->name
                ];
            });

        // Recent orders
        $recentOrders = Order::with('user')
            ->latest()
            ->limit(5)
            ->get()
            ->map(function ($order) {
                return [
                    'type' => 'order',
                    'title' => 'Đơn hàng mới từ ' . ($order->user->name ?? $order->full_name),
                    'description' => 'Mã: ' . $order->order_id . ' - ' . number_format($order->total_amount) . 'đ',
                    'status' => $order->status,
                    'created_at' => $order->created_at,
                    'url' => '/admin/orders/' . $order->order_id
                ];
            });

        // Recent users
        $recentUsers = User::latest()
            ->limit(3)
            ->get()
            ->map(function ($user) {
                return [
                    'type' => 'user',
                    'title' => 'Người dùng mới đăng ký',
                    'description' => $user->name . ' (' . $user->email . ')',
                    'created_at' => $user->created_at,
                    'url' => '/admin/users/' . $user->id
                ];
            });

        // Merge and sort by created_at
        $activities = $activities
            ->merge($recentReviews)
            ->merge($recentOrders)
            ->merge($recentUsers)
            ->sortByDesc('created_at')
            ->take(10)
            ->values();

        return response()->json([
            'success' => true,
            'data' => $activities
        ]);
    }

    public function ratingDistribution()
    {
        $distribution = [];
        for ($i = 1; $i <= 5; $i++) {
            $distribution[$i] = Review::approved()->where('rating', $i)->count();
        }

        $total = array_sum($distribution);
        $percentages = [];
        foreach ($distribution as $rating => $count) {
            $percentages[$rating] = $total > 0 ? round(($count / $total) * 100, 1) : 0;
        }

        return response()->json([
            'success' => true,
            'data' => [
                'counts' => $distribution,
                'percentages' => $percentages,
                'total' => $total,
                'average' => $total > 0 ? round(Review::approved()->avg('rating'), 1) : 0
            ]
        ]);
    }
}