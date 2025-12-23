<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Review;
use App\Models\User;
use App\Models\Product;
use App\Models\Admin;

class ReviewSeeder extends Seeder
{
    /**
     * Run the database seeder.
     */
    public function run(): void
    {
        // Get some users and products
        $users = User::limit(10)->get();
        $products = Product::limit(5)->get();
        $admin = Admin::first();

        if ($users->isEmpty() || $products->isEmpty()) {
            $this->command->info('Cần có ít nhất 1 user và 1 product để tạo reviews');
            return;
        }

        $sampleComments = [
            'Sản phẩm rất đẹp, chất lượng tốt, giao hàng nhanh!',
            'Mình rất hài lòng với sản phẩm này. Đóng gói cẩn thận.',
            'Chất lượng ổn, giá cả hợp lý. Sẽ mua lại lần sau.',
            'Sản phẩm đúng như mô tả, shop tư vấn nhiệt tình.',
            'Hơi nhỏ so với mình tưởng tượng nhưng vẫn đẹp.',
            'Chất liệu tốt, màu sắc đẹp như hình. Recommend!',
            'Giao hàng hơi chậm nhưng sản phẩm ok.',
            'Rất đáng tiền! Sẽ giới thiệu cho bạn bè.',
            'Sản phẩm dễ thương, con mình rất thích.',
            'Shop phục vụ tốt, sản phẩm chất lượng cao.'
        ];

        $statuses = ['pending', 'approved', 'hidden'];
        $weights = [0.2, 0.7, 0.1]; // 20% pending, 70% approved, 10% hidden

        foreach ($products as $product) {
            // Create 3-8 reviews per product
            $reviewCount = rand(3, 8);
            
            for ($i = 0; $i < $reviewCount; $i++) {
                $user = $users->random();
                $rating = $this->getWeightedRating();
                $status = $this->getWeightedStatus($statuses, $weights);
                
                $review = Review::create([
                    'user_id' => $user->id,
                    'product_id' => $product->id,
                    'rating' => $rating,
                    'comment' => $sampleComments[array_rand($sampleComments)],
                    'status' => $status,
                    'created_at' => now()->subDays(rand(1, 30)),
                    'updated_at' => now()->subDays(rand(1, 30))
                ]);

                // If approved, set approval info
                if ($status === 'approved' && $admin) {
                    $review->update([
                        'approved_at' => $review->created_at->addHours(rand(1, 24)),
                        'approved_by' => $admin->id
                    ]);
                }
            }
        }

        $this->command->info('Đã tạo ' . Review::count() . ' reviews mẫu');
    }

    private function getWeightedRating()
    {
        // Weighted towards higher ratings (more realistic)
        $ratings = [1, 2, 3, 4, 5];
        $weights = [0.05, 0.1, 0.15, 0.3, 0.4]; // 5% 1-star, 10% 2-star, 15% 3-star, 30% 4-star, 40% 5-star
        
        $rand = mt_rand() / mt_getrandmax();
        $cumulative = 0;
        
        for ($i = 0; $i < count($ratings); $i++) {
            $cumulative += $weights[$i];
            if ($rand <= $cumulative) {
                return $ratings[$i];
            }
        }
        
        return 5; // fallback
    }

    private function getWeightedStatus($statuses, $weights)
    {
        $rand = mt_rand() / mt_getrandmax();
        $cumulative = 0;
        
        for ($i = 0; $i < count($statuses); $i++) {
            $cumulative += $weights[$i];
            if ($rand <= $cumulative) {
                return $statuses[$i];
            }
        }
        
        return 'approved'; // fallback
    }
}