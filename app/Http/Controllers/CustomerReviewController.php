<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\Product;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CustomerReviewController extends Controller
{
    public function store(Request $request)
    {
        if (!Auth::check()) {
            return response()->json([
                'success' => false,
                'message' => 'Vui lòng đăng nhập để đánh giá'
            ], 401);
        }

        $request->validate([
            'product_id' => 'required|exists:products,id',
            'order_id' => 'nullable|string',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg|max:5120' // 5MB max
        ]);

        try {
            // Check if user already reviewed this product
            $existingReview = Review::where('user_id', Auth::id())
                ->where('product_id', $request->product_id)
                ->first();

            if ($existingReview) {
                return response()->json([
                    'success' => false,
                    'message' => 'Bạn đã đánh giá sản phẩm này rồi!'
                ], 422);
            }

            // Verify order if provided
            $orderId = null;
            if ($request->order_id) {
                $order = Order::where('order_id', $request->order_id)
                    ->where('user_id', Auth::id())
                    ->whereHas('items', function ($query) use ($request) {
                        $query->where('product_id', $request->product_id);
                    })
                    ->first();

                if (!$order) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Bạn chưa mua sản phẩm này!'
                    ], 422);
                }
                $orderId = $order->order_id;
            }

            // Handle image uploads
            $imageNames = [];
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $image) {
                    $imageName = 'review_' . time() . '_' . Str::random(10) . '.' . $image->getClientOriginalExtension();
                    $image->storeAs('reviews', $imageName, 'public');
                    $imageNames[] = $imageName;
                }
            }

            // Create review
            $review = Review::create([
                'user_id' => Auth::id(),
                'product_id' => $request->product_id,
                'order_id' => $orderId,
                'rating' => $request->rating,
                'comment' => $request->comment,
                'images' => $imageNames,
                'status' => 'pending'
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Cảm ơn bạn đã đánh giá! Đánh giá sẽ được hiển thị sau khi được duyệt.',
                'review' => $review->load('user')
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra: ' . $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, Review $review)
    {
        if (!Auth::check() || $review->user_id !== Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'Không có quyền chỉnh sửa đánh giá này'
            ], 403);
        }

        // Only allow editing pending reviews
        if ($review->status !== 'pending') {
            return response()->json([
                'success' => false,
                'message' => 'Chỉ có thể chỉnh sửa đánh giá đang chờ duyệt'
            ], 422);
        }

        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg|max:5120'
        ]);

        try {
            $updateData = [
                'rating' => $request->rating,
                'comment' => $request->comment
            ];

            // Handle new image uploads
            if ($request->hasFile('images')) {
                // Delete old images
                if ($review->images) {
                    foreach ($review->images as $oldImage) {
                        Storage::disk('public')->delete('reviews/' . $oldImage);
                    }
                }

                // Upload new images
                $imageNames = [];
                foreach ($request->file('images') as $image) {
                    $imageName = 'review_' . time() . '_' . Str::random(10) . '.' . $image->getClientOriginalExtension();
                    $image->storeAs('reviews', $imageName, 'public');
                    $imageNames[] = $imageName;
                }
                $updateData['images'] = $imageNames;
            }

            $review->update($updateData);

            return response()->json([
                'success' => true,
                'message' => 'Đánh giá đã được cập nhật thành công!',
                'review' => $review->fresh()->load('user')
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy(Review $review)
    {
        if (!Auth::check() || $review->user_id !== Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'Không có quyền xóa đánh giá này'
            ], 403);
        }

        try {
            // Delete images
            if ($review->images) {
                foreach ($review->images as $image) {
                    Storage::disk('public')->delete('reviews/' . $image);
                }
            }

            $review->delete();

            return response()->json([
                'success' => true,
                'message' => 'Đánh giá đã được xóa thành công!'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getProductReviews(Product $product)
    {
        $reviews = Review::where('product_id', $product->id)
            ->approved()
            ->with('user')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $stats = [
            'total_reviews' => Review::where('product_id', $product->id)->approved()->count(),
            'average_rating' => Review::where('product_id', $product->id)->approved()->avg('rating'),
            'rating_distribution' => [
                5 => Review::where('product_id', $product->id)->approved()->withRating(5)->count(),
                4 => Review::where('product_id', $product->id)->approved()->withRating(4)->count(),
                3 => Review::where('product_id', $product->id)->approved()->withRating(3)->count(),
                2 => Review::where('product_id', $product->id)->approved()->withRating(2)->count(),
                1 => Review::where('product_id', $product->id)->approved()->withRating(1)->count(),
            ]
        ];

        return response()->json([
            'success' => true,
            'reviews' => $reviews,
            'stats' => $stats
        ]);
    }

    public function getUserReviews()
    {
        if (!Auth::check()) {
            return response()->json([
                'success' => false,
                'message' => 'Vui lòng đăng nhập'
            ], 401);
        }

        $reviews = Review::where('user_id', Auth::id())
            ->with('product')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return response()->json([
            'success' => true,
            'reviews' => $reviews
        ]);
    }
}