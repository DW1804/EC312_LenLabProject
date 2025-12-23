@extends('admin.layout')

{{-- Ghi đè Header mặc định để thêm Ô tìm kiếm & Bộ lọc --}}
@section('header')
<div class="bg-surface-light dark:bg-surface-dark border-b border-gray-200 dark:border-gray-700 px-4 py-4 sm:px-8 sticky top-0 z-20">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Đánh giá sản phẩm</h2>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Quản lý phản hồi và đánh giá từ khách hàng.</p>
        </div>
        
        {{-- Công cụ tìm kiếm & Filter --}}
        <div class="flex items-center gap-2">
            <form method="GET" class="flex items-center gap-2">
                <div class="relative w-full sm:w-auto">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                        <span class="material-icons-round text-lg">search</span>
                    </span>
                    <input name="search" value="{{ $currentSearch }}" 
                           class="w-full sm:w-64 pl-9 pr-4 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg bg-gray-50 dark:bg-gray-800 focus:ring-primary focus:border-primary dark:text-white" 
                           placeholder="Tìm tên, sản phẩm..." type="text"/>
                </div>
                <div class="relative">
                    <select name="rating" onchange="this.form.submit()" class="appearance-none bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 px-4 py-2 pr-8 rounded-lg shadow-sm hover:bg-gray-50 dark:hover:bg-gray-700 text-sm font-medium transition-colors">
                        <option value="">Tất cả sao</option>
                        <option value="5" {{ $currentRating == 5 ? 'selected' : '' }}>5 sao</option>
                        <option value="4" {{ $currentRating == 4 ? 'selected' : '' }}>4 sao</option>
                        <option value="3" {{ $currentRating == 3 ? 'selected' : '' }}>3 sao</option>
                        <option value="2" {{ $currentRating == 2 ? 'selected' : '' }}>2 sao</option>
                        <option value="1" {{ $currentRating == 1 ? 'selected' : '' }}>1 sao</option>
                    </select>
                    <span class="material-icons-round absolute right-2 top-1/2 transform -translate-y-1/2 text-gray-400 pointer-events-none">expand_more</span>
                </div>
                <input type="hidden" name="status" value="{{ $currentStatus }}">
            </form>
        </div>
    </div>
</div>
@endsection

@section('content')

{{-- Tabs Trạng thái --}}
<div class="mb-6 overflow-x-auto pb-2">
    <div class="flex gap-2">
        <a href="{{ request()->fullUrlWithQuery(['status' => 'all']) }}" 
           class="whitespace-nowrap px-4 py-1.5 rounded-full text-sm font-medium shadow-sm transition-colors {{ $currentStatus == 'all' ? 'bg-primary text-white' : 'bg-white dark:bg-gray-800 text-gray-600 dark:text-gray-300 border border-gray-200 dark:border-gray-700 hover:border-primary hover:text-primary' }}">
            Tất cả ({{ $counts['all'] }})
        </a>
        <a href="{{ request()->fullUrlWithQuery(['status' => 'pending']) }}" 
           class="whitespace-nowrap px-4 py-1.5 rounded-full text-sm font-medium transition-colors {{ $currentStatus == 'pending' ? 'bg-primary text-white' : 'bg-white dark:bg-gray-800 text-gray-600 dark:text-gray-300 border border-gray-200 dark:border-gray-700 hover:border-primary hover:text-primary' }}">
            Chờ duyệt ({{ $counts['pending'] }})
        </a>
        <a href="{{ request()->fullUrlWithQuery(['status' => 'approved']) }}" 
           class="whitespace-nowrap px-4 py-1.5 rounded-full text-sm font-medium transition-colors {{ $currentStatus == 'approved' ? 'bg-primary text-white' : 'bg-white dark:bg-gray-800 text-gray-600 dark:text-gray-300 border border-gray-200 dark:border-gray-700 hover:border-primary hover:text-primary' }}">
            Đã duyệt ({{ $counts['approved'] }})
        </a>
        <a href="{{ request()->fullUrlWithQuery(['rating' => 5]) }}" 
           class="whitespace-nowrap px-4 py-1.5 rounded-full text-sm font-medium transition-colors {{ $currentRating == 5 ? 'bg-primary text-white' : 'bg-white dark:bg-gray-800 text-gray-600 dark:text-gray-300 border border-gray-200 dark:border-gray-700 hover:border-primary hover:text-primary' }}">
            5 Sao ({{ $counts['five_star'] }})
        </a>
        <a href="{{ request()->fullUrlWithQuery(['with_images' => 1]) }}" 
           class="whitespace-nowrap px-4 py-1.5 rounded-full text-sm font-medium transition-colors {{ request('with_images') ? 'bg-primary text-white' : 'bg-white dark:bg-gray-800 text-gray-600 dark:text-gray-300 border border-gray-200 dark:border-gray-700 hover:border-primary hover:text-primary' }}">
            Có hình ảnh ({{ $counts['with_images'] }})
        </a>
    </div>
</div>

{{-- Danh sách đánh giá --}}
<div class="grid grid-cols-1 gap-4 pb-20">
    @forelse($reviews as $review)
    <div class="bg-surface-light dark:bg-surface-dark rounded-xl shadow-card border border-gray-200 dark:border-gray-700 p-4 sm:p-6 transition-all hover:shadow-md" data-review-id="{{ $review->id }}">
        <div class="flex justify-between items-start mb-4">
            <div class="flex items-center gap-3">
                @if($review->user->avatar)
                    <img alt="User Avatar" class="w-10 h-10 rounded-full object-cover" src="{{ asset('storage/avatars/' . $review->user->avatar) }}">
                @else
                    <div class="w-10 h-10 rounded-full bg-primary/20 text-primary flex items-center justify-center font-bold text-sm">
                        {{ strtoupper(substr($review->user->name, 0, 2)) }}
                    </div>
                @endif
                <div>
                    <h3 class="text-sm font-bold text-gray-900 dark:text-white">{{ $review->user->name }}</h3>
                    <p class="text-xs text-gray-500 dark:text-gray-400">{{ $review->formatted_created_at }}</p>
                </div>
            </div>
            <span class="px-2.5 py-1 rounded-full text-xs font-bold border
                @switch($review->status)
                    @case('pending') bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200 border-yellow-200 dark:border-yellow-800 @break
                    @case('approved') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200 border-green-200 dark:border-green-800 @break
                    @case('hidden') bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200 border-red-200 dark:border-red-800 @break
                @endswitch
            ">
                @switch($review->status)
                    @case('pending') Chờ duyệt @break
                    @case('approved') Đã duyệt @break
                    @case('hidden') Đã ẩn @break
                @endswitch
            </span>
        </div>
        
        {{-- Sản phẩm liên quan --}}
        <div class="flex items-center gap-3 bg-gray-50 dark:bg-gray-800/50 p-3 rounded-lg mb-4">
            @if($review->product->image && $review->product->image !== 'default.jpg')
                <img alt="Product Thumbnail" class="w-10 h-10 rounded-md object-cover bg-white" src="{{ asset('PRODUCT-IMG/' . $review->product->image) }}">
            @else
                <div class="w-10 h-10 rounded-md bg-gray-200 dark:bg-gray-700 flex items-center justify-center">
                    <span class="material-icons-round text-gray-400 text-sm">inventory_2</span>
                </div>
            @endif
            <div class="flex-1">
                <p class="text-[10px] text-gray-500 dark:text-gray-400 uppercase tracking-wide font-bold">Sản phẩm</p>
                <p class="text-sm font-bold text-primary cursor-pointer hover:underline">{{ $review->product->name }}</p>
            </div>
        </div>

        {{-- Nội dung đánh giá --}}
        <div class="mb-4">
            <div class="flex items-center gap-1 mb-2">
                @for($i = 1; $i <= 5; $i++)
                    <span class="material-icons-round text-lg {{ $i <= $review->rating ? 'text-yellow-400' : 'text-gray-300 dark:text-gray-600' }}">star</span>
                @endfor
            </div>
            @if($review->comment)
                <p class="text-gray-700 dark:text-gray-300 text-sm leading-relaxed mb-3">{{ $review->comment }}</p>
            @endif
            @if($review->images && count($review->images) > 0)
                <div class="flex gap-2 flex-wrap">
                    @foreach($review->image_urls as $imageUrl)
                        <img alt="Review Image" class="w-16 h-16 rounded-lg object-cover border border-gray-100 dark:border-gray-600 cursor-pointer hover:opacity-90 transition-opacity" 
                             src="{{ $imageUrl }}" onclick="openImageModal('{{ $imageUrl }}')">
                    @endforeach
                </div>
            @endif
        </div>

        {{-- Actions --}}
        <div class="flex items-center gap-3 pt-4 border-t border-gray-100 dark:border-gray-700">
            @if($review->isPending())
                <button onclick="approveReview({{ $review->id }})" class="flex-1 flex items-center justify-center gap-2 bg-green-50 dark:bg-green-900/20 text-green-700 dark:text-green-300 py-2 rounded-lg text-sm font-bold hover:bg-green-100 dark:hover:bg-green-900/40 transition-colors">
                    <span class="material-icons-round text-lg">check_circle</span> Duyệt
                </button>
                <button onclick="hideReview({{ $review->id }})" class="flex-1 flex items-center justify-center gap-2 bg-gray-50 dark:bg-gray-800 text-gray-600 dark:text-gray-400 py-2 rounded-lg text-sm font-bold hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                    <span class="material-icons-round text-lg">visibility_off</span> Ẩn
                </button>
            @elseif($review->isApproved())
                <button onclick="hideReview({{ $review->id }})" class="w-full flex items-center justify-center gap-2 bg-gray-50 dark:bg-gray-800 text-red-600 dark:text-red-400 py-2 rounded-lg text-sm font-bold hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors">
                    <span class="material-icons-round text-lg">visibility_off</span> Ẩn đánh giá này
                </button>
            @else
                <button onclick="approveReview({{ $review->id }})" class="w-full flex items-center justify-center gap-2 bg-green-50 dark:bg-green-900/20 text-green-700 dark:text-green-300 py-2 rounded-lg text-sm font-bold hover:bg-green-100 dark:hover:bg-green-900/40 transition-colors">
                    <span class="material-icons-round text-lg">check_circle</span> Hiển thị lại
                </button>
            @endif
        </div>
    </div>
    @empty
    <div class="text-center py-12">
        <span class="material-icons-round text-6xl text-gray-400 mb-4">rate_review</span>
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">Chưa có đánh giá nào</h3>
        <p class="text-gray-500 dark:text-gray-400">Đánh giá từ khách hàng sẽ hiển thị ở đây</p>
    </div>
    @endforelse
</div>

{{-- Pagination --}}
@if($reviews->hasPages())
<div class="flex items-center justify-center pb-8">
    {{ $reviews->appends(request()->query())->links() }}
</div>
@endif

{{-- Image Modal --}}
<div id="imageModal" class="fixed inset-0 bg-black/80 hidden items-center justify-center z-50" onclick="closeImageModal()">
    <div class="max-w-4xl max-h-[90vh] p-4">
        <img id="modalImage" src="" alt="Review Image" class="max-w-full max-h-full object-contain rounded-lg">
    </div>
</div>

@push('scripts')
<script>
// CSRF Token
const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

// Approve review
function approveReview(reviewId) {
    if (confirm('Bạn có chắc muốn duyệt đánh giá này?')) {
        fetch(`/admin/reviews/${reviewId}/approve`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showNotification(data.message, 'success');
                location.reload();
            } else {
                showNotification(data.message, 'error');
            }
        })
        .catch(error => {
            showNotification('Có lỗi xảy ra', 'error');
        });
    }
}

// Hide review
function hideReview(reviewId) {
    if (confirm('Bạn có chắc muốn ẩn đánh giá này?')) {
        fetch(`/admin/reviews/${reviewId}/hide`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showNotification(data.message, 'success');
                location.reload();
            } else {
                showNotification(data.message, 'error');
            }
        })
        .catch(error => {
            showNotification('Có lỗi xảy ra', 'error');
        });
    }
}

// Image modal
function openImageModal(imageUrl) {
    document.getElementById('modalImage').src = imageUrl;
    document.getElementById('imageModal').classList.remove('hidden');
    document.getElementById('imageModal').classList.add('flex');
}

function closeImageModal() {
    document.getElementById('imageModal').classList.add('hidden');
    document.getElementById('imageModal').classList.remove('flex');
}

// Notification
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
</script>
@endpush

@endsection