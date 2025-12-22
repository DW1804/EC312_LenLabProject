<?php

namespace App\Http\Controllers;

use App\Models\DigitalProduct;
use App\Models\DigitalProductPurchase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DigitalProductController extends Controller
{
    /**
     * Hiển thị danh sách sản phẩm số cho khách hàng
     */
    public function index()
    {
        $products = DigitalProduct::active()
            ->select('id', 'name', 'description', 'price', 'type', 'thumbnail')
            ->paginate(12);

        return view('digital-products.index', compact('products'));
    }

    /**
     * Hiển thị chi tiết sản phẩm số
     */
    public function show($id)
    {
        $product = DigitalProduct::active()->findOrFail($id);
        
        return view('digital-products.show', compact('product'));
    }

    /**
     * Xử lý mua sản phẩm số
     */
    public function purchase(Request $request, $id)
    {
        $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_email' => 'required|email|max:255'
        ]);

        $product = DigitalProduct::active()->findOrFail($id);
        
        // Tạo mã đơn hàng unique
        $orderCode = 'DP' . time() . rand(1000, 9999);
        
        // Tính ngày hết hạn
        $expiresAt = now()->addDays($product->access_days);

        $purchase = DigitalProductPurchase::create([
            'digital_product_id' => $product->id,
            'customer_email' => $request->customer_email,
            'customer_name' => $request->customer_name,
            'order_code' => $orderCode,
            'amount_paid' => $product->price,
            'purchased_at' => now(),
            'expires_at' => $expiresAt
        ]);

        // Gửi email nếu được cấu hình
        if ($product->auto_send_email) {
            // TODO: Implement email sending
        }

        return redirect()->route('digital-products.download', $orderCode)
            ->with('success', 'Mua sản phẩm thành công! Bạn có thể tải xuống ngay bây giờ.');
    }

    /**
     * Trang tải xuống sản phẩm
     */
    public function download($orderCode)
    {
        $purchase = DigitalProductPurchase::with('digitalProduct')
            ->where('order_code', $orderCode)
            ->firstOrFail();

        if ($purchase->isExpired()) {
            return view('digital-products.expired', compact('purchase'));
        }

        return view('digital-products.download', compact('purchase'));
    }

    /**
     * Tải file xuống
     */
    public function downloadFile($orderCode, $fileIndex)
    {
        $purchase = DigitalProductPurchase::with('digitalProduct')
            ->where('order_code', $orderCode)
            ->firstOrFail();

        if (!$purchase->canDownload()) {
            abort(403, 'Bạn đã hết lượt tải xuống hoặc đã hết hạn.');
        }

        $files = $purchase->digitalProduct->files ?? [];
        
        if (!isset($files[$fileIndex])) {
            abort(404, 'File không tồn tại.');
        }

        $file = $files[$fileIndex];
        $filePath = storage_path('app/public/' . $file['path']);

        if (!file_exists($filePath)) {
            abort(404, 'File không tồn tại trên server.');
        }

        // Ghi nhận lượt tải xuống
        $purchase->recordDownload();

        return response()->download($filePath, $file['name']);
    }
}