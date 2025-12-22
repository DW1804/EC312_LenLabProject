<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\ProductVariant;
use App\Models\Voucher;
use App\Models\Product;
use App\Models\DigitalProduct;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    // 1. Hiển thị trang giỏ hàng (Server Side Rendering)
    public function show()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $userId = Auth::id();
        $cart = $this->getCartData($userId);
        $subtotal = $this->calculateSubtotal($cart);

        // Lấy thông tin voucher từ session
        $voucherCode = session('voucher_code');
        $voucherDiscount = session('voucher_discount', 0);

        return view('cart', compact('cart', 'subtotal', 'voucherCode', 'voucherDiscount'));
    }

    // 2. Lấy giỏ hàng (API / AJAX)
    public function index()
    {
        if (!Auth::check()) {
            return response()->json([
                'cart' => [],
                'total_items' => 0,
                'subtotal' => 0,
                'success' => true,
            ]);
        }

        $userId = Auth::id();
        $cart = $this->getCartData($userId);
        $subtotal = $this->calculateSubtotal($cart);
        $totalItems = $cart->sum('quantity');

        // Format data cho API frontend
        $formattedCart = $cart->map(function ($item) {
            $baseData = [
                'id' => $item->id,
                'product_id' => $item->product_id,
                'variant_id' => $item->variant_id,
                'quantity' => $item->quantity,
                'price_at_time' => $item->price_at_time,
                'variant_info' => $item->variant_info,
                'product_type' => $item->product_type ?? 'normal'
            ];

            if ($item->product_type === 'digital') {
                $baseData['digital_product'] = $item->digitalProduct ? [
                    'id' => $item->digitalProduct->id,
                    'name' => $item->digitalProduct->name,
                    'price' => $item->digitalProduct->price,
                    'type' => $item->digitalProduct->type,
                    'thumbnail' => $item->digitalProduct->thumbnail_url,
                ] : null;
            } else {
                $baseData['product'] = $item->product ? [
                    'id' => $item->product->id,
                    'name' => $item->product->name,
                    'price' => $item->product->price,
                    'image' => $item->product->image,
                    'category' => $item->product->category ?? 'Chưa phân loại',
                ] : null;
                
                $baseData['variant'] = $item->variant ? [
                    'id' => $item->variant->id,
                    'color' => $item->variant->color ?? null,
                    'size' => $item->variant->size ?? null,
                    'price' => $item->variant->price ?? null,
                    'image' => $item->variant->image ?? null,
                ] : null;
            }

            return $baseData;
        });

        return response()->json([
            'cart' => $formattedCart,
            'total_items' => $totalItems,
            'subtotal' => $subtotal,
            'success' => true,
        ]);
    }

    // 3. Thêm sản phẩm (Logic gộp chung)
    public function add(Request $request)
    {
        if (!Auth::check()) {
            return response()->json(['success' => false, 'message' => 'Vui lòng đăng nhập'], 401);
        }

        $productType = $request->input('product_type', 'normal');
        
        if ($productType === 'digital') {
            return $this->addDigitalProduct($request);
        }

        return $this->addNormalProduct($request);
    }

    // Helper: Thêm sản phẩm thường (Tách ra để code gọn hơn)
    private function addNormalProduct(Request $request)
    {
        $request->validate([
            'product_id' => 'required|integer|exists:products,id',
            'variant_id' => 'nullable|integer|exists:product_variants,id',
            'variant_name' => 'nullable|string',
            'quantity'   => 'required|integer|min:1',
        ]);

        $userId = Auth::id();
        $productId = $request->product_id;
        $variantId = $request->variant_id;
        $quantity = (int)$request->quantity;

        // Chuẩn bị dữ liệu giá và info
        $price = 0;
        $variantInfo = null;

        if ($variantId) {
            $variant = ProductVariant::findOrFail($variantId);
            // Kiểm tra variant có thuộc product không
            if ((int)$variant->product_id !== (int)$productId) {
                return response()->json(['success' => false, 'message' => 'Variant không hợp lệ'], 422);
            }
            $price = $variant->price;
            $variantInfo = [
                'color' => $variant->color,
                'size'  => $variant->size,
                'image' => $variant->image,
            ];
        } else {
            $product = Product::findOrFail($productId);
            $price = $product->price;
            if ($request->variant_name) {
                $variantInfo = ['variant_name' => $request->variant_name];
            }
        }

        // Tìm item trong cart
        $cartItem = Cart::where('user_id', $userId)
            ->where('product_id', $productId)
            ->where('product_type', 'normal')
            ->where('variant_id', $variantId)
            ->first();

        if ($cartItem) {
            $cartItem->quantity += $quantity;
            $cartItem->price_at_time = $price; // Cập nhật giá mới nhất
            if ($variantInfo) {
                $cartItem->variant_info = $variantInfo;
            }
            $cartItem->save();
        } else {
            Cart::create([
                'user_id' => $userId,
                'product_id' => $productId,
                'variant_id' => $variantId,
                'quantity' => $quantity,
                'price_at_time' => $price,
                'product_type' => 'normal',
                'variant_info' => $variantInfo,
            ]);
        }

        $cartCount = Cart::where('user_id', $userId)->sum('quantity');
        return response()->json(['success' => true, 'message' => 'Đã thêm vào giỏ', 'cart_count' => $cartCount]);
    }

    // Helper: Thêm sản phẩm số
    private function addDigitalProduct(Request $request)
    {
        $request->validate([
            'product_id' => 'required|integer|exists:digital_products,id',
        ]);

        $userId = Auth::id();
        $digitalProduct = DigitalProduct::findOrFail($request->product_id);

        if (!$digitalProduct->is_active) {
            return response()->json(['success' => false, 'message' => 'Sản phẩm không khả dụng'], 422);
        }

        $exists = Cart::where('user_id', $userId)
            ->where('product_id', $request->product_id)
            ->where('product_type', 'digital')
            ->exists();

        if ($exists) {
            return response()->json(['success' => false, 'message' => 'Sản phẩm này đã có trong giỏ'], 422);
        }

        Cart::create([
            'user_id' => $userId,
            'product_id' => $request->product_id,
            'quantity' => 1,
            'price_at_time' => $digitalProduct->price,
            'product_type' => 'digital',
            'variant_info' => [
                'type' => $digitalProduct->type,
                'name' => $digitalProduct->name,
                'thumbnail' => $digitalProduct->thumbnail
            ]
        ]);

        $cartCount = Cart::where('user_id', $userId)->sum('quantity');
        return response()->json(['success' => true, 'message' => 'Đã thêm vào giỏ', 'cart_count' => $cartCount]);
    }

    // 4. Update Quantity
    public function updateQuantity(Request $request)
    {
        if (!Auth::check()) return response()->json(['success' => false], 401);

        $request->validate([
            'id' => 'required|integer|exists:cart,id',
            'action' => 'required|in:increase,decrease',
        ]);

        $item = Cart::where('id', $request->id)->where('user_id', Auth::id())->first();

        if (!$item) return response()->json(['success' => false], 404);

        if ($request->action === 'increase') {
            // Với sản phẩm số, không cho tăng quá 1
            if ($item->product_type === 'digital') {
                return response()->json(['success' => false, 'message' => 'Sản phẩm số chỉ mua được 1']);
            }
            $item->increment('quantity');
        } else {
            if ($item->quantity > 1) {
                $item->decrement('quantity');
            } else {
                $item->delete();
                return response()->json(['success' => true, 'removed' => true]);
            }
        }

        return response()->json(['success' => true, 'new_quantity' => $item->quantity]);
    }

    // 5. Delete Item
    public function delete(Request $request)
    {
        if (!Auth::check()) return response()->json(['success' => false], 401);

        $deleted = Cart::where('id', $request->id)->where('user_id', Auth::id())->delete();
        return response()->json(['success' => (bool)$deleted]);
    }

    // 6. Apply Voucher
    public function applyVoucher(Request $request)
    {
        if (!Auth::check()) return response()->json(["success" => false, "message" => "Vui lòng đăng nhập"], 401);

        $request->validate(['code' => 'required|string']);

        $voucher = Voucher::where('code', $request->code)->where('active', 1)->first();

        if (!$voucher) return response()->json(["success" => false, "message" => "Mã không hợp lệ"], 422);

        // Check thời gian
        $now = now();
        if (($voucher->start_date && $now->lt($voucher->start_date)) || 
            ($voucher->end_date && $now->gt($voucher->end_date))) {
            return response()->json(["success" => false, "message" => "Mã không khả dụng"], 422);
        }

        // Tính subtotal để check điều kiện
        $cart = $this->getCartData(Auth::id());
        $subtotal = $this->calculateSubtotal($cart);

        if ($voucher->min_order_value && $subtotal < $voucher->min_order_value) {
            return response()->json([
                "success" => false, 
                "message" => "Đơn tối thiểu " . number_format($voucher->min_order_value) . "đ"
            ], 422);
        }

        // Tính discount
        $discount = 0;
        $discountPercent = 0;
        if ($voucher->type === "fixed") {
            $discount = (int) $voucher->discount_value;
        } else {
            $discount = (int) ($subtotal * ($voucher->discount_value / 100));
            $discountPercent = (int) $voucher->discount_value;
            // Nếu có giảm tối đa (max_discount_amount) thì check thêm ở đây
        }

        // Lưu session
        session([
            'voucher_code' => $voucher->code,
            'voucher_discount' => $discount,
            'voucher_type' => $voucher->type,
            'voucher_value' => $voucher->discount_value
        ]);

        return response()->json([
            "success" => true,
            "message" => "Áp dụng thành công",
            "discount" => $discount,
            "discount_percent" => $discountPercent,
            "voucher_code" => $voucher->code
        ]);
    }

    // ================= PRIVATE HELPERS (Tránh lặp code) =================

    private function getCartData($userId)
    {
        return Cart::where('user_id', $userId)
            ->with(['product', 'variant', 'digitalProduct'])
            ->orderBy('updated_at', 'desc')
            ->get();
    }

    private function calculateSubtotal($cartCollection)
    {
        return $cartCollection->sum(function ($item) {
            $price = $item->price_at_time
                ?? optional($item->variant)->price
                ?? optional($item->product)->price
                ?? optional($item->digitalProduct)->price
                ?? 0;
            return $price * $item->quantity;
        });
    }
}
