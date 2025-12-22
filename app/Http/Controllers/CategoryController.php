<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CategoryController extends Controller
{
    // API để lấy danh sách danh mục
    public function apiIndex()
    {
        try {
            // Danh sách danh mục cố định dựa trên thiết kế
            $categories = [
                [
                    'id' => 1,
                    'name' => 'Nguyên phụ liệu',
                    'icon' => 'inventory_2',
                    'keyword' => 'Nguyên phụ liệu',
                    'description' => 'Len, kim đan, móc và các dụng cụ cần thiết'
                ],
                [
                    'id' => 2,
                    'name' => 'Đồ trang trí',
                    'icon' => 'potted_plant',
                    'keyword' => 'Đồ trang trí',
                    'description' => 'Hoa len, đồ trang trí handmade'
                ],
                [
                    'id' => 3,
                    'name' => 'Thời trang len',
                    'icon' => 'checkroom',
                    'keyword' => 'Thời trang len',
                    'description' => 'Áo len, khăn, mũ và phụ kiện thời trang'
                ],
                [
                    'id' => 7,
                    'name' => 'Sản phẩm số',
                    'icon' => 'download',
                    'keyword' => 'digital',
                    'url' => '/san-pham-so',
                    'description' => 'Tài liệu, khóa học và hướng dẫn kỹ thuật số'
                ],
                [
                    'id' => 4,
                    'name' => 'Combo tiết kiệm',
                    'icon' => 'savings',
                    'keyword' => 'Combo tự làm',
                    'description' => 'Bộ combo nguyên liệu với giá ưu đãi'
                ],
                [
                    'id' => 5,
                    'name' => 'Thú bông len',
                    'icon' => 'pets',
                    'keyword' => 'Thú bông',
                    'description' => 'Thú bông handmade đáng yêu'
                ],
                [
                    'id' => 6,
                    'name' => 'Sách hướng dẫn',
                    'icon' => 'menu_book',
                    'keyword' => 'Sách hướng dẫn',
                    'description' => 'Sách và tài liệu hướng dẫn đan móc'
                ]
            ];
            
            return response()->json([
                'categories' => $categories,
                'success' => true
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'categories' => [],
                'success' => false,
                'error' => $e->getMessage()
            ]);
        }
    }
    
    // API để lấy sản phẩm theo danh mục
    public function getProductsByCategory($categoryId)
    {
        try {
            $products = \App\Models\Product::where('category_id', $categoryId)
                ->where('status', 1)
                ->take(10)
                ->get()
                ->map(function($product) {
                    return [
                        'id' => $product->id,
                        'name' => $product->name ?? 'Sản phẩm',
                        'price' => (float) ($product->price ?? 0),
                        'image' => $product->image ?? null,
                        'description' => $product->description ?? '',
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
}