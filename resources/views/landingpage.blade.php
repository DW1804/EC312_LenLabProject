<!DOCTYPE html>
<html class="dark" lang="vi">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ getSiteName() }} Homepage</title>
    
    {{-- Favicon --}}
    <link rel="icon" type="image/x-icon" href="{{ getFaviconUrl() }}">
    
    {{-- Dynamic CSS from settings --}}
    <style>
        {!! getDynamicCss() !!}
    </style>
    <link href="https://fonts.googleapis.com/css2?family=Spline+Sans:wght@300;400;500;600;700&family=Noto+Sans:wght@400;500;700&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        "primary": "#FAC638", "background-light": "#f8f8f5", "background-dark": "#231e0f", "surface-dark": "#1c2e24", "surface-light": "#ffffff", "pastel-orange": "#FFCCBC", "pastel-orange-dark": "#E64A19"
                    },
                    fontFamily: {
                        "display": ["Spline Sans", "sans-serif"],
                        "body": ["Noto Sans", "sans-serif"]
                    },
                    borderRadius: {
                        "DEFAULT": "1rem", 
                        "lg": "1.5rem", 
                        "xl": "2rem", 
                        "2xl": "2.5rem",
                        "full": "9999px"
                    },
                },
            },
        }
    </script>
    <style>
        .hide-scrollbar::-webkit-scrollbar {display: none;}
        .hide-scrollbar {-ms-overflow-style: none; scrollbar-width: none;}
        body {min-height: max(884px, 100dvh);}
        
        /* Custom animations */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        @keyframes fadeInLeft {
            from {
                opacity: 0;
                transform: translateX(-30px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }
        
        @keyframes fadeInRight {
            from {
                opacity: 0;
                transform: translateX(30px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }
        
        @keyframes scaleIn {
            from {
                opacity: 0;
                transform: scale(0.8);
            }
            to {
                opacity: 1;
                transform: scale(1);
            }
        }
        
        @keyframes float {
            0%, 100% {
                transform: translateY(0px);
            }
            50% {
                transform: translateY(-10px);
            }
        }
        
        @keyframes glow {
            0%, 100% {
                box-shadow: 0 0 5px rgba(250, 198, 56, 0.2);
            }
            50% {
                box-shadow: 0 0 20px rgba(250, 198, 56, 0.4), 0 0 30px rgba(250, 198, 56, 0.2);
            }
        }
        
        /* Animation classes */
        .animate-in {
            animation: fadeInUp 0.6s ease-out forwards;
        }
        
        .animate-out {
            opacity: 0.7;
            transform: translateY(10px);
            transition: all 0.3s ease-out;
        }
        
        .animate-float {
            animation: float 3s ease-in-out infinite;
        }
        
        .animate-glow {
            animation: glow 2s ease-in-out infinite;
        }
        
        /* Hover effects */
        .hover-lift {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        .hover-lift:hover {
            transform: translateY(-8px) scale(1.02);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        }
        
        .hover-glow:hover {
            box-shadow: 0 0 20px rgba(250, 198, 56, 0.3);
        }
        
        /* Product card enhancements */
        .product-item {
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            transform-origin: center;
        }
        
        .product-item:hover {
            transform: translateY(-8px) scale(1.03);
            box-shadow: 0 25px 50px rgba(250, 198, 56, 0.15);
        }
        
        .product-item:hover .add-to-cart-btn {
            transform: translateY(0) scale(1.1);
            opacity: 1;
        }
        
        /* Category item enhancements */
        .category-item {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        .category-item:hover {
            transform: translateY(-4px);
        }
        
        .category-item:hover .material-symbols-outlined {
            transform: scale(1.2) rotate(5deg);
            color: #FAC638;
        }
        
        /* Button enhancements */
        .group\/btn {
            position: relative;
            overflow: hidden;
        }
        
        .group\/btn::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 0;
            height: 0;
            background: radial-gradient(circle, rgba(255,255,255,0.3) 0%, transparent 70%);
            transition: all 0.5s ease;
            transform: translate(-50%, -50%);
            border-radius: 50%;
        }
        
        .group\/btn:hover::before {
            width: 300px;
            height: 300px;
        }
        
        /* Scroll indicators */
        .scroll-indicator {
            position: fixed;
            top: 0;
            left: 0;
            height: 4px;
            background: linear-gradient(90deg, #FAC638, #f59e0b);
            z-index: 9999;
            transition: width 0.3s ease;
        }
        
        /* Mobile menu enhancements */
        
        /* Login Popup Styles */
        #loginPopup {
            backdrop-filter: blur(8px);
            animation: fadeIn 0.3s ease-out;
        }
        
        #loginPopupContent {
            animation: slideUp 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
        }
        
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        
        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(30px) scale(0.9);
            }
            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }
        
        .popup-hero-image {
            background: linear-gradient(135deg, rgba(35, 30, 15, 0.8), rgba(42, 35, 24, 0.6)),
                        url('https://images.unsplash.com/photo-1586281010691-3d8b8c0e5b8d?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1000&q=80');
            background-size: cover;
            background-position: center;
        }
        .menu-dropdown .material-symbols-outlined {
            transition: transform 0.2s ease;
        }
        
        .menu-dropdown .rotate-180 {
            transform: rotate(180deg);
        }
        
        /* Loading states */
        .loading-shimmer {
            background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
            background-size: 200% 100%;
            animation: shimmer 1.5s infinite;
        }
        
        @keyframes shimmer {
            0% {
                background-position: -200% 0;
            }
            100% {
                background-position: 200% 0;
            }
        }
        
        /* Dark mode specific animations */
        .dark .loading-shimmer {
            background: linear-gradient(90deg, #2a2a2a 25%, #3a3a3a 50%, #2a2a2a 75%);
            background-size: 200% 100%;
        }
        
        /* Responsive animations */
        @media (max-width: 768px) {
            .animate-in {
                animation: fadeInUp 0.4s ease-out forwards;
            }
            
            .product-item:hover {
                transform: translateY(-4px) scale(1.02);
            }
        }
        
        /* Ripple effect */
        .ripple {
            position: absolute;
            border-radius: 50%;
            background: rgba(250, 198, 56, 0.3);
            transform: scale(0);
            animation: ripple-animation 0.6s linear;
            pointer-events: none;
        }
        
        @keyframes ripple-animation {
            to {
                transform: scale(4);
                opacity: 0;
            }
        }
        
        /* Page loading states */
        body.loading {
            overflow: hidden;
        }
        
        body.loading::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, #112117, #1c2e24);
            z-index: 9999;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        body.loading::after {
            content: '{{ getSiteName() }}';
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            font-size: 2rem;
            font-weight: bold;
            color: #FAC638;
            z-index: 10000;
            animation: pulse 1.5s ease-in-out infinite;
        }
        
        body.loaded {
            animation: fadeIn 0.5s ease-out;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        
        /* Magnetic button effect */
        .group\/btn, .add-to-cart-btn {
            transition: transform 0.2s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
        }
        
        /* Enhanced hover states */
        .category-item:hover {
            background: rgba(250, 198, 56, 0.05);
        }
        
        .product-item:hover .product-image img {
            transform: scale(1.1);
        }
        
        .product-item .product-image img {
            transition: transform 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        /* Floating background elements */
        .floating-element {
            position: fixed;
            pointer-events: none;
            z-index: 1;
        }
        
        /* Enhanced scroll animations */
        .animate-on-load {
            opacity: 0;
            transform: translateY(30px);
            transition: all 0.6s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        .animate-on-load.animate-in {
            opacity: 1;
            transform: translateY(0);
        }
        
        /* Mobile menu specific animations */
        #mobileMenuPanel {
            backdrop-filter: blur(10px);
        }
        
        #mobileMenuPanel .menu-dropdown button:hover {
            background: rgba(255, 255, 255, 0.1);
        }
        
        #mobileMenuPanel nav a:hover {
            background: rgba(255, 255, 255, 0.1);
            transform: translateX(8px);
        }
        
        /* Accessibility */
        @media (prefers-reduced-motion: reduce) {
            * {
                animation-duration: 0.01ms !important;
                animation-iteration-count: 1 !important;
                transition-duration: 0.01ms !important;
            }
            
            .animate-float {
                animation: none;
            }
            
            .ripple {
                display: none;
            }
        }
        
        /* High contrast mode */
        @media (prefers-contrast: high) {
            .product-item:hover {
                border: 2px solid #FAC638;
            }
            
            .category-item:hover {
                border: 2px solid #FAC638;
            }
        }
    </style>
</head>
<body class="bg-background-light dark:bg-background-dark font-display min-h-screen flex flex-col antialiased selection:bg-primary selection:text-background-dark">

<!-- Scroll Progress Indicator -->
<div class="scroll-indicator" id="scrollProgress"></div>

<!-- Header -->
<header class="sticky top-0 z-50 w-full bg-background-light/90 dark:bg-background-dark/90 backdrop-blur-md border-b border-gray-200 dark:border-white/5 transition-all duration-300">
    <div class="px-4 py-3 flex items-center justify-between gap-3">
        <div class="flex items-center gap-3">
            <button class="flex items-center justify-center size-10 rounded-full hover:bg-black/5 dark:hover:bg-white/10 transition-colors" id="mobileMenuBtn">
                <span class="material-symbols-outlined text-gray-800 dark:text-white">menu</span>
            </button>
            <div class="hidden sm:block text-2xl font-bold tracking-tight text-gray-900 dark:text-white">{{ getSiteName() }}</div>
            <div class="sm:hidden text-xl font-bold tracking-tight text-gray-900 dark:text-white">{{ getSiteName() }}</div>
        </div>
        <div class="flex-1 max-w-md mx-2">
            <div class="relative group">
                <span class="absolute inset-y-0 left-3 flex items-center pointer-events-none">
                    <span class="material-symbols-outlined text-gray-400 dark:text-gray-500 text-[20px]">search</span>
                </span>
                <input class="w-full py-2 pl-10 pr-4 bg-gray-100 dark:bg-surface-dark text-gray-900 dark:text-white rounded-full border-none text-sm focus:ring-2 focus:ring-primary/50 placeholder:text-gray-400 dark:placeholder:text-gray-600 transition-all shadow-sm" placeholder="Tìm kiếm..." type="text" id="searchInput"/>
            </div>
        </div>
        <div class="flex items-center gap-1">
            <button class="relative flex items-center justify-center size-10 rounded-full hover:bg-black/5 dark:hover:bg-white/10 transition-colors" onclick="window.location.href='/cart'">
                <span class="material-symbols-outlined text-gray-800 dark:text-white">shopping_bag</span>
                <span class="absolute top-2 right-2 flex h-2 w-2">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-primary opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-2 w-2 bg-primary" id="cartIndicator"></span>
                </span>
            </button>
            @auth
                <button class="flex items-center justify-center size-10 rounded-full hover:bg-black/5 dark:hover:bg-white/10 transition-colors" onclick="window.location.href='/profile'">
                    <span class="material-symbols-outlined text-gray-800 dark:text-white">account_circle</span>
                </button>
            @else
                <button class="flex items-center justify-center size-10 rounded-full hover:bg-black/5 dark:hover:bg-white/10 transition-colors" onclick="showLoginPopup()">
                    <span class="material-symbols-outlined text-gray-800 dark:text-white">account_circle</span>
                </button>
            @endauth
        </div>
    </div>
</header>

<!-- Main Content -->
<main class="flex-grow flex flex-col gap-8 pb-12">
    <!-- Hero Section -->
    <section class="relative px-4 pt-4">
        <div class="relative w-full h-[500px] rounded-2xl overflow-hidden shadow-2xl group">
            <div class="absolute inset-0 bg-cover bg-center transition-transform duration-700 group-hover:scale-105" style="background-image: url('{{ asset('banner.png') }}')"></div>
            <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/40 to-transparent"></div>
            <div class="absolute inset-0 flex flex-col items-center justify-end pb-12 px-6 text-center z-10">
                <p class="text-gray-300 text-sm md:text-lg mb-8 max-w-md font-light">Khám phá bộ sưu tập len thủ công độc đáo, mang lại sự ấm áp và phong cách cho cuộc sống của bạn.</p>
                <button class="group/btn relative inline-flex items-center justify-center px-8 py-3.5 bg-primary text-background-dark text-base font-bold rounded-full overflow-hidden transition-all hover:shadow-[0_0_20px_rgba(54,226,123,0.4)] hover:scale-105 active:scale-95" onclick="window.location.href='/san-pham'">
                    <span class="relative z-10 flex items-center gap-2">Khám phá sản phẩm
                        <span class="material-symbols-outlined text-[20px] transition-transform group-hover/btn:translate-x-1">arrow_forward</span>
                    </span>
                    <div class="absolute inset-0 bg-white/20 translate-y-full group-hover/btn:translate-y-0 transition-transform duration-300"></div>
                </button>
            </div>
        </div>
    </section>

    <!-- Categories Section -->
    <section class="px-4">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-xl font-bold text-gray-900 dark:text-white tracking-tight">Danh mục</h2>
        </div>
        <div class="flex overflow-x-auto gap-3 pb-2 hide-scrollbar snap-x" id="categoriesContainer">
            <!-- Categories will be loaded here -->
        </div>
    </section>

    <!-- Featured Products Section -->
    <section class="px-4 flex flex-col gap-4">
        <div class="flex items-center justify-between">
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white tracking-tight">Sản phẩm nổi bật</h2>
            <a class="text-sm font-medium text-primary hover:text-primary/80 flex items-center gap-1 transition-colors" href="/san-pham">
                Xem tất cả <span class="material-symbols-outlined text-[16px]">chevron_right</span>
            </a>
        </div>
        <div class="grid grid-cols-2 gap-4" id="featuredProducts">
            <!-- Featured products will be loaded here -->
        </div>
    </section>

    <!-- Winter Campaign Section -->
    <section class="px-4 py-2">
        <div class="relative w-full rounded-2xl overflow-hidden bg-primary/20">
            <div class="absolute inset-0 opacity-20 bg-[radial-gradient(#FAC638_1px,transparent_1px)] [background-size:16px_16px]"></div>
            <div class="relative flex flex-row items-center justify-between p-6">
                <div class="flex flex-col gap-2 max-w-[60%]">
                    <span class="inline-block px-2 py-0.5 w-fit rounded bg-primary text-background-dark text-[10px] font-bold uppercase tracking-wider">Campaign</span>
                    <h2 class="text-2xl font-black text-gray-900 dark:text-white uppercase leading-none">Mùa đông <br/><span class="text-primary">Ấm áp</span></h2>
                    <p class="text-xs text-gray-600 dark:text-gray-300 mt-1">Giảm 15% cho đơn hàng đầu tiên.</p>
                </div>
                <div class="h-24 w-24 flex-shrink-0 rotate-12 rounded-xl overflow-hidden border-2 border-white/20 shadow-lg">
                    <img alt="Cozy winter setup with scarf and coffee" class="h-full w-full object-cover" src="{{ asset('winter-campaign.jpg') }}"/>
                </div>
            </div>
        </div>
    </section>

    <!-- Blog Section -->
    <section class="flex flex-col gap-4">
        <div class="px-4 flex items-center justify-between">
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white tracking-tight">Bài viết mới</h2>
        </div>
        <div class="flex overflow-x-auto gap-4 px-4 pb-4 hide-scrollbar snap-x">
            <article class="flex-none w-[280px] snap-center flex flex-col gap-3">
                <div class="aspect-video w-full rounded-xl overflow-hidden bg-gray-200 dark:bg-gray-800">
                    <img alt="Woman knitting with large wool yarn" class="h-full w-full object-cover hover:scale-105 transition-transform duration-500" src="{{ asset('blog1.jpg') }}"/>
                </div>
                <div class="flex flex-col gap-1">
                    <div class="flex items-center gap-2 text-[10px] text-gray-500 uppercase font-bold tracking-wider">
                        <span class="text-primary">Tips</span><span>•</span><span>12 T10, 2023</span>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white leading-tight">Cách bảo quản len bền đẹp như mới</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400 line-clamp-2">Hướng dẫn chi tiết cách giặt và phơi đồ len để không bị bai dão...</p>
                </div>
            </article>
            <article class="flex-none w-[280px] snap-center flex flex-col gap-3">
                <div class="aspect-video w-full rounded-xl overflow-hidden bg-gray-200 dark:bg-gray-800">
                    <img alt="Assorted colorful wool balls" class="h-full w-full object-cover hover:scale-105 transition-transform duration-500" src="{{ asset('blog2.jpg') }}"/>
                </div>
                <div class="flex flex-col gap-1">
                    <div class="flex items-center gap-2 text-[10px] text-gray-500 uppercase font-bold tracking-wider">
                        <span class="text-primary">Trend</span><span>•</span><span>05 T10, 2023</span>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white leading-tight">Xu hướng màu sắc Thu Đông 2025</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400 line-clamp-2">Những tông màu ấm áp dự kiến sẽ lên ngôi trong mùa lạnh năm nay.</p>
                </div>
            </article>
        </div>
    </section>
</main>

<!-- Footer -->
<footer class="bg-white dark:bg-[#0d140f] border-t border-gray-200 dark:border-white/5 pt-12 pb-8 px-4 mt-auto">
    <div class="flex flex-col gap-8">
        <div class="flex flex-col gap-4">
            <div class="text-2xl font-black tracking-tight text-gray-900 dark:text-white">{{ getSiteName() }}</div>
            <p class="text-sm text-gray-500 dark:text-gray-400 max-w-xs">Thương hiệu đồ len thủ công hàng đầu, mang đến sự ấm áp và phong cách sống bền vững cho bạn.</p>
        </div>
        <div class="grid grid-cols-2 gap-8">
            <div class="flex flex-col gap-3">
                <h4 class="text-sm font-bold text-gray-900 dark:text-white uppercase tracking-wider">Khám phá</h4>
                <ul class="flex flex-col gap-2 text-sm text-gray-500 dark:text-gray-400">
                    <li><a class="hover:text-primary transition-colors" href="/gioi-thieu">Về chúng tôi</a></li>
                    <li><a class="hover:text-primary transition-colors" href="/san-pham">Sản phẩm</a></li>
                    <li><a class="hover:text-primary transition-colors" href="#">Blog</a></li>
                    <li><a class="hover:text-primary transition-colors" href="#">Liên hệ</a></li>
                </ul>
            </div>
            <div class="flex flex-col gap-3">
                <h4 class="text-sm font-bold text-gray-900 dark:text-white uppercase tracking-wider">Hỗ trợ</h4>
                <ul class="flex flex-col gap-2 text-sm text-gray-500 dark:text-gray-400">
                    <li><a class="hover:text-primary transition-colors" href="#">Chính sách đổi trả</a></li>
                    <li><a class="hover:text-primary transition-colors" href="#">Hướng dẫn chọn size</a></li>
                    <li><a class="hover:text-primary transition-colors" href="#">Bảo mật thông tin</a></li>
                </ul>
            </div>
        </div>
        <div class="flex flex-col gap-4 border-t border-gray-200 dark:border-white/5 pt-8">
            <div class="flex items-center gap-4">
                <a class="flex items-center justify-center h-10 w-10 rounded-full bg-gray-100 dark:bg-surface-dark text-gray-600 dark:text-gray-400 hover:bg-primary hover:text-background-dark transition-all" href="#">
                    <svg class="w-5 h-5 fill-current" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"></path></svg>
                </a>
                <a class="flex items-center justify-center h-10 w-10 rounded-full bg-gray-100 dark:bg-surface-dark text-gray-600 dark:text-gray-400 hover:bg-primary hover:text-background-dark transition-all" href="#">
                    <svg class="w-5 h-5 fill-current" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"></path></svg>
                </a>
            </div>
            <div class="text-xs text-gray-400 dark:text-gray-500">© 2023 {{ getSiteName() }}. All rights reserved.</div>
        </div>
    </div>
</footer>


<!-- Scripts -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    // Load categories from database
    function loadCategories() {
        $.get('/api/categories', function(response) {
            if (response.success && response.categories) {
                let html = '';
                response.categories.forEach(category => {
                    const href = category.url || `/san-pham?search=${encodeURIComponent(category.keyword)}`;
                    html += `
                        <a class="flex-none w-24 flex flex-col items-center gap-2 snap-start group category-item" href="${href}" title="${category.description}">
                            <div class="w-20 h-20 rounded-[1.5rem] bg-[#FFF3E0] dark:bg-[#3E2723] flex items-center justify-center transition-transform duration-300 group-hover:scale-105 group-hover:shadow-md border border-orange-100 dark:border-white/5">
                                <span class="material-symbols-outlined text-3xl text-orange-400">${category.icon}</span>
                            </div>
                            <span class="text-xs font-semibold text-center text-gray-700 dark:text-gray-300 line-clamp-2 leading-tight">${category.name}</span>
                        </a>
                    `;
                });
                $('#categoriesContainer').html(html);
            }
        }).fail(function() {
            // Fallback categories
            const fallbackCategories = [
                { name: 'Nguyên phụ liệu', icon: 'inventory_2', keyword: 'Nguyên phụ liệu' },
                { name: 'Đồ trang trí', icon: 'potted_plant', keyword: 'Đồ trang trí' },
                { name: 'Thời trang len', icon: 'checkroom', keyword: 'Thời trang len' },
                { name: 'Sản phẩm số', icon: 'download', keyword: 'digital', url: '/san-pham-so' },
                { name: 'Combo tiết kiệm', icon: 'savings', keyword: 'Combo tự làm' },
                { name: 'Thú bông len', icon: 'pets', keyword: 'Thú bông' }
            ];
            
            let html = '';
            fallbackCategories.forEach(category => {
                const href = category.url || `/san-pham?search=${encodeURIComponent(category.keyword)}`;
                html += `
                    <a class="flex-none w-24 flex flex-col items-center gap-2 snap-start group category-item" href="${href}">
                        <div class="w-20 h-20 rounded-[1.5rem] bg-[#FFF3E0] dark:bg-[#3E2723] flex items-center justify-center transition-transform duration-300 group-hover:scale-105 group-hover:shadow-md border border-orange-100 dark:border-white/5">
                            <span class="material-symbols-outlined text-3xl text-orange-400">${category.icon}</span>
                        </div>
                        <span class="text-xs font-semibold text-center text-gray-700 dark:text-gray-300 line-clamp-2 leading-tight">${category.name}</span>
                    </a>
                `;
            });
            $('#categoriesContainer').html(html);
        });
    }
    
    // Load featured products from database
    function loadFeaturedProducts() {
        $.get('/api/landing/products', function(response) {
            let html = '';
            const products = response.products.slice(0, 4);
            
            products.forEach((item, index) => {
                const isOnSale = index === 3; // Make last product on sale
                const isBestSeller = index === 0; // Make first product best seller
                
                html += `
                    <div class="group relative flex flex-col gap-3 product-item" data-product-id="${item.id}">
                        <div class="relative aspect-[3/4] w-full overflow-hidden rounded-2xl bg-gray-200 dark:bg-gray-800 cursor-pointer">
                            ${item.image ? 
                                `<img alt="${item.name}" class="h-full w-full object-cover object-center transition-transform duration-500 group-hover:scale-110" src="/product-img/${item.image}"/>` :
                                `<div class="h-full w-full flex items-center justify-center bg-gradient-to-br from-gray-200 to-gray-300 dark:from-gray-700 dark:to-gray-800">
                                    <span class="material-symbols-outlined text-4xl text-gray-400">image</span>
                                </div>`
                            }
                            <button class="absolute bottom-3 right-3 flex h-10 w-10 items-center justify-center rounded-full bg-white/90 text-gray-900 shadow-lg backdrop-blur hover:bg-primary hover:text-background-dark transition-all translate-y-4 opacity-0 group-hover:translate-y-0 group-hover:opacity-100 add-to-cart-btn" data-product-id="${item.id}">
                                <span class="material-symbols-outlined text-[20px]">add_shopping_cart</span>
                            </button>
                            ${isBestSeller ? '<div class="absolute top-3 left-3 px-2 py-1 bg-black/60 backdrop-blur-md rounded-lg text-[10px] font-bold text-white uppercase tracking-wide">Best Seller</div>' : ''}
                            ${isOnSale ? '<div class="absolute top-3 left-3 px-2 py-1 bg-red-500/90 backdrop-blur-md rounded-lg text-[10px] font-bold text-white uppercase tracking-wide">-20%</div>' : ''}
                        </div>
                        <div class="flex flex-col gap-1">
                            <h3 class="text-base font-semibold text-gray-900 dark:text-white line-clamp-1">${item.name}</h3>
                            ${isOnSale ? 
                                `<div class="flex items-center gap-2">
                                    <p class="text-sm font-bold text-primary">${(item.price * 0.8).toLocaleString('vi-VN')}₫</p>
                                    <p class="text-xs text-gray-500 line-through">${item.price ? item.price.toLocaleString('vi-VN') + '₫' : 'Liên hệ'}</p>
                                </div>` :
                                `<p class="text-sm font-bold text-primary">${item.price ? item.price.toLocaleString('vi-VN') + '₫' : 'Liên hệ'}</p>`
                            }
                        </div>
                    </div>
                `;
            });
            
            $('#featuredProducts').html(html);
        }).fail(function() {
            // Fallback products
            const fallbackProducts = [
                { id: 1, name: 'Áo Len Cổ Lọ Handknit', price: 850000, image: null },
                { id: 2, name: 'Mũ Len Beanie Basic', price: 320000, image: null },
                { id: 3, name: 'Tất Len Cổ Cao', price: 150000, image: null },
                { id: 4, name: 'Túi Tote Crochet Hoa', price: 600000, image: null }
            ];
            
            let html = '';
            fallbackProducts.forEach((item, index) => {
                const isOnSale = index === 3;
                const isBestSeller = index === 0;
                
                html += `
                    <div class="group relative flex flex-col gap-3 product-item" data-product-id="${item.id}">
                        <div class="relative aspect-[3/4] w-full overflow-hidden rounded-2xl bg-gray-200 dark:bg-gray-800 cursor-pointer">
                            <div class="h-full w-full flex items-center justify-center bg-gradient-to-br from-gray-200 to-gray-300 dark:from-gray-700 dark:to-gray-800">
                                <span class="material-symbols-outlined text-4xl text-gray-400">image</span>
                            </div>
                            <button class="absolute bottom-3 right-3 flex h-10 w-10 items-center justify-center rounded-full bg-white/90 text-gray-900 shadow-lg backdrop-blur hover:bg-primary hover:text-background-dark transition-all translate-y-4 opacity-0 group-hover:translate-y-0 group-hover:opacity-100 add-to-cart-btn" data-product-id="${item.id}">
                                <span class="material-symbols-outlined text-[20px]">add_shopping_cart</span>
                            </button>
                            ${isBestSeller ? '<div class="absolute top-3 left-3 px-2 py-1 bg-black/60 backdrop-blur-md rounded-lg text-[10px] font-bold text-white uppercase tracking-wide">Best Seller</div>' : ''}
                            ${isOnSale ? '<div class="absolute top-3 left-3 px-2 py-1 bg-red-500/90 backdrop-blur-md rounded-lg text-[10px] font-bold text-white uppercase tracking-wide">-20%</div>' : ''}
                        </div>
                        <div class="flex flex-col gap-1">
                            <h3 class="text-base font-semibold text-gray-900 dark:text-white line-clamp-1">${item.name}</h3>
                            ${isOnSale ? 
                                `<div class="flex items-center gap-2">
                                    <p class="text-sm font-bold text-primary">${(item.price * 0.8).toLocaleString('vi-VN')}₫</p>
                                    <p class="text-xs text-gray-500 line-through">${item.price.toLocaleString('vi-VN')}₫</p>
                                </div>` :
                                `<p class="text-sm font-bold text-primary">${item.price.toLocaleString('vi-VN')}₫</p>`
                            }
                        </div>
                    </div>
                `;
            });
            
            $('#featuredProducts').html(html);
        });
    }
    
    // Search functionality
    $('#searchInput').on('keypress', function(e) {
        if (e.which === 13) {
            const keyword = $(this).val().trim();
            if (keyword) {
                window.location.href = `/san-pham?search=${encodeURIComponent(keyword)}`;
            }
        }
    });
    
    // Product click handler
    $(document).on('click', '.product-item', function(e) {
        if (!$(e.target).hasClass('add-to-cart-btn') && !$(e.target).closest('.add-to-cart-btn').length) {
            const productId = $(this).data('product-id');
            if (productId) {
                window.location.href = `/san-pham/${productId}`;
            }
        }
    });
    
    // Add to cart functionality
    $(document).on('click', '.add-to-cart-btn', function(e) {
        e.stopPropagation();
        const productId = $(this).data('product-id');
        
        // Add loading state
        $(this).html('<span class="material-symbols-outlined text-[20px] animate-spin">refresh</span>');
        
        $.ajax({
            url: '/api/cart/add',
            method: 'POST',
            data: {
                product_id: productId,
                quantity: 1,
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                // Reset button
                $('.add-to-cart-btn').html('<span class="material-symbols-outlined text-[20px]">add_shopping_cart</span>');
                
                // Show success feedback
                $('#cartIndicator').addClass('animate-pulse');
                setTimeout(() => {
                    $('#cartIndicator').removeClass('animate-pulse');
                }, 1000);
                
                // Update cart count if needed
                updateCartCount();
            },
            error: function() {
                // Reset button
                $('.add-to-cart-btn').html('<span class="material-symbols-outlined text-[20px]">add_shopping_cart</span>');
                alert('Có lỗi xảy ra khi thêm sản phẩm vào giỏ hàng');
            }
        });
    });
    
    // Update cart count
    function updateCartCount() {
        @auth
            $.get('/api/cart', function(response) {
                const count = response.cart.reduce((sum, item) => sum + item.quantity, 0);
                if (count > 0) {
                    $('#cartIndicator').show();
                } else {
                    $('#cartIndicator').hide();
                }
            }).fail(function() {
                $('#cartIndicator').hide();
            });
        @else
            $('#cartIndicator').hide();
        @endauth
    }
    
    // Mobile menu functionality
    $('#mobileMenuBtn').click(function() {
        let menuHtml = `
            <div class="fixed inset-0 bg-black/60 z-50 backdrop-blur-sm" id="mobileMenuOverlay">
                <div class="fixed left-0 top-0 h-full w-80 bg-gradient-to-b from-[#2a2318] to-[#1a1610] shadow-2xl transform -translate-x-full transition-transform duration-300 ease-out" id="mobileMenuPanel">
                    <!-- Header -->
                    <div class="flex justify-between items-center p-6 border-b border-primary/20">
                        <h3 class="text-xl font-bold text-white">Menu</h3>
                        <button id="closeMobileMenu" class="p-2 hover:bg-white/10 rounded-full transition-colors">
                            <span class="material-symbols-outlined text-white">close</span>
                        </button>
                    </div>
                    
                    <!-- User Section -->
                    <div class="p-6 border-b border-primary/20">
                        @auth
                            <div class="flex items-center gap-4 mb-4">
                                <div class="w-12 h-12 bg-gradient-to-br from-primary to-yellow-600 rounded-full flex items-center justify-center">
                                    <span class="material-symbols-outlined text-white text-xl">account_circle</span>
                                </div>
                                <div>
                                    <p class="text-white font-semibold">Xin chào, {{ Auth::user()->name }}!</p>
                                    <p class="text-yellow-300 text-sm">Chúc bạn mua sắm vui vẻ</p>
                                </div>
                            </div>
                        @else
                            <div class="text-center mb-4">
                                <div class="w-16 h-16 bg-gradient-to-br from-primary to-yellow-600 rounded-full flex items-center justify-center mx-auto mb-3">
                                    <span class="material-symbols-outlined text-white text-2xl">waving_hand</span>
                                </div>
                                <h4 class="text-white font-bold text-lg mb-2">Chào mừng bạn!</h4>
                                <p class="text-yellow-300 text-sm mb-4">Đăng nhập để trải nghiệm mua sắm tốt hơn</p>
                                <div class="flex gap-2">
                                    <a href="{{ route('login') }}" class="flex-1 bg-primary text-background-dark py-2 px-4 rounded-full text-sm font-semibold text-center hover:bg-primary/90 transition-colors">
                                        Đăng nhập
                                    </a>
                                    <a href="{{ route('register') }}" class="flex-1 border border-primary text-primary py-2 px-4 rounded-full text-sm font-semibold text-center hover:bg-primary/10 transition-colors">
                                        Đăng ký
                                    </a>
                                </div>
                            </div>
                        @endauth
                    </div>
                    
                    <!-- Navigation Menu -->
                    <div class="p-6">
                        <nav class="space-y-2">
                            <a href="/" class="flex items-center gap-4 p-3 rounded-xl hover:bg-white/10 transition-all duration-200 group">
                                <span class="material-symbols-outlined text-primary group-hover:scale-110 transition-transform">home</span>
                                <span class="text-white font-medium">Trang chủ</span>
                            </a>
                            
                            <div class="menu-dropdown">
                                <button class="flex items-center justify-between w-full p-3 rounded-xl hover:bg-white/10 transition-all duration-200 group" id="productsMenuToggle">
                                    <div class="flex items-center gap-4">
                                        <span class="material-symbols-outlined text-primary group-hover:scale-110 transition-transform">inventory_2</span>
                                        <span class="text-white font-medium">Sản phẩm</span>
                                    </div>
                                    <span class="material-symbols-outlined text-primary transition-transform duration-200" id="productsMenuIcon">expand_more</span>
                                </button>
                                <div class="ml-12 mt-2 space-y-1 max-h-0 overflow-hidden transition-all duration-300" id="productsSubmenu">
                                    <a href="/san-pham?search=Nguyên phụ liệu" class="block p-2 text-yellow-300 text-sm hover:text-white hover:bg-white/5 rounded-lg transition-colors">Nguyên phụ liệu</a>
                                    <a href="/san-pham?search=Đồ trang trí" class="block p-2 text-yellow-300 text-sm hover:text-white hover:bg-white/5 rounded-lg transition-colors">Đồ trang trí</a>
                                    <a href="/san-pham?search=Thời trang len" class="block p-2 text-yellow-300 text-sm hover:text-white hover:bg-white/5 rounded-lg transition-colors">Thời trang len</a>
                                    <a href="/san-pham?search=Combo tự làm" class="block p-2 text-yellow-300 text-sm hover:text-white hover:bg-white/5 rounded-lg transition-colors">Combo tự làm</a>
                                    <a href="/san-pham?search=Thú bông" class="block p-2 text-yellow-300 text-sm hover:text-white hover:bg-white/5 rounded-lg transition-colors">Thú bông len</a>
                                </div>
                            </div>

                            
                            <a href="/gioi-thieu" class="flex items-center gap-4 p-3 rounded-xl hover:bg-white/10 transition-all duration-200 group">
                                <span class="material-symbols-outlined text-primary group-hover:scale-110 transition-transform">info</span>
                                <span class="text-white font-medium">Giới thiệu</span>
                            </a>
                                                            
                            <a href="#" class="flex items-center gap-4 p-3 rounded-xl hover:bg-white/10 transition-all duration-200 group">
                                <span class="material-symbols-outlined text-primary group-hover:scale-110 transition-transform">receipt_long</span>
                                <span class="text-white font-medium">Tin tức & Blog</span>
                            </a>
                            
                        </nav>
                    </div>
                    
                    <!-- Footer -->
                    <div class="absolute bottom-0 left-0 right-0 p-6 border-t border-primary/20">                        
                        <div class="flex justify-center gap-4 mt-4">
                            <a href="#" class="w-10 h-10 bg-white/10 rounded-full flex items-center justify-center hover:bg-white/20 transition-colors">
                                <i class="fab fa-facebook"></i>
                            </a>
                            <a href="#" class="w-10 h-10 bg-white/10 rounded-full flex items-center justify-center hover:bg-white/20 transition-colors">
                                <i class="fab fa-instagram"></i>
                            </a>
                            <a href="#" class="w-10 h-10 bg-white/10 rounded-full flex items-center justify-center hover:bg-white/20 transition-colors">
                                <i class="fab fa-tiktok"></i>
                            </a>
                        </div>
                        
                        <p class="text-center text-yellow-300/60 text-xs mt-4">© 2025 {{ getSiteName() }}. All rights reserved.</p>
                    </div>
                </div>
            </div>
        `;
        
        $('body').append(menuHtml);
        $('body').addClass('overflow-hidden');
        
        // Animate menu in
        setTimeout(() => {
            $('#mobileMenuPanel').removeClass('-translate-x-full');
        }, 10);
        
        // Products submenu toggle
        $('#productsMenuToggle').click(function(e) {
            e.preventDefault();
            const submenu = $('#productsSubmenu');
            const icon = $('#productsMenuIcon');
            
            if (submenu.hasClass('max-h-0')) {
                submenu.removeClass('max-h-0').addClass('max-h-96');
                icon.addClass('rotate-180');
            } else {
                submenu.removeClass('max-h-96').addClass('max-h-0');
                icon.removeClass('rotate-180');
            }
        });
    });
    
    // Close mobile menu
    $(document).on('click', '#closeMobileMenu', function(e) {
        e.preventDefault();
        $('#mobileMenuPanel').addClass('-translate-x-full');
        setTimeout(() => {
            $('#mobileMenuOverlay').remove();
            $('body').removeClass('overflow-hidden');
        }, 300);
    });
    
    $(document).on('click', '#mobileMenuOverlay', function(e) {
        if (e.target === this) {
            $('#mobileMenuPanel').addClass('-translate-x-full');
            setTimeout(() => {
                $('#mobileMenuOverlay').remove();
                $('body').removeClass('overflow-hidden');
            }, 300);
        }
    });
    
    // Scroll animations and effects
    function initScrollAnimations() {
        // Intersection Observer for scroll animations
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };
        
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('animate-in');
                    entry.target.classList.remove('animate-out');
                } else {
                    entry.target.classList.remove('animate-in');
                    entry.target.classList.add('animate-out');
                }
            });
        }, observerOptions);
        
        // Observe all sections
        document.querySelectorAll('section, .product-card, .category-item').forEach(el => {
            observer.observe(el);
        });
    }
    
    // Floating/hover effects
    function initHoverEffects() {
        // Product cards floating effect
        $(document).on('mouseenter', '.product-item', function() {
            $(this).addClass('transform-gpu scale-105 shadow-2xl shadow-primary/20');
        }).on('mouseleave', '.product-item', function() {
            $(this).removeClass('transform-gpu scale-105 shadow-2xl shadow-primary/20');
        });
        
        // Category items pulse effect
        $(document).on('mouseenter', '.category-item', function() {
            $(this).find('.material-symbols-outlined').addClass('animate-pulse scale-110');
        }).on('mouseleave', '.category-item', function() {
            $(this).find('.material-symbols-outlined').removeClass('animate-pulse scale-110');
        });
        
        // Buttons glow effect
        $(document).on('mouseenter', 'button, .cta-button', function() {
            $(this).addClass('shadow-lg shadow-primary/30');
        }).on('mouseleave', 'button, .cta-button', function() {
            $(this).removeClass('shadow-lg shadow-primary/30');
        });
    }
    
    // Parallax effect for hero section
    function initParallaxEffect() {
        $(window).scroll(function() {
            const scrolled = $(this).scrollTop();
            const parallax = scrolled * 0.5;
            const windowHeight = $(window).height();
            const documentHeight = $(document).height();
            const scrollPercent = (scrolled / (documentHeight - windowHeight)) * 100;
            
            // Update scroll progress indicator
            $('#scrollProgress').css('width', scrollPercent + '%');
            
            // Hero background parallax
            if (scrolled < windowHeight) {
                $('.hero-section .absolute.inset-0').css('transform', `translateY(${parallax}px)`);
            }
            
            // Header backdrop blur effect
            if (scrolled > 50) {
                $('header').addClass('backdrop-blur-xl bg-background-light/95 dark:bg-background-dark/95 shadow-lg');
            } else {
                $('header').removeClass('backdrop-blur-xl bg-background-light/95 dark:bg-background-dark/95 shadow-lg');
            }
            
            // Floating elements
            $('.animate-float').each(function() {
                const speed = $(this).data('speed') || 0.5;
                const yPos = -(scrolled * speed);
                $(this).css('transform', `translateY(${yPos}px)`);
            });
        });
    }
    
    // Smooth scroll for internal links
    function initSmoothScroll() {
        $('a[href^="#"]').click(function(e) {
            e.preventDefault();
            const target = $(this.getAttribute('href'));
            if (target.length) {
                $('html, body').animate({
                    scrollTop: target.offset().top - 80
                }, 800, 'easeInOutCubic');
            }
        });
    }
    
    // Loading animations
    function initLoadingAnimations() {
        // Stagger animation for product cards
        $('#featuredProducts').on('DOMNodeInserted', function() {
            $(this).find('.product-item').each(function(index) {
                $(this).css({
                    'animation-delay': `${index * 100}ms`,
                    'opacity': '0',
                    'transform': 'translateY(20px)'
                }).animate({
                    'opacity': 1,
                    'transform': 'translateY(0)'
                }, 600);
            });
        });
        
        // Categories loading animation
        $('#categoriesContainer').on('DOMNodeInserted', function() {
            $(this).find('.category-item').each(function(index) {
                $(this).css({
                    'animation-delay': `${index * 80}ms`,
                    'opacity': '0',
                    'transform': 'scale(0.8)'
                }).animate({
                    'opacity': 1,
                    'transform': 'scale(1)'
                }, 500);
            });
        });
    }
    
    // Touch gestures for mobile
    function initTouchGestures() {
        let startX, startY, distX, distY;
        
        $(document).on('touchstart', function(e) {
            const touch = e.originalEvent.touches[0];
            startX = touch.clientX;
            startY = touch.clientY;
        });
        
        $(document).on('touchmove', function(e) {
            if (!startX || !startY) return;
            
            const touch = e.originalEvent.touches[0];
            distX = touch.clientX - startX;
            distY = touch.clientY - startY;
            
            // Swipe right to open menu
            if (distX > 50 && Math.abs(distY) < 100 && startX < 50) {
                $('#mobileMenuBtn').click();
            }
        });
        
        $(document).on('touchend', function() {
            startX = startY = distX = distY = null;
        });
    }
    
    // Page loading animation
    function initPageLoading() {
        // Add loading class to body
        $('body').addClass('loading');
        
        // Remove loading class when everything is loaded
        $(window).on('load', function() {
            setTimeout(() => {
                $('body').removeClass('loading').addClass('loaded');
                
                // Trigger entrance animations
                $('.animate-on-load').each(function(index) {
                    setTimeout(() => {
                        $(this).addClass('animate-in');
                    }, index * 100);
                });
            }, 500);
        });
    }
    
    // Add ripple effect to buttons
    function addRippleEffect() {
        $(document).on('click', 'button, .cta-button, .category-item', function(e) {
            const button = $(this);
            const ripple = $('<span class="ripple"></span>');
            
            const rect = this.getBoundingClientRect();
            const size = Math.max(rect.width, rect.height);
            const x = e.clientX - rect.left - size / 2;
            const y = e.clientY - rect.top - size / 2;
            
            ripple.css({
                width: size,
                height: size,
                left: x,
                top: y
            });
            
            button.append(ripple);
            
            setTimeout(() => {
                ripple.remove();
            }, 600);
        });
    }
    
    // Add magnetic effect to buttons
    function addMagneticEffect() {
        $('.group\\/btn, .add-to-cart-btn').each(function() {
            const button = $(this);
            
            button.on('mousemove', function(e) {
                const rect = this.getBoundingClientRect();
                const x = e.clientX - rect.left - rect.width / 2;
                const y = e.clientY - rect.top - rect.height / 2;
                
                $(this).css('transform', `translate(${x * 0.1}px, ${y * 0.1}px) scale(1.05)`);
            });
            
            button.on('mouseleave', function() {
                $(this).css('transform', 'translate(0px, 0px) scale(1)');
            });
        });
    }
    
    // Initialize all effects
    initPageLoading();
    loadCategories();
    loadFeaturedProducts();
    updateCartCount();
    initScrollAnimations();
    initHoverEffects();
    initParallaxEffect();
    initSmoothScroll();
    initLoadingAnimations();
    initTouchGestures();
    addRippleEffect();
    addMagneticEffect();
    
    // Update cart count periodically
    setInterval(updateCartCount, 30000);
    
    // Add some floating elements for visual interest
    setTimeout(() => {
        $('<div class="fixed top-20 right-10 w-2 h-2 bg-primary/30 rounded-full animate-float" data-speed="0.3"></div>').appendTo('body');
        $('<div class="fixed top-40 left-10 w-1 h-1 bg-primary/40 rounded-full animate-float" data-speed="0.5"></div>').appendTo('body');
        $('<div class="fixed bottom-40 right-20 w-3 h-3 bg-primary/20 rounded-full animate-float" data-speed="0.2"></div>').appendTo('body');
    }, 2000);
});

// Login Popup Functions
function showLoginPopup() {
    $('#loginPopup').removeClass('hidden').addClass('flex');
    setTimeout(() => {
        $('#loginPopupContent').removeClass('scale-95 opacity-0').addClass('scale-100 opacity-100');
    }, 10);
}

function hideLoginPopup() {
    $('#loginPopupContent').removeClass('scale-100 opacity-100').addClass('scale-95 opacity-0');
    setTimeout(() => {
        $('#loginPopup').removeClass('flex').addClass('hidden');
    }, 200);
}

// Close popup when clicking outside
$(document).on('click', '#loginPopup', function(e) {
    if (e.target === this) {
        hideLoginPopup();
    }
});

// Close popup with Escape key
$(document).on('keydown', function(e) {
    if (e.key === 'Escape' && !$('#loginPopup').hasClass('hidden')) {
        hideLoginPopup();
    }
});
</script>

<!-- Login/Register Popup -->
<div id="loginPopup" class="fixed inset-0 bg-black/60 backdrop-blur-sm z-[9999] hidden items-center justify-center p-4">
    <div id="loginPopupContent" class="relative bg-gradient-to-b from-[#2a2318] to-[#1a1610] rounded-3xl p-8 max-w-sm w-full mx-4 shadow-2xl border border-primary/20 transform scale-95 opacity-0 transition-all duration-200">
        <!-- Close Button -->
        <button onclick="hideLoginPopup()" class="absolute top-4 right-4 w-8 h-8 flex items-center justify-center rounded-full hover:bg-white/10 transition-colors z-10">
            <span class="material-symbols-outlined text-white/70 text-xl">close</span>
        </button>
        
        <!-- Hero Image -->
        <div class="popup-hero-image relative h-32 mb-6 rounded-2xl overflow-hidden">
            <div class="absolute bottom-4 left-1/2 transform -translate-x-1/2">
                <div class="w-12 h-12 bg-white/20 rounded-full flex items-center justify-center backdrop-blur-sm">
                    <span class="material-symbols-outlined text-white text-2xl">yarn</span>
                </div>
            </div>
        </div>
        
        <!-- Content -->
        <div class="text-center mb-8">
            <h2 class="text-2xl font-bold text-white mb-3">Bạn chưa đăng nhập?</h2>
            <p class="text-gray-400 text-sm leading-relaxed">
                Vui lòng đăng nhập hoặc đăng ký tài khoản để chúng tôi có thể gửi những sản phẩm len ấm áp đến tay bạn.
            </p>
        </div>
        
        <!-- Action Button -->
        <a href="{{ route('login') }}" 
           class="block w-full bg-gradient-to-r from-primary to-yellow-500 text-background-dark font-semibold py-4 px-6 rounded-2xl text-center hover:shadow-lg hover:shadow-primary/25 transition-all duration-300 transform hover:scale-105 mb-4">
            Đăng nhập / Đăng ký
        </a>
        
        <!-- Skip Button -->
        <button onclick="hideLoginPopup()" 
                class="block w-full text-gray-400 text-sm py-2 hover:text-white transition-colors">
            Bỏ qua
        </button>
    </div>
</div>

</body>
</html>