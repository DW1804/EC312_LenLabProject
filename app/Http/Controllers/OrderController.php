<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    /**
     * Display orders list
     */
    public function index()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        try {
            // Get user's orders from database - simplified version
            $orders = \App\Models\Order::where('user_id', Auth::id())
                                      ->with('orderItems') // Load order items relationship
                                      ->orderBy('order_id', 'desc')
                                      ->get();

            return view('orders', compact('orders'));
        } catch (\Exception $e) {
            \Log::error('Error loading orders:', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return view('orders', ['orders' => collect()]);
        }
    }
    /**
     * Display order details
     */
    public function show($orderId)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        // Get order from database
        $order = \App\Models\Order::where('order_id', $orderId)
                                 ->where('user_id', Auth::id())
                                 ->with(['orderItems.product'])
                                 ->first();
        
        if (!$order) {
            return redirect()->route('orders.index')->with('error', 'Không tìm thấy đơn hàng');
        }

        // Format order data for view
        $orderData = [
            'id' => $order->order_id, // Sử dụng order_id làm mã đơn hàng
            'status' => $order->status,
            'status_text' => $order->status_text,
            'created_at' => date('d/m/Y'), // Sử dụng ngày hiện tại vì không có timestamps
            'items' => $order->orderItems->map(function($item) {
                return [
                    'product_id' => $item->product_id,
                    'name' => $item->product_name,
                    'variant' => $this->formatVariantInfo($item->variant_info),
                    'quantity' => $item->quantity,
                    'price' => $item->price,
                    'image' => $item->product_image ?? 'default.jpg' // Đảm bảo có fallback
                ];
            })->toArray(),
            'shipping_address' => [
                'name' => $order->full_name ?? '',
                'phone' => $order->phone ?? '',
                'address' => $this->formatAddressFromColumns($order)
            ],
            'payment' => [
                'method' => $this->formatPaymentMethod($order->payment_method),
                'subtotal' => $order->subtotal,
                'shipping' => $order->shipping_fee,
                'discount' => $order->discount_amount,
                'total' => $order->total_amount // Sử dụng total_amount thay vì total
            ]
        ];
        
        return view('order-detail', ['order' => $orderData]);
    }
    
    /**
     * Cancel order
     */
    public function cancel(Request $request, $orderId)
    {
        try {
            if (!Auth::check()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Vui lòng đăng nhập'
                ], 401);
            }

            // Validate cancel reason
            $request->validate([
                'cancel_reason' => 'required|string|max:500'
            ]);

            $order = \App\Models\Order::where('order_id', $orderId)
                                     ->where('user_id', Auth::id())
                                     ->first();
            
            if (!$order) {
                return response()->json([
                    'success' => false,
                    'message' => 'Không tìm thấy đơn hàng'
                ]);
            }
            
            if ($order->status !== 'processing') {
                return response()->json([
                    'success' => false,
                    'message' => 'Không thể hủy đơn hàng ở trạng thái này'
                ]);
            }
            
            // Update order status to cancelled and save cancel reason
            $order->update([
                'status' => 'cancelled',
                'order_note' => 'Lý do hủy: ' . $request->cancel_reason
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'Đã hủy đơn hàng thành công'
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Error cancelling order:', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi hủy đơn hàng'
            ], 500);
        }
    }

    /**
     * Format variant info for display
     */
    private function formatVariantInfo($variantInfo)
    {
        if (!$variantInfo) {
            return '';
        }

        if (is_string($variantInfo)) {
            $variantInfo = json_decode($variantInfo, true);
        }

        if (isset($variantInfo['variant_name'])) {
            return 'Loại: ' . $variantInfo['variant_name'];
        }

        return '';
    }

    /**
     * Format shipping address from order columns
     */
    private function formatAddressFromColumns($order)
    {
        $parts = [];
        
        if ($order->specific_address) {
            $parts[] = $order->specific_address;
        }
        
        if ($order->ward) {
            $parts[] = $order->ward;
        }
        
        if ($order->province) {
            $parts[] = $order->province;
        }
        
        return implode(', ', $parts);
    }

    /**
     * Format shipping address
     */
    private function formatAddress($address)
    {
        if (!$address) {
            return '';
        }

        $parts = [];
        
        if (isset($address['specific_address'])) {
            $parts[] = $address['specific_address'];
        }
        
        if (isset($address['ward_name'])) {
            $parts[] = $address['ward_name'];
        }
        
        if (isset($address['province_name'])) {
            $parts[] = $address['province_name'];
        }

        return implode(', ', $parts);
    }

    /**
     * Format payment method
     */
    private function formatPaymentMethod($method)
    {
        switch ($method) {
            case 'cod':
                return 'Thanh toán khi nhận hàng (COD)';
            case 'bank_transfer':
                return 'Chuyển khoản ngân hàng';
            case 'momo':
                return 'Ví MoMo';
            default:
                return 'Phương thức thanh toán: ' . $method;
        }
    }
    
    /**
     * Get mock order data
     */
    private function getMockOrder($orderId)
    {
        // Mock data - in real app, fetch from database
        $orders = [
            'ORD-2023-884' => [
                'id' => 'ORD-2023-884',
                'status' => 'processing', // processing, shipping, delivered, cancelled
                'status_text' => 'Đang xử lý',
                'created_at' => '18/12/2024',
                'items' => [
                    [
                        'name' => 'Khăn len Merino xám',
                        'variant' => 'Màu: Xám khói',
                        'quantity' => 1,
                        'price' => 450000,
                        'image' => 'product1.jpg'
                    ],
                    [
                        'name' => 'Mũ len Beanie',
                        'variant' => 'Màu: Be',
                        'quantity' => 1,
                        'price' => 200000,
                        'image' => 'product2.jpg'
                    ]
                ],
                'shipping_address' => [
                    'name' => 'Nguyễn Văn A',
                    'phone' => '(+84) 909 123 456',
                    'address' => '123 Đường Nguyễn Huệ, P. Bến Nghé, Quận 1, TP. Hồ Chí Minh'
                ],
                'payment' => [
                    'method' => 'Thẻ tín dụng (Visa ****2742)',
                    'subtotal' => 650000,
                    'shipping' => 30000,
                    'discount' => 0,
                    'total' => 680000
                ]
            ],
            'ORD-2023-885' => [
                'id' => 'ORD-2023-885',
                'status' => 'shipping',
                'status_text' => 'Đang giao hàng',
                'created_at' => '17/12/2024',
                'items' => [
                    [
                        'name' => 'Khăn len Merino xám',
                        'variant' => 'Màu: Xám khói',
                        'quantity' => 1,
                        'price' => 450000,
                        'image' => 'product1.jpg'
                    ]
                ],
                'shipping_address' => [
                    'name' => 'Nguyễn Văn A',
                    'phone' => '(+84) 909 123 456',
                    'address' => '123 Đường Nguyễn Huệ, P. Bến Nghé, Quận 1, TP. Hồ Chí Minh'
                ],
                'payment' => [
                    'method' => 'Thẻ tín dụng (Visa ****2742)',
                    'subtotal' => 450000,
                    'shipping' => 30000,
                    'discount' => 0,
                    'total' => 480000
                ]
            ]
        ];
        
        return $orders[$orderId] ?? null;
    }
    
    /**
     * Get status color class
     */
    public static function getStatusColor($status)
    {
        switch ($status) {
            case 'processing':
                return 'text-yellow-400';
            case 'shipping':
                return 'text-blue-400';
            case 'delivered':
                return 'text-green-400';
            case 'cancelled':
                return 'text-red-400';
            default:
                return 'text-gray-400';
        }
    }
    
    /**
     * Get status icon
     */
    public static function getStatusIcon($status)
    {
        switch ($status) {
            case 'processing':
                return 'schedule';
            case 'shipping':
                return 'local_shipping';
            case 'delivered':
                return 'check_circle';
            case 'cancelled':
                return 'cancel';
            default:
                return 'help';
        }
    }
}