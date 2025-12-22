@extends('layouts.app')

@section('title', 'Sản phẩm số - ' . getSiteName())

@section('content')
<div class="min-h-screen bg-gray-50 dark:bg-gray-900 py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Header -->
        <div class="text-center mb-12">
            <h1 class="text-4xl font-bold text-gray-900 dark:text-white mb-4">
                Sản phẩm số
            </h1>
            <p class="text-lg text-gray-600 dark:text-gray-400 max-w-2xl mx-auto">
                Khám phá bộ sưu tập tài liệu, khóa học và hướng dẫn kỹ thuật số của chúng tôi
            </p>
        </div>

        <!-- Products Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            @forelse($products as $product)
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden hover:shadow-lg transition-shadow group">
                
                <!-- Thumbnail -->
                <div class="aspect-video bg-gray-100 dark:bg-gray-700 relative overflow-hidden">
                    @if($product->thumbnail)
                        <img src="{{ $product->thumbnail_url }}" 
                             alt="{{ $product->name }}" 
                             class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                    @else
                        <div class="w-full h-full flex items-center justify-center">
                            <span class="material-icons-round text-4xl text-gray-400">
                                @switch($product->type)
                                    @case('course') school @break
                                    @case('file') description @break
                                    @default link
                                @endswitch
                            </span>
                        </div>
                    @endif
                    
                    <!-- Type Badge -->
                    <div class="absolute top-3 left-3">
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
                    </div>
                </div>

                <!-- Content -->
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2 line-clamp-2">
                        {{ $product->name }}
                    </h3>
                    
                    @if($product->description)
                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-4 line-clamp-3">
                        {{ $product->description }}
                    </p>
                    @endif

                    <!-- Price & Action -->
                    <div class="flex items-center justify-between">
                        <div class="text-2xl font-bold text-primary">
                            {{ $product->formatted_price }}
                        </div>
                        <a href="{{ route('digital-products.show', $product->id) }}" 
                           class="inline-flex items-center px-4 py-2 bg-primary text-white text-sm font-medium rounded-lg hover:bg-primary-hover transition-colors">
                            Xem chi tiết
                        </a>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-span-full text-center py-12">
                <span class="material-icons-round text-6xl text-gray-400 mb-4">inventory_2</span>
                <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">
                    Chưa có sản phẩm số nào
                </h3>
                <p class="text-gray-600 dark:text-gray-400">
                    Hãy quay lại sau để khám phá các sản phẩm mới
                </p>
            </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if($products->hasPages())
        <div class="mt-12 flex justify-center">
            {{ $products->links() }}
        </div>
        @endif

    </div>
</div>
@endsection