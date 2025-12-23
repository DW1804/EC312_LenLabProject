{{-- Reviews List Component --}}
<div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
    <div class="flex items-center justify-between mb-6">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Đánh giá từ khách hàng</h3>
        <div class="flex items-center gap-2 text-sm text-gray-500 dark:text-gray-400">
            <span class="material-icons-round text-yellow-400">star</span>
            <span id="average-rating">0</span>
            (<span id="total-reviews">0</span> đánh giá)
        </div>
    </div>
    
    {{-- Rating Summary --}}
    <div class="mb-6 p-4 bg-gray-50 dark:bg-gray-800/50 rounded-lg" id="rating-summary" style="display: none;">
        <div class="grid grid-cols-5 gap-2 text-center">
            @for($i = 5; $i >= 1; $i--)
                <div class="flex flex-col items-center">
                    <div class="flex items-center gap-1 mb-1">
                        <span class="text-xs font-medium">{{ $i }}</span>
                        <span class="material-icons-round text-yellow-400 text-sm">star</span>
                    </div>
                    <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                        <div class="bg-yellow-400 h-2 rounded-full" id="rating-bar-{{ $i }}" style="width: 0%"></div>
                    </div>
                    <span class="text-xs text-gray-500 mt-1" id="rating-count-{{ $i }}">0</span>
                </div>
            @endfor
        </div>
    </div>
    
    {{-- Reviews List --}}
    <div id="reviews-container">
        <div class="text-center py-8" id="loading-reviews">
            <div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-primary mb-4"></div>
            <p class="text-gray-500 dark:text-gray-400">Đang tải đánh giá...</p>
        </div>
        
        <div class="text-center py-8 hidden" id="no-reviews">
            <span class="material-icons-round text-4xl text-gray-400 mb-3">rate_review</span>
            <p class="text-gray-600 dark:text-gray-400">Chưa có đánh giá nào cho sản phẩm này</p>
        </div>
        
        <div id="reviews-list" class="space-y-4"></div>
    </div>
    
    {{-- Load More Button --}}
    <div class="text-center mt-6">
        <button id="load-more-btn" class="hidden px-6 py-2 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors">
            Xem thêm đánh giá
        </button>
    </div>
</div>

@push('scripts')
<script>
let currentPage = 1;
let hasMoreReviews = true;
const productId = {{ $product->id }};

document.addEventListener('DOMContentLoaded', function() {
    loadReviews();
    
    document.getElementById('load-more-btn').addEventListener('click', function() {
        currentPage++;
        loadReviews(false);
    });
});

function loadReviews(reset = true) {
    if (reset) {
        currentPage = 1;
        hasMoreReviews = true;
        document.getElementById('reviews-list').innerHTML = '';
    }
    
    document.getElementById('loading-reviews').style.display = reset ? 'block' : 'none';
    
    fetch(`/api/reviews/${productId}?page=${currentPage}`)
        .then(response => response.json())
        .then(data => {
            document.getElementById('loading-reviews').style.display = 'none';
            
            if (data.success) {
                const reviews = data.reviews.data;
                const stats = data.stats;
                
                // Update stats
                updateReviewStats(stats);
                
                if (reviews.length === 0 && reset) {
                    document.getElementById('no-reviews').classList.remove('hidden');
                    return;
                } else {
                    document.getElementById('no-reviews').classList.add('hidden');
                }
                
                // Render reviews
                reviews.forEach(review => {
                    renderReview(review);
                });
                
                // Update pagination
                hasMoreReviews = data.reviews.next_page_url !== null;
                const loadMoreBtn = document.getElementById('load-more-btn');
                if (hasMoreReviews) {
                    loadMoreBtn.classList.remove('hidden');
                } else {
                    loadMoreBtn.classList.add('hidden');
                }
            }
        })
        .catch(error => {
            document.getElementById('loading-reviews').style.display = 'none';
            console.error('Error loading reviews:', error);
        });
}

function updateReviewStats(stats) {
    document.getElementById('average-rating').textContent = stats.average_rating ? stats.average_rating.toFixed(1) : '0';
    document.getElementById('total-reviews').textContent = stats.total_reviews;
    
    if (stats.total_reviews > 0) {
        document.getElementById('rating-summary').style.display = 'block';
        
        // Update rating distribution
        for (let i = 1; i <= 5; i++) {
            const count = stats.rating_distribution[i] || 0;
            const percentage = stats.total_reviews > 0 ? (count / stats.total_reviews) * 100 : 0;
            
            document.getElementById(`rating-bar-${i}`).style.width = percentage + '%';
            document.getElementById(`rating-count-${i}`).textContent = count;
        }
    }
}

function renderReview(review) {
    const reviewsContainer = document.getElementById('reviews-list');
    
    const reviewElement = document.createElement('div');
    reviewElement.className = 'border-b border-gray-200 dark:border-gray-700 pb-4 last:border-b-0';
    
    // Generate stars
    let starsHtml = '';
    for (let i = 1; i <= 5; i++) {
        starsHtml += `<span class="material-icons-round text-sm ${i <= review.rating ? 'text-yellow-400' : 'text-gray-300 dark:text-gray-600'}">star</span>`;
    }
    
    // Generate images
    let imagesHtml = '';
    if (review.image_urls && review.image_urls.length > 0) {
        imagesHtml = '<div class="flex gap-2 mt-3">';
        review.image_urls.forEach(imageUrl => {
            imagesHtml += `<img src="${imageUrl}" alt="Review Image" class="w-16 h-16 object-cover rounded-lg border border-gray-200 cursor-pointer hover:opacity-90 transition-opacity" onclick="openImageModal('${imageUrl}')">`;
        });
        imagesHtml += '</div>';
    }
    
    reviewElement.innerHTML = `
        <div class="flex items-start gap-3">
            ${review.user.avatar ? 
                `<img src="/storage/avatars/${review.user.avatar}" alt="${review.user.name}" class="w-10 h-10 rounded-full object-cover">` :
                `<div class="w-10 h-10 rounded-full bg-primary/20 text-primary flex items-center justify-center font-bold text-sm">${review.user.name.substring(0, 2).toUpperCase()}</div>`
            }
            <div class="flex-1">
                <div class="flex items-center gap-2 mb-1">
                    <h4 class="font-semibold text-gray-900 dark:text-white">${review.user.name}</h4>
                    <div class="flex items-center gap-1">${starsHtml}</div>
                </div>
                <p class="text-xs text-gray-500 dark:text-gray-400 mb-2">${review.formatted_created_at}</p>
                ${review.comment ? `<p class="text-gray-700 dark:text-gray-300 text-sm leading-relaxed">${review.comment}</p>` : ''}
                ${imagesHtml}
            </div>
        </div>
    `;
    
    reviewsContainer.appendChild(reviewElement);
}

// Image modal (reuse from review form)
function openImageModal(imageUrl) {
    let modal = document.getElementById('review-image-modal');
    if (!modal) {
        modal = document.createElement('div');
        modal.id = 'review-image-modal';
        modal.className = 'fixed inset-0 bg-black/80 hidden items-center justify-center z-50';
        modal.onclick = () => closeReviewImageModal();
        modal.innerHTML = `
            <div class="max-w-4xl max-h-[90vh] p-4">
                <img id="review-modal-image" src="" alt="Review Image" class="max-w-full max-h-full object-contain rounded-lg">
            </div>
        `;
        document.body.appendChild(modal);
    }
    
    document.getElementById('review-modal-image').src = imageUrl;
    modal.classList.remove('hidden');
    modal.classList.add('flex');
}

function closeReviewImageModal() {
    const modal = document.getElementById('review-image-modal');
    if (modal) {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }
}
</script>
@endpush