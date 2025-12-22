<!DOCTYPE html>
<html class="dark" lang="vi">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Về LENLAB - Gửi ấm áp trong từng mũi len</title>
    <link href="https://fonts.googleapis.com/css2?family=Spline+Sans:wght@300;400;500;600;700&family=Noto+Sans:wght@400;500;700&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        "primary": "#FAC638",
                        "background-dark": "#1a1a1a",
                        "surface-dark": "#2d2d2d",
                        "card-dark": "#333333"
                    },
                    fontFamily: {
                        "display": ["Spline Sans", "sans-serif"],
                        "body": ["Noto Sans", "sans-serif"]
                    }
                }
            }
        }
    </script>
    <style>
        body {
            font-family: 'Spline Sans', sans-serif;
            background: #1a1a1a;
            min-height: 100vh;
        }
        
        .intro-container {
            background: #1a1a1a;
            max-width: 400px;
            margin: 0 auto;
            min-height: 100vh;
        }
        
        .hero-section {
            position: relative;
            height: 300px;
            overflow: hidden;
        }
        
        .hero-image {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        
        .hero-overlay {
            position: absolute;
            inset: 0;
            background: linear-gradient(to bottom, transparent 0%, rgba(26, 26, 26, 0.8) 70%, #1a1a1a 100%);
        }
        
        .hero-text {
            position: absolute;
            bottom: 20px;
            left: 20px;
            right: 20px;
            z-index: 10;
        }
        
        .value-card {
            background: rgba(45, 45, 45, 0.6);
            border: 1px solid rgba(255, 255, 255, 0.1);
            transition: all 0.3s ease;
        }
        
        .value-card:hover {
            background: rgba(60, 60, 60, 0.8);
            transform: translateY(-2px);
        }
        
        .timeline-item {
            position: relative;
            padding-left: 40px;
            opacity: 0;
            transform: translateX(-20px);
            transition: all 0.6s ease;
        }
        
        .timeline-item.visible {
            opacity: 1;
            transform: translateX(0);
        }
        
        .timeline-item::before {
            content: '';
            position: absolute;
            left: 12px;
            top: 30px;
            bottom: -20px;
            width: 2px;
            background: linear-gradient(to bottom, #FAC638, transparent);
        }
        
        .timeline-item:last-child::before {
            display: none;
        }
        
        .timeline-dot {
            position: absolute;
            left: 0;
            top: 8px;
            width: 28px;
            height: 28px;
            background: #FAC638;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .fade-in {
            opacity: 0;
            transform: translateY(20px);
            transition: all 0.8s ease;
        }
        
        .fade-in.visible {
            opacity: 1;
            transform: translateY(0);
        }
        
        .cta-button {
            background: linear-gradient(135deg, #FAC638, #f59e0b);
            transition: all 0.3s ease;
        }
        
        .cta-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(250, 198, 56, 0.4);
        }
    </style>
</head>

<body class="bg-background-dark">
    <div class="intro-container">
        <!-- Header - Fixed -->
        <header class="sticky top-0 z-50 w-full bg-background-dark/95 backdrop-blur-md border-b border-gray-700">
            <div class="px-4 py-3 flex items-center justify-between gap-3">
                <div class="flex items-center gap-3">
                    <button id="mobileMenuBtn" class="flex items-center justify-center size-10 rounded-full hover:bg-white/10 transition-colors">
                        <span class="material-symbols-outlined text-white">menu</span>
                    </button>
                    <div class="text-xl font-bold tracking-tight text-white">LENLAB</div>
                </div>
                <div class="flex-1 max-w-md mx-2">
                    <div class="relative group">
                        <span class="absolute inset-y-0 left-3 flex items-center pointer-events-none">
                            <span class="material-symbols-outlined text-gray-400 text-[20px]">search</span>
                        </span>
                        <input class="w-full py-2 pl-10 pr-4 bg-surface-dark text-white rounded-full border-none text-sm focus:ring-2 focus:ring-primary/50 placeholder:text-gray-500 transition-all" 
                               placeholder="Tìm kiếm..." 
                               type="text" 
                               id="searchInput"/>
                    </div>
                </div>
                <div class="flex items-center gap-1">
                    <button class="relative flex items-center justify-center size-10 rounded-full hover:bg-white/10 transition-colors" onclick="window.location.href='/cart'">
                        <span class="material-symbols-outlined text-white">shopping_bag</span>
                        <span class="absolute top-2 right-2 flex h-2 w-2">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-primary opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-2 w-2 bg-primary" id="cartIndicator"></span>
                        </span>
                    </button>
                    @auth
                        <button class="flex items-center justify-center size-10 rounded-full hover:bg-white/10 transition-colors" onclick="window.location.href='/profile'">
                            <span class="material-symbols-outlined text-white">account_circle</span>
                        </button>
                    @else
                        <button class="flex items-center justify-center size-10 rounded-full hover:bg-white/10 transition-colors" onclick="showLoginPopup()">
                            <span class="material-symbols-outlined text-white">account_circle</span>
                        </button>
                    @endauth
                </div>
            </div>
        </header>

        <!-- Hero Section -->
        <div class="hero-section">
            <img src="{{ asset('banner.png') }}" 
                 alt="Len thủ công LENLAB" 
                 class="hero-image"
                 onerror="this.src='https://images.unsplash.com/photo-1586281010691-3d8b8c0e5b8d?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80'">
            <div class="hero-overlay"></div>
            <div class="hero-text">
                <h2 class="text-white text-2xl font-bold mb-2">Gửi ấm áp trong<br>từng mũi len</h2>
            </div>
        </div>

        <!-- Introduction -->
        <div class="p-6 fade-in">
            <p class="text-gray-300 text-sm leading-relaxed mb-6">
                Xin chào, chúng mình là <span class="text-primary font-semibold">LENLAB</span>. 
                LENLAB là nơi nhỏ của những sản phẩm len thủ công bền vững. Chúng tôi tin rằng mỗi mũi len mang trong mình một câu chuyện về sự kiên nhẫn và tình yêu thủ công, và chúng tôi muốn chia sẻ điều đó đến bạn.
            </p>
            
            <!-- Core Values -->
            <div class="grid grid-cols-3 gap-3 mb-8">
                <div class="value-card rounded-xl p-4 text-center fade-in" style="transition-delay: 0.1s">
                    <div class="w-12 h-12 bg-primary/20 rounded-full flex items-center justify-center mx-auto mb-2">
                        <span class="material-symbols-outlined text-primary text-xl">handshake</span>
                    </div>
                    <p class="text-white text-xs font-medium mb-1">Thủ công</p>
                    <p class="text-primary text-xs font-bold">100%</p>
                </div>
                
                <div class="value-card rounded-xl p-4 text-center fade-in" style="transition-delay: 0.2s">
                    <div class="w-12 h-12 bg-primary/20 rounded-full flex items-center justify-center mx-auto mb-2">
                        <span class="material-symbols-outlined text-primary text-xl">recycling</span>
                    </div>
                    <p class="text-white text-xs font-medium mb-1">Nguyên liệu</p>
                    <p class="text-primary text-xs font-bold">Recycling</p>
                </div>
                
                <div class="value-card rounded-xl p-4 text-center fade-in" style="transition-delay: 0.3s">
                    <div class="w-12 h-12 bg-primary/20 rounded-full flex items-center justify-center mx-auto mb-2">
                        <span class="material-symbols-outlined text-primary text-xl">eco</span>
                    </div>
                    <p class="text-white text-xs font-medium mb-1">Len</p>
                    <p class="text-primary text-xs font-bold">Cao cấp</p>
                </div>
            </div>
        </div>

        <!-- Vision & Mission -->
        <div class="px-6 pb-6">
            <h3 class="text-white text-xl font-bold mb-6 fade-in">Tầm nhìn & Sứ mệnh</h3>
            
            <!-- Vision -->
            <div class="mb-6 fade-in" style="transition-delay: 0.1s">
                <div class="flex items-start gap-3 mb-3">
                    <div class="w-8 h-8 bg-primary/20 rounded-full flex items-center justify-center flex-shrink-0 mt-1">
                        <span class="material-symbols-outlined text-primary text-sm">visibility</span>
                    </div>
                    <div>
                        <h4 class="text-primary font-semibold mb-2">Tầm nhìn</h4>
                        <p class="text-gray-300 text-sm leading-relaxed">
                            Trở thành thương hiệu len thủ công hàng đầu tại Việt Nam, nơi mà nghệ thuật đan móc truyền thống gặp gỡ với sự sáng tạo hiện đại.
                        </p>
                    </div>
                </div>
            </div>
            
            <!-- Mission -->
            <div class="mb-8 fade-in" style="transition-delay: 0.2s">
                <div class="flex items-start gap-3">
                    <div class="w-8 h-8 bg-primary/20 rounded-full flex items-center justify-center flex-shrink-0 mt-1">
                        <span class="material-symbols-outlined text-primary text-sm">flag</span>
                    </div>
                    <div>
                        <h4 class="text-primary font-semibold mb-2">Sứ mệnh</h4>
                        <p class="text-gray-300 text-sm leading-relaxed">
                            Mang đến những sản phẩm len thủ công chất lượng cao, thân thiện với môi trường, đồng thời lan tỏa tình yêu với nghề thủ công truyền thống.
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Timeline -->
        <div class="px-6 pb-6">
            <h3 class="text-white text-xl font-bold mb-6 fade-in">Hành trình phát triển</h3>
            
            <div class="space-y-6">
                <!-- 2021 -->
                <div class="timeline-item">
                    <div class="timeline-dot">
                        <span class="material-symbols-outlined text-background-dark text-sm">star</span>
                    </div>
                    <div class="bg-surface-dark/60 rounded-xl p-4 border border-gray-700">
                        <div class="flex items-center gap-2 mb-2">
                            <span class="text-primary font-bold">2021</span>
                        </div>
                        <h4 class="text-white font-semibold mb-2">Khởi đầu khiêm tốn</h4>
                        <p class="text-gray-400 text-sm">
                            LENLAB ra đời từ niềm đam mê với nghệ thuật đan móc và mong muốn chia sẻ những sản phẩm thủ công chất lượng.
                        </p>
                    </div>
                </div>
                
                <!-- 2022 -->
                <div class="timeline-item">
                    <div class="timeline-dot">
                        <span class="material-symbols-outlined text-background-dark text-sm">trending_up</span>
                    </div>
                    <div class="bg-surface-dark/60 rounded-xl p-4 border border-gray-700">
                        <div class="flex items-center gap-2 mb-2">
                            <span class="text-primary font-bold">2022</span>
                        </div>
                        <h4 class="text-white font-semibold mb-2">Bổ sung tập đầu tiên</h4>
                        <p class="text-gray-400 text-sm">
                            Mở rộng đội ngũ thợ thủ công và ra mắt bộ sưu tập đầu tiên với hơn 50 sản phẩm độc đáo.
                        </p>
                    </div>
                </div>
                
                <!-- 2024 -->
                <div class="timeline-item">
                    <div class="timeline-dot">
                        <span class="material-symbols-outlined text-background-dark text-sm">rocket_launch</span>
                    </div>
                    <div class="bg-surface-dark/60 rounded-xl p-4 border border-gray-700">
                        <div class="flex items-center gap-2 mb-2">
                            <span class="text-primary font-bold">2024</span>
                        </div>
                        <h4 class="text-white font-semibold mb-2">Mở rộng & Tiến vững</h4>
                        <p class="text-gray-400 text-sm">
                            Chuyển sang 100% len tái chế, mở rộng thị trường và xây dựng cộng đồng yêu len thủ công.
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- CTA Button -->
        <div class="px-6 pb-8 fade-in">
            <button onclick="window.location.href='/san-pham'" class="cta-button w-full py-4 rounded-2xl text-background-dark font-bold text-lg flex items-center justify-center gap-2">
                Khám phá sản phẩm
                <span class="material-symbols-outlined">arrow_forward</span>
            </button>
        </div>
    </div>

    <!-- Mobile Menu Container -->
    <div id="mobileMenuContainer"></div>

    <script>
        $(document).ready(function() {
            // Mobile menu button
            $('#mobileMenuBtn').click(function() {
                showMobileMenu();
            });
            
            // Intersection Observer for fade-in animations
            const observerOptions = {
                threshold: 0.1,
                rootMargin: '0px 0px -50px 0px'
            };
            
            const observer = new IntersectionObserver(function(entries) {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('visible');
                    }
                });
            }, observerOptions);
            
            // Observe all fade-in elements
            document.querySelectorAll('.fade-in, .timeline-item').forEach(el => {
                observer.observe(el);
            });
            
            // Parallax effect for hero image
            $(window).scroll(function() {
                const scrolled = $(window).scrollTop();
                $('.hero-image').css('transform', `translateY(${scrolled * 0.5}px)`);
            });
        });

        function showMobileMenu() {
            const menuHtml = `
                <div class="fixed inset-0 bg-black/60 z-50 backdrop-blur-sm" id="mobileMenuOverlay">
                    <div class="fixed left-0 top-0 h-full w-80 bg-gradient-to-b from-[#2a2318] to-[#1a1610] shadow-2xl transform -translate-x-full transition-transform duration-300 ease-out" id="mobileMenuPanel">
                        <div class="flex justify-between items-center p-6 border-b border-primary/20">
                            <h3 class="text-xl font-bold text-white">Menu</h3>
                            <button id="closeMobileMenu" class="p-2 hover:bg-white/10 rounded-full transition-colors">
                                <span class="material-symbols-outlined text-white">close</span>
                            </button>
                        </div>
                        
                        <div class="p-6 border-b border-primary/20">
                            @auth
                                <div class="flex items-center gap-4 mb-4">
                                    <div class="w-12 h-12 bg-gradient-to-br from-primary to-yellow-600 rounded-full flex items-center justify-center">
                                        <span class="material-symbols-outlined text-white text-xl">account_circle</span>
                                    </div>
                                    <div>
                                        <p class="text-white font-semibold">{{ Auth::user()->name }}</p>
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
                        
                        <div class="p-6">
                            <nav class="space-y-2">
                                <a href="/" class="flex items-center gap-4 p-3 rounded-xl hover:bg-white/10 transition-all duration-200 group">
                                    <span class="material-symbols-outlined text-primary group-hover:scale-110 transition-transform">home</span>
                                    <span class="text-white font-medium">Trang chủ</span>
                                </a>
                                <a href="/san-pham" class="flex items-center gap-4 p-3 rounded-xl hover:bg-white/10 transition-all duration-200 group">
                                    <span class="material-symbols-outlined text-primary group-hover:scale-110 transition-transform">shopping_bag</span>
                                    <span class="text-white font-medium">Sản phẩm</span>
                                </a>
                                <a href="/gioi-thieu" class="flex items-center gap-4 p-3 rounded-xl bg-white/10">
                                    <span class="material-symbols-outlined text-primary">info</span>
                                    <span class="text-white font-medium">Giới thiệu</span>
                                </a>
                                <a href="/cart" class="flex items-center gap-4 p-3 rounded-xl hover:bg-white/10 transition-all duration-200 group">
                                    <span class="material-symbols-outlined text-primary group-hover:scale-110 transition-transform">shopping_cart</span>
                                    <span class="text-white font-medium">Giỏ hàng</span>
                                </a>
                            </nav>
                        </div>
                    </div>
                </div>
            `;
            
            $('#mobileMenuContainer').html(menuHtml);
            
            setTimeout(() => {
                $('#mobileMenuPanel').removeClass('-translate-x-full');
            }, 10);
            
            // Close menu handlers
            $('#closeMobileMenu, #mobileMenuOverlay').click(function(e) {
                if (e.target === this) {
                    closeMobileMenu();
                }
            });
        }
        
        function closeMobileMenu() {
            $('#mobileMenuPanel').addClass('-translate-x-full');
            setTimeout(() => {
                $('#mobileMenuContainer').empty();
            }, 300);
        }

        function showLoginPopup() {
            window.location.href = '{{ route("login") }}';
        }
    </script>
</body>
</html>