@extends('admin.layout')

@section('title', getSiteName() . ' - Quản lý sản phẩm số')

@php
    // Variables for header
    $pageTitle = 'Sản phẩm số';
    $pageHeading = 'Quản lý sản phẩm số';
    $pageDescription = 'Quản lý sản phẩm số';
    $createUrl = '#';
@endphp

@section('content')
<div class="p-6 max-w-6xl mx-auto">

    <!-- Page Header with Description -->
    <div class="mb-8">
        <p class="text-gray-600 dark:text-gray-400 text-sm">Quản lý tài liệu, video và quy trình gửi hàng tự động cho sản phẩm kỹ thuật số</p>
    </div>

    <!-- Add New Product Button -->
    <div class="mb-6">
        <button id="add-product-btn" class="inline-flex items-center px-4 py-2 bg-primary text-white font-medium rounded-lg hover:bg-primary-hover transition-colors">
            <span class="material-icons-round mr-2">add</span>
            Thêm sản phẩm số mới
        </button>
    </div>

    <!-- Products List -->
    <div class="bg-surface-light dark:bg-surface-dark rounded-xl shadow-sm border border-gray-100 dark:border-gray-800 overflow-hidden">
        <div class="p-6 border-b border-gray-100 dark:border-gray-800">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center gap-2">
                <span class="material-icons-round text-primary">inventory</span>
                Danh sách sản phẩm số
            </h2>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 dark:bg-gray-800/50">
                    <tr class="text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                        <th class="px-6 py-3">Sản phẩm</th>
                        <th class="px-6 py-3">Loại</th>
                        <th class="px-6 py-3">Giá</th>
                        <th class="px-6 py-3">Files</th>
                        <th class="px-6 py-3">Đã bán</th>
                        <th class="px-6 py-3">Trạng thái</th>
                        <th class="px-6 py-3">Hành động</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($products as $product)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-colors">
                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                <div class="w-12 h-12 rounded-lg bg-gray-200 dark:bg-gray-700 flex items-center justify-center mr-4">
                                    @if($product->thumbnail)
                                        <img src="{{ $product->thumbnail_url }}" alt="{{ $product->name }}" class="w-full h-full object-cover rounded-lg">
                                    @else
                                        <span class="material-icons-round text-gray-400">description</span>
                                    @endif
                                </div>
                                <div>
                                    <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $product->name }}</div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400">ID: #{{ $product->id }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                @switch($product->type)
                                    @case('course') bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300 @break
                                    @case('file') bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300 @break
                                    @default bg-purple-100 text-purple-800 dark:bg-purple-900/30 dark:text-purple-300
                                @endswitch
                            ">
                                @switch($product->type)
                                    @case('course') Khóa học @break
                                    @case('file') Tài liệu @break
                                    @default Link
                                @endswitch
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm font-medium text-gray-900 dark:text-white">
                            {{ $product->formatted_price }}
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400">
                            {{ count($product->files ?? []) }} files
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400">
                            {{ $product->purchases->count() }}
                        </td>
                        <td class="px-6 py-4">
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" class="sr-only peer toggle-active-btn" 
                                       data-id="{{ $product->id }}" 
                                       {{ $product->is_active ? 'checked' : '' }}>
                                <div class="w-9 h-5 bg-gray-200 peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-primary/30 dark:peer-focus:ring-primary/50 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all dark:border-gray-600 peer-checked:bg-primary"></div>
                            </label>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-2">
                                <button class="edit-product-btn p-2 text-gray-400 hover:text-primary transition-colors" 
                                        data-id="{{ $product->id }}" title="Chỉnh sửa">
                                    <span class="material-icons-round text-sm">edit</span>
                                </button>
                                <button class="manage-files-btn p-2 text-gray-400 hover:text-blue-500 transition-colors" 
                                        data-id="{{ $product->id }}" title="Quản lý files">
                                    <span class="material-icons-round text-sm">folder</span>
                                </button>
                                <button class="delete-product-btn p-2 text-gray-400 hover:text-red-500 transition-colors" 
                                        data-id="{{ $product->id }}" title="Xóa">
                                    <span class="material-icons-round text-sm">delete</span>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center gap-3">
                                <span class="material-icons-round text-4xl text-gray-400">inventory</span>
                                <p class="text-gray-500 dark:text-gray-400">Chưa có sản phẩm số nào</p>
                                <button id="add-first-product-btn" class="inline-flex items-center px-4 py-2 bg-primary text-white font-medium rounded-lg hover:bg-primary-hover transition-colors">
                                    <span class="material-icons-round mr-2">add</span>
                                    Tạo sản phẩm đầu tiên
                                </button>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($products->hasPages())
        <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700">
            {{ $products->links() }}
        </div>
        @endif
    </div>

</div>

<!-- Add/Edit Product Modal -->
<div id="product-modal" class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50">
    <div class="bg-white dark:bg-surface-dark rounded-xl w-full max-w-2xl mx-4 max-h-[90vh] overflow-y-auto">
        <div class="p-6 border-b border-gray-200 dark:border-gray-700">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white" id="modal-title">Thêm sản phẩm số mới</h3>
        </div>
        
        <form id="product-form" class="p-6 space-y-4" enctype="multipart/form-data">
            <input type="hidden" name="product_id" id="product-id">
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Tên sản phẩm</label>
                    <input type="text" name="name" id="product-name" required class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-surface-dark text-gray-900 dark:text-white focus:ring-2 focus:ring-primary/50 focus:border-primary">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Loại sản phẩm</label>
                    <select name="type" id="product-type" required class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-surface-dark text-gray-900 dark:text-white focus:ring-2 focus:ring-primary/50 focus:border-primary">
                        <option value="file">Tài liệu</option>
                        <option value="course">Khóa học</option>
                        <option value="link">Link</option>
                    </select>
                </div>
            </div>
            
            <!-- Thumbnail Upload -->
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Ảnh đại diện</label>
                <div class="flex items-center gap-4">
                    <div class="w-20 h-20 rounded-lg border-2 border-dashed border-gray-300 dark:border-gray-600 flex items-center justify-center bg-gray-50 dark:bg-gray-800 cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors" id="thumbnail-preview">
                        <span class="material-icons-round text-gray-400">add_photo_alternate</span>
                    </div>
                    <div class="flex-1">
                        <input type="file" name="thumbnail" id="thumbnail-input" accept="image/*" class="hidden">
                        <button type="button" onclick="document.getElementById('thumbnail-input').click()" class="px-4 py-2 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors">
                            Chọn ảnh
                        </button>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">JPG, PNG tối đa 2MB</p>
                    </div>
                </div>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Mô tả</label>
                <textarea name="description" id="product-description" rows="3" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-surface-dark text-gray-900 dark:text-white focus:ring-2 focus:ring-primary/50 focus:border-primary"></textarea>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Giá (VNĐ)</label>
                    <input type="number" name="price" id="product-price" required min="0" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-surface-dark text-gray-900 dark:text-white focus:ring-2 focus:ring-primary/50 focus:border-primary">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Giới hạn tải</label>
                    <input type="number" name="download_limit" id="product-download-limit" required min="1" value="3" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-surface-dark text-gray-900 dark:text-white focus:ring-2 focus:ring-primary/50 focus:border-primary">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Số ngày truy cập</label>
                    <input type="number" name="access_days" id="product-access-days" required min="1" value="30" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-surface-dark text-gray-900 dark:text-white focus:ring-2 focus:ring-primary/50 focus:border-primary">
                </div>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Hướng dẫn sử dụng</label>
                <textarea name="instructions" id="product-instructions" rows="3" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-surface-dark text-gray-900 dark:text-white focus:ring-2 focus:ring-primary/50 focus:border-primary"></textarea>
            </div>
            
            <div class="flex items-center gap-4">
                <label class="flex items-center">
                    <input type="checkbox" name="auto_send_email" id="product-auto-send" class="rounded border-gray-300 text-primary focus:ring-primary">
                    <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Tự động gửi email</span>
                </label>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Mẫu email</label>
                <textarea name="email_template" id="product-email-template" rows="4" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-surface-dark text-gray-900 dark:text-white focus:ring-2 focus:ring-primary/50 focus:border-primary" placeholder="Cảm ơn bạn đã mua hàng! Link tải: {download_link}"></textarea>
            </div>
        </form>
        
        <div class="p-6 border-t border-gray-200 dark:border-gray-700 flex justify-end gap-3">
            <button type="button" id="cancel-btn" class="px-4 py-2 text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800 rounded-lg transition-colors">
                Hủy
            </button>
            <button type="submit" form="product-form" class="px-4 py-2 bg-primary text-white rounded-lg hover:bg-primary-hover transition-colors">
                Lưu sản phẩm
            </button>
        </div>
    </div>
</div>

<!-- File Management Modal -->
<div id="files-modal" class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50">
    <div class="bg-white dark:bg-surface-dark rounded-xl w-full max-w-4xl mx-4 max-h-[90vh] overflow-y-auto">
        <div class="p-6 border-b border-gray-200 dark:border-gray-700">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Quản lý files sản phẩm</h3>
        </div>
        
        <div class="p-6">
            <!-- Upload Area -->
            <div class="border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-xl bg-gray-50 dark:bg-gray-800/50 p-8 mb-6 text-center">
                <input type="file" id="files-input" multiple accept=".pdf,.doc,.docx,.mp4,.avi,.mov" class="hidden">
                <div class="w-16 h-16 rounded-full bg-primary/10 flex items-center justify-center mb-4 mx-auto">
                    <span class="material-icons-round text-primary text-3xl">cloud_upload</span>
                </div>
                <p class="text-base font-semibold text-gray-900 dark:text-white mb-2">Kéo thả file hoặc click để chọn</p>
                <p class="text-sm text-gray-500 dark:text-gray-400 mb-2">Hỗ trợ: PDF, DOC, DOCX, MP4, AVI, MOV</p>
                <button type="button" onclick="document.getElementById('files-input').click()" class="px-4 py-2 bg-primary text-white rounded-lg hover:bg-primary-hover transition-colors">
                    Chọn files
                </button>
            </div>

            <!-- Add Link -->
            <div class="mb-6 p-4 bg-blue-50 dark:bg-blue-900/20 rounded-lg border border-blue-200 dark:border-blue-800">
                <h4 class="text-sm font-semibold text-blue-900 dark:text-blue-300 mb-2">Thêm link ngoài</h4>
                <div class="flex gap-2">
                    <input type="url" id="link-url" placeholder="https://drive.google.com/..." 
                           class="flex-1 px-3 py-2 text-sm border border-blue-300 dark:border-blue-700 rounded-lg bg-white dark:bg-surface-dark text-gray-900 dark:text-white focus:ring-2 focus:ring-primary/50 focus:border-primary">
                    <input type="text" id="link-name" placeholder="Tên hiển thị" 
                           class="w-32 px-3 py-2 text-sm border border-blue-300 dark:border-blue-700 rounded-lg bg-white dark:bg-surface-dark text-gray-900 dark:text-white focus:ring-2 focus:ring-primary/50 focus:border-primary">
                    <button type="button" id="add-link-btn" class="px-4 py-2 bg-primary text-white text-sm font-medium rounded-lg hover:bg-primary-hover transition-colors">
                        Thêm
                    </button>
                </div>
            </div>

            <!-- Files List -->
            <div id="product-files-list" class="space-y-3">
                <!-- Files will be loaded here -->
            </div>
        </div>
        
        <div class="p-6 border-t border-gray-200 dark:border-gray-700 flex justify-end">
            <button type="button" id="close-files-modal" class="px-4 py-2 text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800 rounded-lg transition-colors">
                Đóng
            </button>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('product-modal');
    const form = document.getElementById('product-form');
    const modalTitle = document.getElementById('modal-title');
    
    // Open modal for new product
    document.getElementById('add-product-btn').addEventListener('click', () => openModal());
    document.getElementById('add-first-product-btn')?.addEventListener('click', () => openModal());
    
    // Close modal
    document.getElementById('cancel-btn').addEventListener('click', closeModal);
    modal.addEventListener('click', (e) => {
        if (e.target === modal) closeModal();
    });
    
    // Form submit
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(form);
        
        fetch('/admin/digital-products', {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showNotification('Sản phẩm đã được tạo thành công!', 'success');
                closeModal();
                location.reload();
            } else {
                showNotification(data.message || 'Có lỗi xảy ra', 'error');
            }
        })
        .catch(error => {
            showNotification('Có lỗi xảy ra khi tạo sản phẩm', 'error');
        });
    });
    
    // Toggle active status
    document.addEventListener('change', function(e) {
        if (e.target.classList.contains('toggle-active-btn')) {
            const productId = e.target.dataset.id;
            
            fetch(`/admin/digital-products/${productId}/toggle-active`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showNotification(data.message, 'success');
                } else {
                    e.target.checked = !e.target.checked; // Revert
                    showNotification(data.message, 'error');
                }
            })
            .catch(error => {
                e.target.checked = !e.target.checked; // Revert
                showNotification('Có lỗi xảy ra', 'error');
            });
        }
    });
    
    // Delete product
    document.addEventListener('click', function(e) {
        if (e.target.closest('.delete-product-btn')) {
            const btn = e.target.closest('.delete-product-btn');
            const productId = btn.dataset.id;
            
            if (confirm('Bạn có chắc muốn xóa sản phẩm này? Tất cả file và dữ liệu liên quan sẽ bị xóa.')) {
                fetch('/admin/digital-products/delete', {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({ product_id: productId })
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
                    showNotification('Có lỗi xảy ra khi xóa sản phẩm', 'error');
                });
            }
        }
    });
    
    function openModal(product = null) {
        if (product) {
            modalTitle.textContent = 'Chỉnh sửa sản phẩm';
            // Fill form with product data
        } else {
            modalTitle.textContent = 'Thêm sản phẩm số mới';
            form.reset();
        }
        
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }
    
    function closeModal() {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
        form.reset();
    }
    
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

@endsection