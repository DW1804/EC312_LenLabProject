<header>
    <div class="bg">
        <!-- Logo -->
        <div class="logo">
            <a href="/" style="text-decoration: none; color: inherit;">Len Lab</a>
        </div>

        <!-- Mobile Menu Button -->
        <button class="mobile-menu-btn" id="mobileMenuBtn" style="display: none; background: none; border: none; font-size: 1.5rem; color: #ffffff; cursor: pointer;">
            <i class="fas fa-bars"></i>
        </button>

        <!-- Navigation -->
        <nav class="nav" id="mainNav">
            <ul>
                <li><a href="/">Trang chủ</a></li>
                <li class="dropdown">
                    <a href="/san-pham">Sản phẩm</a>
                    <ul class="dropdown-menu">
                        <li><a href="/san-pham?search=Nguyên phụ liệu">Nguyên phụ liệu</a></li>
                        <li><a href="/san-pham?search=Đồ trang trí">Đồ trang trí</a></li>
                        <li><a href="/san-pham?search=Thời trang len">Thời trang len</a></li>
                        <li><a href="/san-pham-so">Sản phẩm số</a></li>
                        <li><a href="/san-pham?search=Combo tự làm">Combo tự làm</a></li>
                        <li><a href="/san-pham?search=Sách hướng dẫn">Sách hướng dẫn</a></li>
                        <li><a href="/san-pham?search=Thú bông len">Thú bông len</a></li>
                    </ul>
                </li>
                <li><a href="/gioi-thieu">Giới thiệu</a></li>
            </ul>
        </nav>

        <!-- Search & User Actions -->
        <div class="user-cart">
            <!-- Mobile Search Button -->
            <button class="mobile-search-btn" id="mobileSearchBtn" style="display: none; background: none; border: none; font-size: 1.2rem; color: #ffffff; cursor: pointer; padding: 8px;">
                <i class="fas fa-search"></i>
            </button>

            <!-- Search Box -->
            <div class="search-box" id="searchBox">
                <input type="text" id="searchInput" placeholder="Tìm kiếm...">
                <button type="button" id="searchBtn"><i class="fas fa-search"></i></button>
            </div>

            <!-- Cart -->
            <div class="cart-icon" onclick="window.location.href='/cart'">
                <i class="fas fa-shopping-cart"></i>
                <span class="cart-count" id="cartCount">0</span>
            </div>

            <!-- User Profile -->
            @auth
                <div class="user-profile dropdown">
                    <i class="fas fa-user"></i>
                    <ul class="dropdown-menu user-dropdown">
                        <li><a href="/profile">Thông tin cá nhân</a></li>
                        <li><a href="/orders">Đơn hàng của tôi</a></li>
                        <li>
                            <form method="POST" action="{{ route('logout') }}" style="margin: 0;">
                                @csrf
                                <a href="#" onclick="event.preventDefault(); this.closest('form').submit();">
                                    Đăng xuất
                                </a>
                            </form>
                        </li>
                    </ul>
                </div>
            @else
                <div class="user-profile">
                    <a href="{{ route('login') }}" style="text-decoration: none; color: inherit;">
                        <i class="fas fa-user"></i>
                    </a>
                </div>
            @endauth
        </div>
    </div>

    <!-- Mobile Search Overlay -->
    <div class="mobile-search-overlay" id="mobileSearchOverlay" style="display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0.5); z-index: 2000;">
        <div style="background: white; margin: 20px; border-radius: 10px; padding: 20px;">
            <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 20px;">
                <input type="text" id="mobileSearchInput" placeholder="Tìm kiếm sản phẩm..." style="flex: 1; border: 1px solid #ddd; padding: 12px; border-radius: 5px; font-size: 16px;">
                <button id="mobileSearchSubmit" style="background: #22c55e; color: white; border: none; padding: 12px 16px; border-radius: 5px; cursor: pointer;">
                    <i class="fas fa-search"></i>
                </button>
            </div>
            <button id="closeMobileSearch" style="background: #f8f9fa; color: #333; border: 1px solid #ddd; padding: 8px 16px; border-radius: 5px; cursor: pointer; width: 100%;">
                Đóng
            </button>
        </div>
    </div>

    <!-- Mobile Navigation Overlay -->
    <div class="mobile-nav-overlay" id="mobileNavOverlay" style="display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0.5); z-index: 2000;">
        <div style="background: white; width: 280px; height: 100%; padding: 20px; overflow-y: auto;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; padding-bottom: 20px; border-bottom: 1px solid #eee;">
                <h3 style="margin: 0; color: #22c55e;">Menu</h3>
                <button id="closeMobileNav" style="background: none; border: none; font-size: 1.5rem; color: #333; cursor: pointer;">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <ul style="list-style: none; padding: 0; margin: 0;">
                <li style="margin-bottom: 15px;">
                    <a href="/" style="text-decoration: none; color: #333; font-size: 1.1rem; font-weight: 500; display: block; padding: 10px 0;">Trang chủ</a>
                </li>
                <li style="margin-bottom: 15px;">
                    <a href="/san-pham" style="text-decoration: none; color: #333; font-size: 1.1rem; font-weight: 500; display: block; padding: 10px 0;">Sản phẩm</a>
                    <ul style="list-style: none; padding: 0; margin: 10px 0 0 20px;">
                        <li><a href="/san-pham?search=Nguyên phụ liệu" style="text-decoration: none; color: #666; font-size: 0.9rem; display: block; padding: 5px 0;">Nguyên phụ liệu</a></li>
                        <li><a href="/san-pham?search=Đồ trang trí" style="text-decoration: none; color: #666; font-size: 0.9rem; display: block; padding: 5px 0;">Đồ trang trí</a></li>
                        <li><a href="/san-pham?search=Thời trang len" style="text-decoration: none; color: #666; font-size: 0.9rem; display: block; padding: 5px 0;">Thời trang len</a></li>
                        <li><a href="/san-pham-so" style="text-decoration: none; color: #666; font-size: 0.9rem; display: block; padding: 5px 0;">Sản phẩm số</a></li>
                        <li><a href="/san-pham?search=Combo tự làm" style="text-decoration: none; color: #666; font-size: 0.9rem; display: block; padding: 5px 0;">Combo tự làm</a></li>
                        <li><a href="/san-pham?search=Sách hướng dẫn" style="text-decoration: none; color: #666; font-size: 0.9rem; display: block; padding: 5px 0;">Sách hướng dẫn</a></li>
                        <li><a href="/san-pham?search=Thú bông len" style="text-decoration: none; color: #666; font-size: 0.9rem; display: block; padding: 5px 0;">Thú bông len</a></li>
                    </ul>
                </li>
                <li style="margin-bottom: 15px;">
                    <a href="/gioi-thieu" style="text-decoration: none; color: #333; font-size: 1.1rem; font-weight: 500; display: block; padding: 10px 0;">Giới thiệu</a>
                </li>
            </ul>
        </div>
    </div>
</header>

<style>
    /* Mobile Menu Styles */
    @media (max-width: 767px) {
        .mobile-menu-btn {
            display: block !important;
            color: #ffffff !important;
        }
        
        .mobile-search-btn {
            display: block !important;
            color: #ffffff !important;
        }
        
        .nav {
            display: none !important;
        }
        
        .search-box {
            display: none !important;
        }
    }
    
    @media (min-width: 768px) {
        .mobile-menu-btn {
            display: none !important;
        }
        
        .mobile-search-btn {
            display: none !important;
        }
    }
</style>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    // Search functionality
    $("#searchBtn, #mobileSearchSubmit").click(function() {
        const keyword = $("#searchInput").val().trim() || $("#mobileSearchInput").val().trim();
        if (keyword) {
            window.location.href = `/san-pham?search=${encodeURIComponent(keyword)}`;
        }
    });

    $("#searchInput, #mobileSearchInput").keypress(function(e) {
        if (e.which === 13) { // Enter key
            const keyword = $(this).val().trim();
            if (keyword) {
                window.location.href = `/san-pham?search=${encodeURIComponent(keyword)}`;
            }
        }
    });

    // Mobile menu functionality
    $("#mobileMenuBtn").click(function() {
        $("#mobileNavOverlay").fadeIn(300);
        $("body").css("overflow", "hidden");
    });

    $("#closeMobileNav, #mobileNavOverlay").click(function(e) {
        if (e.target === this) {
            $("#mobileNavOverlay").fadeOut(300);
            $("body").css("overflow", "auto");
        }
    });

    // Mobile search functionality
    $("#mobileSearchBtn").click(function() {
        $("#mobileSearchOverlay").fadeIn(300);
        $("#mobileSearchInput").focus();
        $("body").css("overflow", "hidden");
    });

    $("#closeMobileSearch, #mobileSearchOverlay").click(function(e) {
        if (e.target === this) {
            $("#mobileSearchOverlay").fadeOut(300);
            $("body").css("overflow", "auto");
        }
    });

    // Update cart count
    function updateCartCount() {
        @auth
            $.get('/api/cart', function(response) {
                const count = response.cart.reduce((sum, item) => sum + item.quantity, 0);
                $("#cartCount").text(count);
            }).fail(function() {
                $("#cartCount").text('0');
            });
        @else
            $("#cartCount").text('0');
        @endauth
    }

    updateCartCount();
    
    // Update cart count every 30 seconds
    setInterval(updateCartCount, 30000);

    // Close mobile overlays on window resize
    $(window).resize(function() {
        if ($(window).width() >= 768) {
            $("#mobileNavOverlay, #mobileSearchOverlay").hide();
            $("body").css("overflow", "auto");
        }
    });

    // Prevent body scroll when overlays are open
    $("#mobileNavOverlay, #mobileSearchOverlay").on('touchmove', function(e) {
        e.preventDefault();
    });
});
</script>