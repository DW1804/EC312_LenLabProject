<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Review;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ReviewController extends Controller
{
    public function index(Request $request)
    {
        $query = Review::with(['user', 'product'])
            ->orderBy('created_at', 'desc');

        // Filter by status
        if ($request->has('status') && $request->status !== 'all') {
            switch ($request->status) {
                case 'pending':
                    $query->pending();
                    break;
                case 'approved':
                    $query->approved();
                    break;
                case 'hidden':
                    $query->hidden();
                    break;
            }
        }

        // Filter by rating
        if ($request->has('rating') && $request->rating) {
            $query->withRating($request->rating);
        }

        // Filter reviews with images
        if ($request->has('with_images') && $request->with_images) {
            $query->withImages();
        }

        // Search functionality
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->whereHas('user', function ($userQuery) use ($search) {
                    $userQuery->where('name', 'like', "%{$search}%")
                             ->orWhere('email', 'like', "%{$search}%");
                })
                ->orWhereHas('product', function ($productQuery) use ($search) {
                    $productQuery->where('name', 'like', "%{$search}%");
                })
                ->orWhere('comment', 'like', "%{$search}%");
            });
        }

        $reviews = $query->paginate(10);

        // Get counts for tabs
        $counts = [
            'all' => Review::count(),
            'pending' => Review::pending()->count(),
            'approved' => Review::approved()->count(),
            'hidden' => Review::hidden()->count(),
            'five_star' => Review::withRating(5)->count(),
            'with_images' => Review::withImages()->count()
        ];

        return view('admin.reviews.index', [
            'reviews' => $reviews,
            'counts' => $counts,
            'currentStatus' => $request->get('status', 'all'),
            'currentRating' => $request->get('rating'),
            'currentSearch' => $request->get('search'),
            'pageTitle' => 'Đánh giá sản phẩm',
            'custom_header' => true
        ]);
    }

    public function approve(Request $request, Review $review)
    {
        try {
            $review->approve(Auth::guard('admin')->id());

            return response()->json([
                'success' => true,
                'message' => 'Đánh giá đã được duyệt thành công!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra: ' . $e->getMessage()
            ], 500);
        }
    }

    public function hide(Request $request, Review $review)
    {
        try {
            $review->hide();

            return response()->json([
                'success' => true,
                'message' => 'Đánh giá đã được ẩn thành công!'
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
        try {
            // Delete review images if exist
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

    public function bulkAction(Request $request)
    {
        $request->validate([
            'action' => 'required|in:approve,hide,delete',
            'review_ids' => 'required|array',
            'review_ids.*' => 'exists:reviews,id'
        ]);

        try {
            $reviews = Review::whereIn('id', $request->review_ids);
            $count = $reviews->count();

            switch ($request->action) {
                case 'approve':
                    $reviews->update([
                        'status' => 'approved',
                        'approved_at' => now(),
                        'approved_by' => Auth::guard('admin')->id()
                    ]);
                    $message = "Đã duyệt {$count} đánh giá thành công!";
                    break;

                case 'hide':
                    $reviews->update(['status' => 'hidden']);
                    $message = "Đã ẩn {$count} đánh giá thành công!";
                    break;

                case 'delete':
                    // Delete images for all reviews
                    foreach ($reviews->get() as $review) {
                        if ($review->images) {
                            foreach ($review->images as $image) {
                                Storage::disk('public')->delete('reviews/' . $image);
                            }
                        }
                    }
                    $reviews->delete();
                    $message = "Đã xóa {$count} đánh giá thành công!";
                    break;
            }

            return response()->json([
                'success' => true,
                'message' => $message
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra: ' . $e->getMessage()
            ], 500);
        }
    }

    public function show(Review $review)
    {
        $review->load(['user', 'product', 'approvedBy']);
        
        return response()->json([
            'success' => true,
            'review' => $review
        ]);
    }

    public function stats()
    {
        $stats = [
            'total_reviews' => Review::count(),
            'pending_reviews' => Review::pending()->count(),
            'approved_reviews' => Review::approved()->count(),
            'hidden_reviews' => Review::hidden()->count(),
            'average_rating' => Review::approved()->avg('rating'),
            'rating_distribution' => [
                5 => Review::approved()->withRating(5)->count(),
                4 => Review::approved()->withRating(4)->count(),
                3 => Review::approved()->withRating(3)->count(),
                2 => Review::approved()->withRating(2)->count(),
                1 => Review::approved()->withRating(1)->count(),
            ],
            'reviews_with_images' => Review::withImages()->count(),
            'recent_reviews' => Review::with(['user', 'product'])
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->get()
        ];

        return response()->json([
            'success' => true,
            'stats' => $stats
        ]);
    }
}
