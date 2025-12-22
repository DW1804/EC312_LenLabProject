@extends('admin.layout')

@section('title', $siteName . ' - Cấu hình hệ thống')

@php
    // Variables for header
    $pageTitle = 'Cấu hình';
    $pageHeading = 'Cấu hình hệ thống';
    $pageDescription = 'Tùy chỉnh giao diện & Thông báo hệ thống';
    $createUrl = '#';
@endphp

@section('content')

{{-- Style riêng giữ nguyên --}}
<style>
    .toggle-checkbox:checked { right: 0; border-color: #D1A272; }
    .toggle-checkbox:checked + .toggle-label { background-color: #D1A272; }
    .toggle-checkbox:checked + .toggle-label .toggle-dot { transform: translateX(20px); }
    .toggle-dot { transition: transform 0.3s ease; }
</style>

{{-- FORM WRAPPER --}}
<form id="ui-config-form" enctype="multipart/form-data">
    @csrf
    
    {{-- NỘI DUNG CHÍNH --}}
    <div class="space-y-6 pb-24"> 

        {{-- Section 1: Giao diện --}}
        <section class="bg-surface-light dark:bg-surface-dark rounded-xl shadow-card border border-gray-100 dark:border-gray-800 p-6">
            <div class="border-b border-gray-100 dark:border-gray-800 pb-4 mb-6">
                <h3 class="text-lg font-bold flex items-center gap-2 text-gray-900 dark:text-white">
                    <span class="material-icons-round text-primary">palette</span>
                    Tùy chỉnh Giao diện
                </h3>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Thiết lập các yếu tố nhận diện thương hiệu</p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div class="space-y-6">
                    <div>
                        <label class="block text-sm font-semibold mb-2 text-gray-700 dark:text-gray-300">Tên Website</label>
                        <input 
                            id="site-name"
                            name="site_name"
                            class="w-full px-4 py-2.5 rounded-lg border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-surface-dark focus:ring-2 focus:ring-primary/50 focus:border-primary transition-all text-sm outline-none dark:text-white" 
                            placeholder="Lenlab Official" 
                            type="text"
                            value="{{ $settings['site_name'] ?? 'Lenlab Official' }}"
                            required
                        />
                    </div>
                    <div>
                        <label class="block text-sm font-semibold mb-2 text-gray-700 dark:text-gray-300">Màu sắc chủ đạo</label>
                        <div class="flex items-center gap-4 p-3 border border-gray-200 dark:border-gray-700 rounded-lg bg-gray-50 dark:bg-surface-dark">
                            <div id="color-preview" class="w-8 h-8 rounded-full border border-gray-200 shadow-sm" style="background-color: {{ $settings['primary_color'] ?? '#D1A272' }};"></div>
                            <div class="flex-1">
                                <p class="text-sm font-bold dark:text-white">Cam Đất (Terracotta)</p>
                                <p id="color-text" class="text-xs text-gray-500 font-mono">{{ strtoupper($settings['primary_color'] ?? '#D1A272') }}</p>
                            </div>
                            <button type="button" id="color-change-btn" class="text-xs font-semibold text-primary hover:bg-primary/10 px-3 py-1.5 rounded-md transition-colors">
                                Thay đổi
                            </button>
                        </div>
                        <input 
                            type="hidden" 
                            id="primary-color" 
                            name="primary_color" 
                            value="{{ $settings['primary_color'] ?? '#D1A272' }}"
                        />
                    </div>
                </div>
                <div class="space-y-6">
                    <div>
                        <label class="block text-sm font-semibold mb-2 text-gray-700 dark:text-gray-300">Logo Website</label>
                        <div class="border-2 border-dashed border-gray-300 dark:border-gray-700 rounded-xl p-6 flex flex-col items-center justify-center text-center hover:border-primary/50 transition-colors cursor-pointer bg-gray-50/50 dark:bg-surface-dark/50 relative">
                            <input type="file" id="logo-upload" name="logo" accept="image/*" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer">
                            <div id="logo-preview-container" class="w-full h-full min-h-[120px] flex items-center justify-center">
                                @if(isset($settings['logo_path']) && $settings['logo_path'])
                                    <div class="relative group w-full h-full">
                                        <img src="{{ Storage::url($settings['logo_path']) }}" alt="logo" class="w-full h-full object-contain rounded-lg">
                                        <div class="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 transition-opacity rounded-lg flex items-center justify-center">
                                            <button type="button" onclick="uiConfig.removeFile('logo')" class="text-white hover:text-red-300 transition-colors">
                                                <span class="material-icons-round">delete</span>
                                            </button>
                                        </div>
                                    </div>
                                @else
                                    <div class="text-center">
                                        <div class="w-16 h-16 bg-primary rounded-full flex items-center justify-center text-white mb-3 shadow-md mx-auto">
                                            <span class="material-icons-round text-3xl">gesture</span>
                                        </div>
                                        <p class="text-sm font-semibold text-primary">Tải lên logo mới</p>
                                        <p class="text-xs text-gray-400 mt-1">SVG, PNG, JPG (Max. 800x400px)</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold mb-2 text-gray-700 dark:text-gray-300">Favicon</label>
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 border-2 border-dashed border-gray-300 dark:border-gray-700 rounded-lg flex items-center justify-center bg-gray-50 dark:bg-surface-dark hover:border-primary/50 cursor-pointer relative">
                                <input type="file" id="favicon-upload" name="favicon" accept="image/png,image/x-icon,image/vnd.microsoft.icon" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer">
                                <div id="favicon-preview-container" class="w-full h-full flex items-center justify-center">
                                    @if(isset($settings['favicon_path']) && $settings['favicon_path'])
                                        <div class="relative group w-full h-full">
                                            <img src="{{ Storage::url($settings['favicon_path']) }}" alt="favicon" class="w-full h-full object-contain rounded-lg">
                                            <div class="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 transition-opacity rounded-lg flex items-center justify-center">
                                                <button type="button" onclick="uiConfig.removeFile('favicon')" class="text-white hover:text-red-300 transition-colors">
                                                    <span class="material-icons-round text-xs">delete</span>
                                                </button>
                                            </div>
                                        </div>
                                    @else
                                        <span class="material-icons-round text-gray-400">upload</span>
                                    @endif
                                </div>
                            </div>
                            <div class="text-xs text-gray-500 dark:text-gray-400">
                                <p>Kích thước đề xuất: 32x32px</p>
                                <p class="mt-0.5">Dùng làm biểu tượng trên tab trình duyệt</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        {{-- Section 2: Thông báo --}}
        <section class="bg-surface-light dark:bg-surface-dark rounded-xl shadow-card border border-gray-100 dark:border-gray-800 p-6">
            <div class="border-b border-gray-100 dark:border-gray-800 pb-4 mb-6">
                <h3 class="text-lg font-bold flex items-center gap-2 text-gray-900 dark:text-white">
                    <span class="material-icons-round text-primary">notifications_active</span>
                    Cấu hình Thông báo
                </h3>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Quản lý cách bạn nhận thông tin từ hệ thống</p>
            </div>
            <div class="space-y-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h4 class="text-sm font-bold text-gray-900 dark:text-white">Thông báo qua Email</h4>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Nhận đơn hàng mới qua email admin</p>
                    </div>
                    <label class="flex items-center cursor-pointer relative" for="toggle-email">
                        <input 
                            {{ ($settings['email_notifications'] ?? true) ? 'checked' : '' }}
                            class="sr-only toggle-checkbox" 
                            id="toggle-email" 
                            name="email_notifications"
                            type="checkbox"
                            value="1"
                        />
                        <div class="w-11 h-6 bg-gray-200 dark:bg-gray-700 rounded-full toggle-label transition-colors relative">
                            <div class="toggle-dot absolute left-1 top-1 bg-white w-4 h-4 rounded-full transition-transform transform"></div>
                        </div>
                    </label>
                </div>
                <div class="border-t border-gray-100 dark:border-gray-800"></div>
                <div class="flex items-center justify-between">
                    <div>
                        <h4 class="text-sm font-bold text-gray-900 dark:text-white">Thông báo Trình duyệt</h4>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Pop-up khi có tin nhắn mới</p>
                    </div>
                    <label class="flex items-center cursor-pointer relative" for="toggle-browser">
                        <input 
                            {{ ($settings['browser_notifications'] ?? false) ? 'checked' : '' }}
                            class="sr-only toggle-checkbox" 
                            id="toggle-browser" 
                            name="browser_notifications"
                            type="checkbox"
                            value="1"
                        />
                        <div class="w-11 h-6 bg-gray-200 dark:bg-gray-700 rounded-full toggle-label transition-colors relative">
                            <div class="toggle-dot absolute left-1 top-1 bg-white w-4 h-4 rounded-full transition-transform transform"></div>
                        </div>
                    </label>
                </div>
            </div>
        </section>

    </div>
</form>

{{-- THANH NÚT BẤM CỐ ĐỊNH Ở DƯỚI --}}
<div class="fixed bottom-0 right-0 left-0 md:left-64 p-4 bg-white/90 dark:bg-surface-dark/90 backdrop-blur-md border-t border-gray-200 dark:border-gray-700 z-30 flex items-center justify-end gap-3 shadow-[0_-4px_6px_-1px_rgba(0,0,0,0.1)]">
    <button type="button" id="cancel-btn" class="px-6 py-2.5 rounded-lg text-sm font-bold text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors">
        Hủy bỏ
    </button>
    <button type="submit" form="ui-config-form" id="submit-btn" class="px-6 py-2.5 rounded-lg text-sm font-bold text-white bg-primary hover:bg-primary-hover shadow-lg shadow-primary/30 transition-all active:scale-95">
        Lưu thay đổi
    </button>
</div>

{{-- Include JavaScript --}}
@push('scripts')
@vite('resources/js/ui-config.js')
@endpush

@endsection