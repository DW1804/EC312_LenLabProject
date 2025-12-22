<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Province;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CheckoutController extends Controller
{
    /**
     * Get shipping fee and delivery time based on address
     */
    private function getShippingInfo($address)
    {
        $provinceName = $address['province_name'] ?? '';
        
        // Nếu chưa có province_name, thử lấy từ province_id
        if (empty($provinceName) && isset($address['province_id'])) {
            try {
                $province = Province::find($address['province_id']);
                if ($province) {
                    $provinceName = $province->name;
                    // Cập nhật lại session để lần sau không cần query
                    $address['province_name'] = $provinceName;
                    session(['checkout_address' => $address]);
                }
            } catch (\Exception $e) {
                \Log::error('Error loading province name:', ['error' => $e->getMessage()]);
            }
        }
        
        return [
            'shipping_fee' => \App\Helpers\ShippingHelper::calculateShippingFee($provinceName),
            'delivery_time' => \App\Helpers\ShippingHelper::calculateDeliveryTime($provinceName),
            'province_name' => $provinceName
        ];
    }

    // Hiển thị trang checkout
    public function index()
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Vui lòng đăng nhập để tiếp tục');
        }

        // Kiểm tra giỏ hàng có sản phẩm không
        $cartItems = Cart::where('user_id', Auth::id())->with('product')->get();
        if ($cartItems->isEmpty()) {
            return redirect()->route('cart')->with('error', 'Giỏ hàng trống');
        }

        return view('checkout');
        
    }

    // Hiển thị view order detail
    public function showOrderDetail($code)
    {
        return view('order_detail', ['orderCode' => $code]);
    }

    // Tóm tắt giỏ hàng cho trang checkout
    public function summary()
    {
        $userId = Auth::id();
        $cartItems = Cart::where('user_id', $userId)
            ->with('product')
            ->get();

        $subtotal = $cartItems->sum(function ($item) {
            $price = $item->price_at_time ?? optional($item->product)->price ?? 0;
            return $price * $item->quantity;
        });

        return response()->json([
            'cart_items' => $cartItems,
            'subtotal'   => $subtotal,
        ]);
    }

    // Hàm generate mã đơn hàng kiểu C: LL-XXXXXXXX
    protected function generateOrderCode(): string
    {
        return 'LL-' . strtoupper(Str::random(8));
    }

    // Hiển thị trang thanh toán
    public function payment()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        // Kiểm tra có địa chỉ trong session không
        $address = session('checkout_address');
        if (!$address) {
            return redirect()->route('checkout')->with('error', 'Vui lòng chọn địa chỉ giao hàng');
        }

        // Lấy thông tin tỉnh và xã/phường nếu có province_id và ward_id
        if (isset($address['province_id']) && isset($address['ward_id'])) {
            try {
                $province = \App\Models\Province::find($address['province_id']);
                $ward = \App\Models\Ward::find($address['ward_id']);
                
                if ($province) {
                    $address['province_name'] = $province->name;
                    // Update session with province name
                    session(['checkout_address' => array_merge(session('checkout_address', []), ['province_name' => $province->name])]);
                }
                if ($ward) {
                    $address['ward_name'] = $ward->name;
                    // Update session with ward name
                    session(['checkout_address' => array_merge(session('checkout_address', []), ['ward_name' => $ward->name])]);
                }
            } catch (\Exception $e) {
                \Log::error('Error loading province/ward names:', ['error' => $e->getMessage()]);
            }
        }

        // Lấy thông tin giỏ hàng
        $selectedItemIds = session('checkout_selected_items', []);
        if (empty($selectedItemIds)) {
            return redirect()->route('cart')->with('error', 'Vui lòng chọn sản phẩm để thanh toán');
        }

        $cartItems = Cart::whereIn('id', $selectedItemIds)
            ->where('user_id', Auth::id())
            ->with('product')
            ->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart')->with('error', 'Không tìm thấy sản phẩm');
        }

        $subtotal = 0;
        foreach ($cartItems as $item) {
            $itemPrice = $item->price_at_time ?? $item->product->price ?? 0;
            $subtotal += $itemPrice * $item->quantity;
        }

        // Tính phí vận chuyển theo khu vực
        $shippingInfo = $this->getShippingInfo($address);
        $shippingFee = $shippingInfo['shipping_fee'];
        $deliveryTime = $shippingInfo['delivery_time'];

        // Lấy thông tin voucher từ session
        $voucherCode = session('voucher_code');
        $voucherDiscount = session('voucher_discount', 0);

        return view('checkout-payment', compact('address', 'cartItems', 'subtotal', 'shippingFee', 'deliveryTime', 'voucherCode', 'voucherDiscount'));
    }

    // Set selected items for checkout
    public function setSelectedItems(Request $request)
    {
        try {
            if (!Auth::check()) {
                return response()->json(['success' => false, 'message' => 'Vui lòng đăng nhập'], 401);
            }

            $request->validate([
                'selected_items' => 'required|array',
                'selected_items.*' => 'integer|exists:cart,id'
            ]);

            // Lưu selected items vào session
            session(['checkout_selected_items' => $request->selected_items]);

            // Xóa voucher cũ khi thay đổi selected items
            session()->forget([
                'voucher_code',
                'applied_voucher',
                'voucher_discount',
                'voucher_discount_type',
                'voucher_discount_value'
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Đã lưu sản phẩm được chọn'
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Dữ liệu không hợp lệ',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            \Log::error('Error in setSelectedItems:', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra: ' . $e->getMessage()
            ], 500);
        }
    }

    // Set address for checkout
    public function setAddress(Request $request)
    {
        try {
            if (!Auth::check()) {
                return response()->json(['success' => false, 'message' => 'Vui lòng đăng nhập'], 401);
            }

            $request->validate([
                'full_name' => 'required|string|max:255',
                'phone' => 'required|string|max:20',
                'province_id' => 'required|integer',
                'ward_id' => 'required|integer',
                'specific_address' => 'required|string|max:500'
            ]);

            // Lấy tên province và ward từ database
            $address = $request->all();
            
            try {
                $province = \App\Models\Province::find($request->province_id);
                $ward = \App\Models\Ward::find($request->ward_id);
                
                if ($province) {
                    $address['province_name'] = $province->name;
                }
                if ($ward) {
                    $address['ward_name'] = $ward->name;
                }
                
                \Log::info('Address data prepared', [
                    'province_id' => $request->province_id,
                    'ward_id' => $request->ward_id,
                    'province_name' => $address['province_name'] ?? 'NULL',
                    'ward_name' => $address['ward_name'] ?? 'NULL'
                ]);
                
            } catch (\Exception $e) {
                \Log::error('Error loading province/ward names:', ['error' => $e->getMessage()]);
            }

            // Lưu address vào session
            session(['checkout_address' => $address]);

            \Log::info('Address saved to session', [
                'province_name' => $address['province_name'] ?? 'NULL',
                'ward_name' => $address['ward_name'] ?? 'NULL',
                'session_data' => session('checkout_address')
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Đã lưu địa chỉ giao hàng'
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Dữ liệu không hợp lệ',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            \Log::error('Error in setAddress:', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra: ' . $e->getMessage()
            ], 500);
        }
    }

    // Set note for checkout
    public function setNote(Request $request)
    {
        try {
            if (!Auth::check()) {
                return response()->json(['success' => false, 'message' => 'Vui lòng đăng nhập'], 401);
            }

            $request->validate([
                'note' => 'nullable|string|max:500'
            ]);

            // Lưu note vào session
            session(['checkout_note' => $request->note]);

            return response()->json([
                'success' => true,
                'message' => 'Đã lưu ghi chú'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra: ' . $e->getMessage()
            ], 500);
        }
    }

    // Set payment method for checkout
    public function setPaymentMethod(Request $request)
    {
        try {
            if (!Auth::check()) {
                return response()->json(['success' => false, 'message' => 'Vui lòng đăng nhập'], 401);
            }

            $request->validate([
                'payment_method' => 'required|string|in:cod,bank_transfer,momo'
            ]);

            // Lưu payment method vào session
            session(['checkout_payment_method' => $request->payment_method]);

            return response()->json([
                'success' => true,
                'message' => 'Đã lưu phương thức thanh toán'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra: ' . $e->getMessage()
            ], 500);
        }
    }

    // Confirm checkout page
    public function confirm()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        // Lấy selected items từ session
        $selectedItemIds = session('checkout_selected_items', []);
        if (empty($selectedItemIds)) {
            return redirect()->route('cart')->with('error', 'Vui lòng chọn sản phẩm để thanh toán');
        }

        // Lấy cart items
        $cartItems = Cart::whereIn('id', $selectedItemIds)
            ->where('user_id', Auth::id())
            ->with('product')
            ->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart')->with('error', 'Không tìm thấy sản phẩm');
        }

        // Tính tổng tiền
        $subtotal = 0;
        foreach ($cartItems as $item) {
            $itemPrice = $item->price_at_time ?? $item->product->price ?? 0;
            $subtotal += $itemPrice * $item->quantity;
        }

        // Lấy thông tin voucher từ session
        $voucherCode = session('voucher_code');
        $voucherDiscount = session('voucher_discount', 0);

        // Lấy địa chỉ từ session
        $address = session('checkout_address');

        // Tính phí ship theo khu vực
        $shippingFee = 30000; // Default
        $deliveryTime = null;
        
        if ($address) {
            $shippingInfo = $this->getShippingInfo($address);
            $shippingFee = $shippingInfo['shipping_fee'];
            $deliveryTime = $shippingInfo['delivery_time'];
        }

        return view('checkout-confirm', compact('cartItems', 'subtotal', 'voucherCode', 'voucherDiscount', 'address', 'shippingFee', 'deliveryTime'));
    }

    // Bank transfer page
    public function bankTransfer(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        // Get order code from query parameter or session
        $orderCode = $request->query('order_code') ?? session('temp_order_code');
        $queryTotal = $request->query('total');
        
        // Lấy thông tin từ session
        $selectedItemIds = session('checkout_selected_items', []);
        if (empty($selectedItemIds)) {
            return redirect()->route('cart')->with('error', 'Vui lòng chọn sản phẩm để thanh toán');
        }

        $cartItems = Cart::whereIn('id', $selectedItemIds)
            ->where('user_id', Auth::id())
            ->with('product')
            ->get();

        $subtotal = 0;
        foreach ($cartItems as $item) {
            $itemPrice = $item->price_at_time ?? $item->product->price ?? 0;
            $subtotal += $itemPrice * $item->quantity;
        }

        // Tính phí ship theo khu vực
        $address = session('checkout_address');
        $provinceName = $address['province_name'] ?? '';
        $shippingFee = \App\Helpers\ShippingHelper::calculateShippingFee($provinceName);
        
        $voucherDiscount = session('voucher_discount', 0);
        $discountAmount = $voucherDiscount; // Use discountAmount as expected by view
        
        // Use query total if provided, otherwise calculate
        $total = $queryTotal ? floatval($queryTotal) : ($subtotal - $voucherDiscount + $shippingFee);
        
        // Generate order code if not provided
        if (!$orderCode) {
            $orderCode = 'LL' . date('Ymd') . rand(1000, 9999);
            session(['temp_order_code' => $orderCode]);
        }

        return view('checkout-bank-transfer', compact('orderCode', 'subtotal', 'discountAmount', 'shippingFee', 'total'));
    }

    // Order success page
    public function orderSuccess(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        // Lấy order code từ query parameter hoặc session
        $orderCode = $request->query('order_code') ?? session('last_order_id');
        
        if (!$orderCode) {
            return redirect()->route('cart')->with('error', 'Không tìm thấy thông tin đơn hàng');
        }

        // Lấy thông tin đơn hàng từ database
        $order = \App\Models\Order::where('order_id', $orderCode)
                                  ->where('user_id', Auth::id())
                                  ->first();

        if (!$order) {
            // Fallback to session data if order not found in DB
            $orderCode = session('last_order_id', $orderCode);
            $total = session('last_order_total', 0);
            $paymentMethod = session('last_order_payment_method', 'cod');
            $estimatedDelivery = session('last_order_delivery_time', '2-3 ngày');
        } else {
            $total = $order->total_amount;
            $paymentMethod = $order->payment_method;
            
            // Tính thời gian giao hàng dự kiến
            $estimatedDelivery = \App\Helpers\ShippingHelper::calculateDeliveryTime($order->province);
        }

        // Xóa session data sau khi đã hiển thị
        session()->forget([
            'last_order_id',
            'last_order_total', 
            'last_order_payment_method',
            'last_order_delivery_time'
        ]);

        return view('order-success', compact('orderCode', 'total', 'paymentMethod', 'estimatedDelivery'));
    }

    // Prepare bank transfer
    public function prepareBankTransfer(Request $request)
    {
        try {
            if (!Auth::check()) {
                return response()->json(['success' => false, 'message' => 'Vui lòng đăng nhập'], 401);
            }

            $request->validate([
                'payment_method' => 'required|string|in:bank_transfer'
            ]);

            // Lưu payment method vào session
            session(['checkout_payment_method' => $request->payment_method]);

            // Tạo mã đơn hàng và lưu vào session để dùng lại
            $orderCode = 'LL' . date('Ymd') . rand(1000, 9999);
            session(['temp_order_code' => $orderCode]);

            // Tính tổng tiền
            $selectedItemIds = session('checkout_selected_items', []);
            $cartItems = Cart::whereIn('id', $selectedItemIds)
                ->where('user_id', Auth::id())
                ->with('product')
                ->get();

            $subtotal = 0;
            foreach ($cartItems as $item) {
                $itemPrice = $item->price_at_time ?? $item->product->price ?? 0;
                $subtotal += $itemPrice * $item->quantity;
            }

            // Tính phí ship theo khu vực
            $address = session('checkout_address');
            $provinceName = $address['province_name'] ?? '';
            $shippingFee = \App\Helpers\ShippingHelper::calculateShippingFee($provinceName);
            
            $voucherDiscount = session('voucher_discount', 0);
            $total = $subtotal + $shippingFee - $voucherDiscount;

            return response()->json([
                'success' => true,
                'order_code' => $orderCode,
                'total' => $total
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra: ' . $e->getMessage()
            ], 500);
        }
    }

    // Complete bank transfer
    public function completeBankTransfer(Request $request)
    {
        try {
            if (!Auth::check()) {
                return response()->json(['success' => false, 'message' => 'Vui lòng đăng nhập'], 401);
            }

            // Validate transfer image
            $request->validate([
                'transfer_image' => 'required|image|mimes:jpeg,png,jpg|max:5120', // Max 5MB
                'order_code' => 'nullable|string'
            ]);

            // Handle image upload
            $transferImagePath = null;
            if ($request->hasFile('transfer_image')) {
                $image = $request->file('transfer_image');
                $imageName = 'transfer_' . time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
                $image->move(public_path('transfer-images'), $imageName);
                $transferImagePath = $imageName;
            }

            // Tạo đơn hàng thực tế
            $orderCode = $this->createOrderFromSession();

            // Cập nhật transfer image vào order
            if ($transferImagePath) {
                $order = \App\Models\Order::where('order_id', $orderCode)->first();
                if ($order) {
                    $order->transfer_image = $transferImagePath;
                    $order->save();
                }
            }

            return response()->json([
                'success' => true,
                'order_code' => $orderCode,
                'redirect_url' => '/order-success?order_code=' . $orderCode
            ]);

        } catch (\Exception $e) {
            \Log::error('Error completing bank transfer:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra: ' . $e->getMessage()
            ], 500);
        }
    }

    // Create order from session data
    public function createOrder(Request $request)
    {
        try {
            if (!Auth::check()) {
                return response()->json(['success' => false, 'message' => 'Vui lòng đăng nhập'], 401);
            }

            $request->validate([
                'payment_method' => 'required|string|in:cod,bank_transfer'
            ]);

            // Lưu payment method
            session(['checkout_payment_method' => $request->payment_method]);

            // Tạo order code và lưu vào session để dùng lại
            $orderCode = 'LL' . date('Ymd') . rand(1000, 9999);
            session(['temp_order_code' => $orderCode]);

            // Tạo đơn hàng
            $finalOrderCode = $this->createOrderFromSession();

            return response()->json([
                'success' => true,
                'order_code' => $finalOrderCode,
                'redirect_url' => '/order-success?order_code=' . $finalOrderCode
            ]);

        } catch (\Exception $e) {
            \Log::error('Error creating order:', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra: ' . $e->getMessage()
            ], 500);
        }
    }

    // Helper method to create order from session
    private function createOrderFromSession()
    {
        $userId = Auth::id();
        $address = session('checkout_address');
        $selectedItemIds = session('checkout_selected_items', []);
        $paymentMethod = session('checkout_payment_method', 'cod');
        $note = session('checkout_note', '');

        if (!$address || empty($selectedItemIds)) {
            throw new \Exception('Thiếu thông tin đơn hàng');
        }

        // Lấy cart items
        $cartItems = Cart::whereIn('id', $selectedItemIds)
            ->where('user_id', $userId)
            ->with('product')
            ->get();

        if ($cartItems->isEmpty()) {
            throw new \Exception('Không tìm thấy sản phẩm');
        }

        // Tính toán
        $subtotal = 0;
        foreach ($cartItems as $item) {
            $itemPrice = $item->price_at_time ?? $item->product->price ?? 0;
            $subtotal += $itemPrice * $item->quantity;
        }

        // Tính phí ship theo khu vực
        $provinceName = $address['province_name'] ?? '';
        $shippingFee = \App\Helpers\ShippingHelper::calculateShippingFee($provinceName);
        
        $voucherDiscount = session('voucher_discount', 0);
        $total = $subtotal + $shippingFee - $voucherDiscount;

        \Log::info('Order calculation', [
            'subtotal' => $subtotal,
            'shipping_fee' => $shippingFee,
            'voucher_discount' => $voucherDiscount,
            'total' => $total
        ]);

        // Sử dụng order code từ session nếu có, nếu không tạo mới
        $orderCode = session('temp_order_code');
        if (!$orderCode) {
            $orderCode = 'LL' . date('Ymd') . rand(1000, 9999);
        }

        // Tạo đơn hàng trong database
        $order = \App\Models\Order::create([
            'order_id' => $orderCode,
            'user_id' => $userId,
            'status' => 'processing',
            'payment_method' => $paymentMethod,
            'payment_status' => $paymentMethod === 'cod' ? 'pending' : 'pending',
            'subtotal' => $subtotal,
            'shipping_fee' => $shippingFee,
            'discount_amount' => $voucherDiscount,
            'total_amount' => $total,
            'order_note' => $note,
            'email' => Auth::user()->email ?? '',
            'full_name' => $address['full_name'] ?? '',
            'phone' => $address['phone'] ?? '',
            'province' => $address['province_name'] ?? '',
            'ward' => $address['ward_name'] ?? '',
            'specific_address' => $address['specific_address'] ?? ''
        ]);

        \Log::info('Order created', [
            'order_id' => $order->order_id,
            'ward' => $address['ward_name'] ?? 'NULL',
            'province' => $address['province_name'] ?? 'NULL',
            'address_data' => $address
        ]);

        // Lưu order items
        foreach ($cartItems as $item) {
            $variantId = $item->variant_id;
            $variantInfo = $item->variant_info;
            
            \Log::info('Creating order item', [
                'product_id' => $item->product_id,
                'variant_id' => $variantId ?? 'NULL',
                'variant_info' => $variantInfo,
                'cart_item_data' => [
                    'id' => $item->id,
                    'variant_id_raw' => $item->getAttributes()['variant_id'] ?? 'NOT_SET',
                    'variant_info_raw' => $item->getAttributes()['variant_info'] ?? 'NOT_SET'
                ]
            ]);

            $orderItem = \App\Models\OrderItem::create([
                'order_id' => $order->order_id,
                'product_id' => $item->product_id,
                'product_name' => $item->product->name ?? '',
                'product_image' => $item->product->image ?? '',
                'variant_id' => $variantId,
                'variant_info' => $variantInfo,
                'quantity' => $item->quantity,
                'price' => $item->price_at_time ?? $item->product->price,
                'total' => ($item->price_at_time ?? $item->product->price) * $item->quantity
            ]);
            
            \Log::info('Order item created', [
                'order_item_id' => $orderItem->id,
                'saved_variant_id' => $orderItem->variant_id,
                'saved_variant_info' => $orderItem->variant_info
            ]);
        }

        // Lưu thông tin đơn hàng vào session để hiển thị trang success
        session([
            'last_order_id' => $order->order_id,
            'last_order_total' => $total,
            'last_order_payment_method' => $paymentMethod,
            'last_order_delivery_time' => \App\Helpers\ShippingHelper::calculateDeliveryTime($provinceName)
        ]);

        // Xóa cart items đã checkout
        Cart::whereIn('id', $selectedItemIds)->where('user_id', $userId)->delete();

        // Xóa session checkout
        session()->forget([
            'checkout_address',
            'checkout_selected_items', 
            'checkout_note',
            'checkout_payment_method',
            'voucher_code',
            'voucher_discount',
            'applied_voucher',
            'voucher_discount_type',
            'voucher_discount_value',
            'temp_order_code'
        ]);

        return $order->order_id;
    }

    // Hoàn tất đơn hàng (chọn phương thức thanh toán ở Order Detail)
    public function completeOrder(Request $request)
    {
        try {
            if (!Auth::check()) {
                return response()->json(['success' => false, 'message' => 'Vui lòng đăng nhập'], 401);
            }

            $address = session('checkout_address');
            if (!$address) {
                return response()->json(['success' => false, 'message' => 'Không tìm thấy địa chỉ giao hàng'], 400);
            }

            $validated = $request->validate([
                'payment_method' => 'required|string|in:cod,bank_transfer,momo',
                'note' => 'nullable|string|max:500'
            ]);

            // Lấy giỏ hàng
            $cartItems = Cart::where('user_id', Auth::id())->with('product')->get();
            if ($cartItems->isEmpty()) {
                return response()->json(['success' => false, 'message' => 'Giỏ hàng trống'], 400);
            }

            // Tính tổng tiền
            $subtotal = 0;
            foreach ($cartItems as $item) {
                $itemPrice = $item->price_at_time ?? $item->product->price ?? 0;
                $subtotal += $itemPrice * $item->quantity;
            }
            
            $shippingFee = 30000; // Phí ship cố định
            $total = $subtotal + $shippingFee;

            // Tạo mã đơn hàng
            $orderCode = 'LL' . date('Ymd') . rand(1000, 9999);

            // Tạo đơn hàng (giả sử có model Order)
            // Order::create([...]);

            // Xóa giỏ hàng
            Cart::where('user_id', Auth::id())->delete();

            // Xóa session
            session()->forget(['checkout_address', 'checkout_selected_items', 'checkout_note', 'checkout_payment_method', 'voucher_code', 'voucher_discount']);

            return response()->json([
                'success' => true,
                'order_code' => $orderCode,
                'message' => 'Đặt hàng thành công'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra: ' . $e->getMessage()
            ], 500);
        }
    }
}