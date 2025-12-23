{{-- Review Form Component --}}
<div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Đánh giá sản phẩm</h3>
    
    @auth
        <form id="review-form" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="product_id" value="{{ $product->id }}">
            
            {{-- Rating --}}
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Đánh giá của bạn</label>
                <div class="flex items-center gap-1" id="rating-stars">
                    @for($i = 1; $i <= 5; $i++)
                        <button type="button" class="star-btn text-2xl text-gray-300 hover:text-yellow-400 transition-colors" data-rating="{{ $i }}">
                            <span class="material-icons-round">star</span>
                        </button>
                    @endfor
                </div>
                <input type="hidden" name="rating" id="rating-input" required>
            </div>
            
            {{-- Comment --}}
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Nhận xét</label>
                <textarea name="comment" rows="4" 
                          class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary/50 focus:border-primary"
                          placeholder="Chia sẻ trải nghiệm của bạn về sản phẩm này..."></textarea>
            </div>
            
            {{-- Images --}}
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Hình ảnh (tùy chọn)</label>
                <div class="border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-lg p-4 text-center">
                    <input type="file" name="images[]" id="review-images" multiple accept="image/*" class="hidden">
                    <div id="image-preview" class="flex gap-2 flex-wrap mb-3 hidden"></div>
                    <button type="button" onclick="document.getElementById('review-images').click()" 
                            class="inline-flex items-center px-4 py-2 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors">
                        <span class="material-icons-round mr-2 text-sm">add_photo_alternate</span>
                        Thêm hình ảnh
                    </button>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">JPG, PNG tối đa 5MB mỗi ảnh</p>
                </div>
            </div>
            
            {{-- Submit Button --}}
            <button type="submit" id="submit-review-btn"
                    class="w-full bg-primary text-white font-semibold py-3 px-4 rounded-lg hover:bg-primary-hover transition-colors flex items-center justify-center gap-2">
                <span class="material-icons-round">rate_review</span>
                Gửi đánh giá
            </button>
        </form>
    @else
        <div class="text-center py-8">
            <span class="material-icons-round text-4xl text-gray-400 mb-3">account_circle</span>
            <p class="text-gray-600 dark:text-gray-400 mb-4">Vui lòng đăng nhập để đánh giá sản phẩm</p>
            <a href="{{ route('login') }}" class="inline-flex items-center px-4 py-2 bg-primary text-white rounded-lg hover:bg-primary-hover transition-colors">
                Đăng nhập
            </a>
        </div>
    @endauth
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Rating stars
    const stars = document.querySelectorAll('.star-btn');
    const ratingInput = document.getElementById('rating-input');
    let currentRating = 0;
    
    stars.forEach((star, index) => {
        star.addEventListener('click', function() {
            currentRating = index + 1;
            ratingInput.value = currentRating;
            updateStars();
        });
        
        star.addEventListener('mouseenter', function() {
            highlightStars(index + 1);
        });
    });
    
    document.getElementById('rating-stars').addEventListener('mouseleave', function() {
        updateStars();
    });
    
    function updateStars() {
        stars.forEach((star, index) => {
            if (index < currentRating) {
                star.classList.remove('text-gray-300');
                star.classList.add('text-yellow-400');
            } else {
                star.classList.remove('text-yellow-400');
                star.classList.add('text-gray-300');
            }
        });
    }
    
    function highlightStars(rating) {
        stars.forEach((star, index) => {
            if (index < rating) {
                star.classList.remove('text-gray-300');
                star.classList.add('text-yellow-400');
            } else {
                star.classList.remove('text-yellow-400');
                star.classList.add('text-gray-300');
            }
        });
    }
    
    // Image preview
    document.getElementById('review-images').addEventListener('change', function(e) {
        const preview = document.getElementById('image-preview');
        preview.innerHTML = '';
        
        if (e.target.files.length > 0) {
            preview.classList.remove('hidden');
            
            Array.from(e.target.files).forEach((file, index) => {
                if (file.type.startsWith('image/')) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        const img = document.createElement('img');
                        img.src = e.target.result;
                        img.className = 'w-16 h-16 object-cover rounded-lg border border-gray-200';
                        preview.appendChild(img);
                    };
                    reader.readAsDataURL(file);
                }
            });
        } else {
            preview.classList.add('hidden');
        }
    });
    
    // Form submission
    document.getElementById('review-form')?.addEventListener('submit', function(e) {
        e.preventDefault();
        
        if (!currentRating) {
            alert('Vui lòng chọn số sao đánh giá');
            return;
        }
        
        const submitBtn = document.getElementById('submit-review-btn');
        const originalText = submitBtn.innerHTML;
        
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<span class="material-icons-round animate-spin">refresh</span> Đang gửi...';
        
        const formData = new FormData(this);
        
        fetch('/api/reviews', {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showNotification(data.message, 'success');
                this.reset();
                currentRating = 0;
                updateStars();
                document.getElementById('image-preview').classList.add('hidden');
                // Reload reviews list if exists
                if (typeof loadReviews === 'function') {
                    loadReviews();
                }
            } else {
                showNotification(data.message, 'error');
            }
        })
        .catch(error => {
            showNotification('Có lỗi xảy ra khi gửi đánh giá', 'error');
        })
        .finally(() => {
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalText;
        });
    });
    
    function showNotification(message, type) {
        const notification = document.createElement('div');
        notification.className = `fixed top-4 right-4 z-50 p-4 rounded-lg shadow-lg max-w-sm transform transition-all duration-300 ${
            type === 'success' ? 'bg-green-500 text-white' : 'bg-red-500 text-white'
        }`;
        notification.innerHTML = `
            <div class="flex items-center gap-3">
                <span class="material-icons-round">${type === 'success' ? 'check_circle' : 'error'}</span>
                <span class="flex-1">${message}</span>
                <button onclick="this.parentElement.parentElement.remove()" class="hover:opacity-70">
                    <span class="material-icons-round text-sm">close</span>
                </button>
            </div>
        `;

        document.body.appendChild(notification);
        setTimeout(() => notification.remove(), 5000);
    }
});
</script>
@endpush