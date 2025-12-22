<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DigitalProduct;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class DigitalProductController extends Controller
{
    public function index()
    {
        $products = DigitalProduct::with('purchases')->latest()->paginate(10);
        return view('admin.products.digital', compact('products'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'type' => 'required|in:file,link,course',
            'instructions' => 'nullable|string',
            'download_limit' => 'required|integer|min:1',
            'access_days' => 'required|integer|min:1',
            'thumbnail' => 'nullable|image|max:2048'
        ]);

        $data = $request->only([
            'name', 'description', 'price', 'type', 
            'instructions', 'download_limit', 'access_days'
        ]);

        $data['auto_send_email'] = $request->has('auto_send_email');
        $data['email_template'] = $request->email_template;

        // Handle thumbnail upload
        if ($request->hasFile('thumbnail')) {
            $data['thumbnail'] = $request->file('thumbnail')->store('digital-products/thumbnails', 'public');
        }

        $product = DigitalProduct::create($data);

        return response()->json([
            'success' => true,
            'message' => 'Sản phẩm số đã được tạo thành công!',
            'product' => $product
        ]);
    }

    public function upload(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:digital_products,id',
            'files.*' => 'required|file|max:51200' // 50MB max
        ]);

        $product = DigitalProduct::findOrFail($request->product_id);
        $files = $product->files ?? [];

        foreach ($request->file('files') as $file) {
            $filename = time() . '_' . Str::random(10) . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('digital-products/files', $filename, 'public');
            
            $files[] = [
                'name' => $file->getClientOriginalName(),
                'path' => $path,
                'size' => $file->getSize(),
                'uploaded_at' => now()->toISOString()
            ];
        }

        $product->update(['files' => $files]);

        return response()->json([
            'success' => true,
            'message' => 'File đã được tải lên thành công!'
        ]);
    }

    public function addLink(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:digital_products,id',
            'link_name' => 'required|string|max:255',
            'link_url' => 'required|url'
        ]);

        $product = DigitalProduct::findOrFail($request->product_id);
        $links = $product->links ?? [];

        $links[] = [
            'name' => $request->link_name,
            'url' => $request->link_url,
            'added_at' => now()->toISOString()
        ];

        $product->update(['links' => $links]);

        return response()->json([
            'success' => true,
            'message' => 'Link đã được thêm thành công!'
        ]);
    }

    public function delete(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:digital_products,id'
        ]);

        $product = DigitalProduct::findOrFail($request->product_id);
        
        // Delete associated files
        if ($product->files) {
            foreach ($product->files as $file) {
                Storage::disk('public')->delete($file['path']);
            }
        }

        // Delete thumbnail
        if ($product->thumbnail) {
            Storage::disk('public')->delete($product->thumbnail);
        }

        $product->delete();

        return response()->json([
            'success' => true,
            'message' => 'Sản phẩm số đã được xóa thành công!'
        ]);
    }

    public function toggleActive(Request $request, $id)
    {
        $product = DigitalProduct::findOrFail($id);
        $product->update(['is_active' => !$product->is_active]);

        return response()->json([
            'success' => true,
            'message' => 'Trạng thái sản phẩm đã được cập nhật!',
            'is_active' => $product->is_active
        ]);
    }
}