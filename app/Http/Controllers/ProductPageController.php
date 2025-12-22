<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\Schema;

class ProductPageController extends Controller
{
    // Trang danh sách sản phẩm
    public function index(Request $request)
    {
        $query = Product::active(); // Chỉ lấy sản phẩm active
        
        // Lọc theo danh mục
        if ($request->has('type')) {
            $categoryMap = [
                'nguyen-lieu' => 1,
                'trang-tri' => 2,
                'thoi-trang' => 3,
                'combo' => 4,
                'sach' => 5,
                'thu-bong' => 6,
            ];
            
            if (isset($categoryMap[$request->type])) {
                $query->where('category_id', $categoryMap[$request->type]);
            }
        }
        
        // Tìm kiếm
        if ($request->has('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }
        
        $products = $query->paginate(12);
        
        return view('products', compact('products'));
    }
    
    // API cho danh sách sản phẩm
    public function apiIndex(Request $request)
    {
        try {
            $query = Product::active(); // Chỉ lấy sản phẩm active
            
            // Filter theo category
            if ($request->has('category') && $request->category !== 'all') {
                $query->where('category_id', $request->category);
            }
            
            // Tìm kiếm theo từ khóa
            if ($request->has('keyword') && $request->keyword) {
                $query->where('name', 'like', '%' . $request->keyword . '%');
                
                // Kiểm tra xem có cột category không
                if (\Schema::hasColumn('products', 'category')) {
                    $query->orWhere('category', 'like', '%' . $request->keyword . '%');
                }
            }
            
            // Sorting
            if ($request->has('sort')) {
                switch($request->sort) {
                    case 'price-asc':
                        $query->orderBy('price', 'asc');
                        break;
                    case 'price-desc':
                        $query->orderBy('price', 'desc');
                        break;
                    case 'name-asc':
                        $query->orderBy('name', 'asc');
                        break;
                    case 'name-desc':
                        $query->orderBy('name', 'desc');
                        break;
                    default:
                        $query->orderBy('id', 'desc');
                        break;
                }
            } else {
                $query->orderBy('id', 'desc');
            }
            
            $products = $query->get()->map(function($product) {
                // Lấy category từ cột category hoặc từ quan hệ category_id
                $category = 'Chưa phân loại';
                if (isset($product->category) && $product->category) {
                    $category = $product->category;
                } elseif (isset($product->category_id)) {
                    // Map category_id to category name
                    $categoryMap = [
                        1 => 'Nguyên phụ liệu',
                        2 => 'Đồ trang trí', 
                        3 => 'Thời trang len',
                        4 => 'Combo tự làm',
                        5 => 'Sách hướng dẫn',
                        6 => 'Thú bông len'
                    ];
                    $category = $categoryMap[$product->category_id] ?? 'Chưa phân loại';
                }
                
                return [
                    'id' => $product->id,
                    'name' => $product->name ?? 'Sản phẩm',
                    'price' => (float) ($product->price ?? 0),
                    'category' => $category,
                    'image' => $product->image ?? 'default.jpg',
                    'is_new' => isset($product->new) && ($product->new == 1 || $product->new == '1')
                ];
            });
            
            return response()->json([
                'products' => $products
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'products' => [],
                'error' => $e->getMessage()
            ]);
        }
    }
    
    // API cho sản phẩm trang chủ
    public function landingProducts()
    {
        try {
            $products = Product::active() // Chỉ lấy sản phẩm active
                ->where('status', 1)
                ->orderBy('id', 'desc')
                ->take(8)
                ->get()
                ->map(function($product) {
                    // Map category_id to category name
                    $categoryMap = [
                        1 => 'Nguyên phụ liệu',
                        2 => 'Đồ trang trí', 
                        3 => 'Thời trang len',
                        4 => 'Combo tự làm',
                        5 => 'Sách hướng dẫn',
                        6 => 'Thú bông len'
                    ];
                    
                    $category = 'Chưa phân loại';
                    if (isset($product->category) && $product->category) {
                        $category = $product->category;
                    } elseif (isset($product->category_id)) {
                        $category = $categoryMap[$product->category_id] ?? 'Chưa phân loại';
                    }
                    
                    return [
                        'id' => $product->id,
                        'name' => $product->name ?? 'Sản phẩm',
                        'price' => (float) ($product->price ?? 0),
                        'image' => $product->image ?? null,
                        'category' => $category,
                        'description' => $product->description ?? '',
                        'is_new' => isset($product->new) && ($product->new == 1 || $product->new == '1'),
                        'quantity' => $product->quantity ?? 0
                    ];
                });
            
            return response()->json([
                'products' => $products,
                'success' => true
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'products' => [],
                'success' => false,
                'error' => $e->getMessage()
            ]);
        }
    }
    
    // Chi tiết sản phẩm
    public function show($id)
    {
        $product = Product::active()->with('variants')->findOrFail($id); // Chỉ cho phép xem sản phẩm active
        
        // Lấy thông tin variants
        $availableVariants = $product->getAvailableVariants();
        $hasVariants = $product->hasVariants();
        
        // Lấy thông tin hình ảnh
        $productImages = $product->getAllImages();
        $hasMultipleImages = $product->hasMultipleImages();
        
        return view('product', compact(
            'product', 
            'availableVariants', 
            'hasVariants',
            'productImages',
            'hasMultipleImages'
        ));
    }
    
    // API để lấy thông tin variants của sản phẩm
    public function getVariants($id)
    {
        try {
            $product = Product::with('variants')->findOrFail($id);
            
            return response()->json([
                'success' => true,
                'product' => [
                    'id' => $product->id,
                    'name' => $product->name,
                    'price' => $product->price,
                    'has_variants' => $product->variants->count() > 0
                ],
                'variants' => $product->variants->map(function($variant) {
                    return [
                        'id' => $variant->id,
                        'variant_name' => $variant->variant_name,
                        'price' => $variant->price,
                        'image' => $variant->image
                    ];
                }),
                'product_images' => $product->getAllImages(),
                'has_multiple_images' => $product->hasMultipleImages(),
                'available_variants' => $product->getAvailableVariants(),
                'has_variants' => $product->hasVariants()
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 404);
        }
    }
}