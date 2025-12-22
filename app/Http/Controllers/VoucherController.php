<?php

namespace App\Http\Controllers;

use App\Models\Voucher;
use Illuminate\Http\Request;
use Carbon\Carbon;

class VoucherController extends Controller
{
    /**
     * Display vouchers page
     */
    public function index()
    {
        // Get only active vouchers that haven't expired
        $vouchers = Voucher::where('active', true)
                          ->where('end_date', '>', Carbon::now())
                          ->orderBy('start_date', 'desc')
                          ->get();

        return view('vouchers', compact('vouchers'));
    }

    /**
     * Get vouchers via API (for AJAX requests)
     */
    public function getVouchers()
    {
        try {
            $now = Carbon::now();
            
            $vouchers = Voucher::where('active', true)
                              ->where(function($query) use ($now) {
                                  $query->whereNull('end_date')
                                        ->orWhere('end_date', '>', $now);
                              })
                              ->where(function($query) use ($now) {
                                  $query->whereNull('start_date')
                                        ->orWhere('start_date', '<=', $now);
                              })
                              ->orderBy('discount_value', 'desc')
                              ->get();

            return response()->json([
                'success' => true,
                'vouchers' => $vouchers
            ]);
        } catch (\Exception $e) {
            \Log::error('Error loading vouchers:', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Không thể tải danh sách voucher'
            ], 500);
        }
    }

    /**
     * Apply voucher to cart
     */
    public function applyVoucher(Request $request)
    {
        try {
            $validated = $request->validate([
                'voucher_code' => 'required|string',
                'cart_total' => 'nullable|numeric'
            ]);

            \Log::info('Applying voucher', [
                'voucher_code' => $validated['voucher_code'],
                'cart_total' => $validated['cart_total'] ?? 'not provided'
            ]);

            $now = Carbon::now();
            
            $voucher = Voucher::where('code', $validated['voucher_code'])
                             ->where('active', true)
                             ->where(function($query) use ($now) {
                                 $query->whereNull('start_date')
                                       ->orWhere('start_date', '<=', $now);
                             })
                             ->where(function($query) use ($now) {
                                 $query->whereNull('end_date')
                                       ->orWhere('end_date', '>', $now);
                             })
                             ->first();

            if (!$voucher) {
                \Log::warning('Voucher not found or expired', [
                    'code' => $validated['voucher_code'],
                    'now' => Carbon::now()->format('Y-m-d H:i:s')
                ]);
                
                return response()->json([
                    'success' => false,
                    'message' => 'Mã voucher không hợp lệ hoặc đã hết hạn'
                ]);
            }

            // Check if we're in checkout flow (has selected items) or cart page (use cart_total)
            $selectedItemIds = session('checkout_selected_items', []);
            $selectedSubtotal = 0;
            
            if (!empty($selectedItemIds)) {
                // Checkout flow - use selected items from session
                $selectedCartItems = \App\Models\Cart::whereIn('id', $selectedItemIds)
                    ->where('user_id', auth()->id())
                    ->with('product')
                    ->get();

                // Calculate subtotal of selected items only - ensure we get the exact amount
                foreach ($selectedCartItems as $item) {
                    $itemPrice = $item->price_at_time ?? $item->product->price ?? 0;
                    $selectedSubtotal += $itemPrice * $item->quantity;
                }
                
                \Log::info('Checkout voucher calculation', [
                    'selected_items_count' => $selectedCartItems->count(),
                    'selected_subtotal' => $selectedSubtotal,
                    'voucher_min_order' => $voucher->min_order_value
                ]);
            } else {
                // Cart page flow - use provided cart_total
                $selectedSubtotal = $validated['cart_total'] ?? 0;
                
                \Log::info('Cart voucher calculation', [
                    'cart_total' => $selectedSubtotal,
                    'voucher_min_order' => $voucher->min_order_value
                ]);
            }

            // Check minimum order value
            if ($voucher->min_order_value && $selectedSubtotal < $voucher->min_order_value) {
                return response()->json([
                    'success' => false,
                    'message' => 'Đơn hàng tối thiểu ' . number_format($voucher->min_order_value) . 'đ để sử dụng voucher này (hiện tại: ' . number_format($selectedSubtotal) . 'đ)'
                ]);
            }

            // Store voucher in session with consistent keys
            session([
                'voucher_code' => $voucher->code,
                'applied_voucher' => $voucher->toArray()
            ]);
            
            // Calculate discount amount based on selected items total
            $discountAmount = 0;
            if ($voucher->type === 'percent' || $voucher->type === 'percentage') {
                // Tính phần trăm dựa trên tổng tiền sản phẩm được chọn
                $discountAmount = round(($selectedSubtotal * $voucher->discount_value) / 100);
            } else if ($voucher->type === 'fixed' || $voucher->type === 'fixed_amount') {
                // Giảm giá cố định
                $discountAmount = $voucher->discount_value;
                // Không được vượt quá tổng tiền sản phẩm
                if ($discountAmount > $selectedSubtotal) {
                    $discountAmount = $selectedSubtotal;
                }
            } else {
                $discountAmount = $voucher->discount_value;
            }

            // Lưu thông tin voucher vào session
            session([
                'voucher_discount' => $discountAmount,
                'voucher_discount_type' => $voucher->type,
                'voucher_discount_value' => $voucher->discount_value
            ]);

            \Log::info('Voucher applied successfully', [
                'voucher_code' => $voucher->code,
                'type' => $voucher->type,
                'discount_value' => $voucher->discount_value,
                'selected_subtotal' => $selectedSubtotal,
                'calculated_discount' => $discountAmount
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Áp dụng voucher thành công',
                'voucher' => $voucher,
                'discount_amount' => $discountAmount,
                'selected_subtotal' => $selectedSubtotal
            ]);

        } catch (\Exception $e) {
            \Log::error('Error applying voucher', [
                'error' => $e->getMessage(),
                'request' => $request->all()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi áp dụng voucher: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove applied voucher
     */
    public function removeVoucher()
    {
        session()->forget([
            'voucher_code',
            'applied_voucher', 
            'voucher_discount', 
            'voucher_discount_type', 
            'voucher_discount_value'
        ]);
        
        return response()->json([
            'success' => true,
            'message' => 'Đã hủy voucher'
        ]);
    }

    /**
     * Get icon type based on voucher type
     */
    public static function getIconType($type)
    {
        switch ($type) {
            case 'free_shipping':
                return 'local_shipping';
            case 'percentage':
                return 'percent';
            case 'fixed_amount':
                return 'payments';
            case 'gift':
                return 'card_giftcard';
            default:
                return 'local_offer';
        }
    }

    /**
     * Get icon color based on voucher type
     */
    public static function getIconColor($type)
    {
        switch ($type) {
            case 'free_shipping':
                return 'shipping-icon'; // orange gradient
            case 'percentage':
                return 'discount-icon'; // yellow gradient
            case 'fixed_amount':
                return 'money-icon'; // green gradient
            case 'gift':
                return 'gift-icon'; // blue gradient
            default:
                return 'discount-icon';
        }
    }
}