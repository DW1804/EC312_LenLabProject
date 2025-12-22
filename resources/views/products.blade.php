<!DOCTYPE html>
<html class="dark" lang="vi">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Tổng quan sản phẩm - LENLAB</title>
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
        
        .products-container {
            background: #1a1a1a;
            max-width: 400px;
            margin: 0 auto;
            min-height: 100vh;
        }
        
        .product-card {
            background: rgba(45, 45, 45, 0.8);
            border: 1px solid rgba(255, 255, 255, 0.1);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }
        
        .product-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 25px rgba(250, 198, 56, 0.15);
        }
        
        .product-image {
            width: 100%;
            height: 120px;
            object-fit: cover;
            border-radius: 12px;
        }
        
        .add-btn {
            position: absolute;
            bottom: 8px;
            right: 8px;
            width: 28px;
            height: 28px;
            background: #FAC638;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .add-btn:hover {
            transform: scale(1.1);
            box-shadow: 0 4px 12px rgba(250, 198, 56, 0.4);
        }
        
        .filter-btn {
            background: rgba(45, 45, 45, 0.8);
            border: 1px solid rgba(255, 255, 255, 0.1);
            transition: all 0.3s ease;
        }
        
        .filter-btn.active {
            background: #FAC638;
            color: #1a1a1a;
            font-weight: 600;
        }
        
        .rating-stars {
            color: #FAC638;
        }
        
        .pagination-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.3);
            transition: all 0.3s ease;
            cursor: pointer;
        }
        
        .pagination-dot.active {
            background: #FAC638;
            transform: scale(1.2);
        }
        
        .hide-scrollbar::-webkit-scrollbar {
            display: none;
        }
        
        .hide-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
    </style>
</head>

<body class="bg-background-dark">
    <div class="products-container">
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

        <!-- Filter Section -->
        <div class="p-4 bg-background-dark">
            <!-- Category Tabs -->
            <div class="flex gap-2 mb-4 overflow-x-auto hide-scrollbar pb-2" id="categoryTabs">
                <button class="filter-btn active px-4 py-2 rounded-full text-sm whitespace-nowrap" data-category="all">
                    Tất cả
                </button>
                <!-- Categories will be loaded here -->
            </div>
            
            <div class="flex items-center justify-between mb-4">
                <p class="text-white text-sm">Hiển thị <span id="productCount">0</span> sản phẩm</p>
                <button id="sortBtn" class="text-primary text-sm flex items-center gap-1 hover:text-primary/80 transition-colors">
                    Sắp xếp
                    <span class="material-symbols-outlined text-sm">expand_more</span>
                </button>
            </div>
        </div>

        <!-- Sort Dropdown -->
        <div id="sortDropdown" class="hidden fixed inset-0 bg-black/60 backdrop-blur-sm z-50">
            <div class="flex items-end justify-center min-h-screen">
                <div class="bg-surface-dark w-full max-w-md rounded-t-3xl p-6 transform translate-y-full transition-transform duration-300" id="sortContent">
                    <div class="w-12 h-1 bg-gray-600 rounded-full mx-auto mb-6"></div>
                    
                    <h3 class="text-white text-xl font-semibold mb-6">Sắp xếp theo</h3>
                    
                    <div class="space-y-3">
                        <button class="sort-option w-full text-left p-4 bg-card-dark rounded-xl text-white hover:bg-gray-600 transition-colors" data-sort="default">
                            Mặc định
                        </button>
                        <button class="sort-option w-full text-left p-4 bg-card-dark rounded-xl text-white hover:bg-gray-600 transition-colors" data-sort="price-asc">
                            Giá: Thấp đến cao
                        </button>
                        <button class="sort-option w-full text-left p-4 bg-card-dark rounded-xl text-white hover:bg-gray-600 transition-colors" data-sort="price-desc">
                            Giá: Cao đến thấp
                        </button>
                        <button class="sort-option w-full text-left p-4 bg-card-dark rounded-xl text-white hover:bg-gray-600 transition-colors" data-sort="name-asc">
                            Tên: A-Z
                        </button>
                        <button class="sort-option w-full text-left p-4 bg-card-dark rounded-xl text-white hover:bg-gray-600 transition-colors" data-sort="name-desc">
                            Tên: Z-A
                        </button>
                    </div>
                    
                    <button onclick="hideSortDropdown()" class="w-full mt-6 py-3 text-gray-400 hover:text-white transition-colors">
                        Đóng
                    </button>
                </div>
            </div>
        </div>

        <!-- Products Grid -->
        <div class="px-4 pb-20">
            <div class="grid grid-cols-2 gap-3" id="productsGrid">
                <!-- Products will be loaded here -->
            </div>
            
            <!-- Loading -->
            <div id="loading" class="text-center py-8">
                <div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-primary"></div>
                <p class="text-gray-400 mt-2">Đang tải sản phẩm...</p>
            </div>
        </div>

        <!-- Pagination -->
        <div class="fixed bottom-4 left-1/2 transform -translate-x-1/2 z-40">
            <div class="flex items-center gap-3 bg-surface-dark/90 backdrop-blur-md px-5 py-3 rounded-full shadow-lg border border-gray-700">
                <button id="prevPage" class="text-gray-400 hover:text-primary transition-colors disabled:opacity-30" onclick="changePage(-1)">
                    <span class="material-symbols-outlined text-xl">chevron_left</span>
                </button>
                <div class="flex gap-2" id="paginationDots">
                    <div class="pagination-dot active" data-page="1"></div>
                    <div class="pagination-dot" data-page="2"></div>
                    <div class="pagination-dot" data-page="3"></div>
                </div>
                <button id="nextPage" class="text-primary hover:text-primary/80 transition-colors disabled:opacity-30" onclick="changePage(1)">
                    <span class="material-symbols-outlined text-xl">chevron_right</span>
                </button>
            </div>
        </div>
    </div>

    <!-- Mobile Menu (will be injected by JavaScript) -->
    <div id="mobileMenuContainer"></div>

    <script>
        let currentPage = 1;
        let currentCategory = 'all';
        let currentSort = 'default';
        let products = [];
        let categories = [];

        $(document).ready(function() {
            loadCategories();
            loadProducts();
            
            // Mobile menu button
            $('#mobileMenuBtn').click(function() {
                showMobileMenu();
            });
            
            // Search functionality
            $('#searchInput').on('input', function() {
                const searchTerm = $(this).val();
                filterProducts(searchTerm);
            });
            
            // Sort button
            $('#sortBtn').click(function() {
                showSortDropdown();
            });
            
            // Sort options
            $('.sort-option').click(function() {
                currentSort = $(this).data('sort');
                loadProducts(); // Reload with new sort
                hideSortDropdown();
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
                        
                        <div class="p-6">
                            <nav class="space-y-2">
                                <a href="/" class="flex items-center gap-4 p-3 rounded-xl hover:bg-white/10 transition-all duration-200 group">
                                    <span class="material-symbols-outlined text-primary group-hover:scale-110 transition-transform">home</span>
                                    <span class="text-white font-medium">Trang chủ</span>
                                </a>
                                <a href="/san-pham" class="flex items-center gap-4 p-3 rounded-xl bg-white/10">
                                    <span class="material-symbols-outlined text-primary">shopping_bag</span>
                                    <span class="text-white font-medium">Sản phẩm</span>
                                </a>
                                <a href="/gioi-thieu" class="flex items-center gap-4 p-3 rounded-xl hover:bg-white/10 transition-all duration-200 group">
                                    <span class="material-symbols-outlined text-primary group-hover:scale-110 transition-transform">info</span>
                                    <span class="text-white font-medium">Giới thiệu</span>
                                </a>
                                <a href="#" class="flex items-center gap-4 p-3 rounded-xl hover:bg-white/10 transition-all duration-200 group">
                                    <span class="material-symbols-outlined text-primary group-hover:scale-110 transition-transform">shopping_cart</span>
                                    <span class="text-white font-medium">Tin tức & Blog</span>
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

        function loadCategories() {
            $.ajax({
                url: '/api/categories',
                method: 'GET',
                success: function(response) {
                    categories = response.categories || [];
                    renderCategoryTabs();
                },
                error: function() {
                    console.error('Không thể tải danh mục');
                }
            });
        }

        function renderCategoryTabs() {
            const tabsContainer = $('#categoryTabs');
            
            categories.forEach(function(category) {
                const tab = `
                    <button class="filter-btn px-4 py-2 rounded-full text-sm text-white whitespace-nowrap" data-category="${category.id}">
                        ${category.name}
                    </button>
                `;
                tabsContainer.append(tab);
            });
            
            // Use event delegation for dynamically added buttons
            $('#categoryTabs').off('click').on('click', '.filter-btn', function() {
                $('.filter-btn').removeClass('active').addClass('text-white');
                $(this).addClass('active').removeClass('text-white');
                currentCategory = $(this).data('category');
                currentPage = 1;
                loadProducts();
            });
        }

        function loadProducts() {
            $('#loading').show();
            
            $.ajax({
                url: '/api/products',
                method: 'GET',
                data: {
                    category: currentCategory,
                    page: currentPage,
                    sort: currentSort
                },
                success: function(response) {
                    products = response.products || [];
                    renderProducts();
                    updateProductCount();
                    $('#loading').hide();
                },
                error: function() {
                    $('#loading').hide();
                    $('#productsGrid').html('<div class="col-span-2 text-center text-gray-400 py-8">Không thể tải sản phẩm</div>');
                }
            });
        }

        function filterProducts(searchTerm) {
            if (!searchTerm) {
                renderProducts();
                return;
            }
            
            const filteredProducts = products.filter(product => 
                product.name.toLowerCase().includes(searchTerm.toLowerCase())
            );
            
            renderFilteredProducts(filteredProducts);
            $('#productCount').text(filteredProducts.length);
        }

        function sortProducts() {
            let sortedProducts = [...products];
            
            switch(currentSort) {
                case 'price-asc':
                    sortedProducts.sort((a, b) => parseFloat(a.price) - parseFloat(b.price));
                    break;
                case 'price-desc':
                    sortedProducts.sort((a, b) => parseFloat(b.price) - parseFloat(a.price));
                    break;
                case 'name-asc':
                    sortedProducts.sort((a, b) => a.name.localeCompare(b.name));
                    break;
                case 'name-desc':
                    sortedProducts.sort((a, b) => b.name.localeCompare(a.name));
                    break;
                default:
                    // Keep original order
                    break;
            }
            
            renderFilteredProducts(sortedProducts);
        }

        function showSortDropdown() {
            const modal = $('#sortDropdown');
            const content = $('#sortContent');
            
            modal.removeClass('hidden');
            setTimeout(() => {
                content.removeClass('translate-y-full');
            }, 10);
        }
        
        function hideSortDropdown() {
            const modal = $('#sortDropdown');
            const content = $('#sortContent');
            
            content.addClass('translate-y-full');
            setTimeout(() => {
                modal.addClass('hidden');
            }, 300);
        }

        function renderProducts() {
            renderFilteredProducts(products);
        }

        function renderFilteredProducts(productList) {
            const grid = $('#productsGrid');
            grid.empty();
            
            if (productList.length === 0) {
                grid.html('<div class="col-span-2 text-center text-gray-400 py-8">Không có sản phẩm nào</div>');
                return;
            }
            
            productList.forEach(function(product, index) {
                const productCard = createProductCard(product, index);
                grid.append(productCard);
            });
        }

        function createProductCard(product, index) {
            const colors = ['#FAC638', '#FF6B6B', '#4ECDC4', '#45B7D1', '#96CEB4', '#FFEAA7'];
            const bgColor = colors[index % colors.length];
            
            const imageUrl = product.image && product.image !== 'default.jpg' 
                ? `/PRODUCT-IMG/${product.image}` 
                : `https://via.placeholder.com/200x120/${bgColor.substring(1)}/FFFFFF?text=${encodeURIComponent(product.name.substring(0, 2))}`;
            
            const price = parseFloat(product.price) || 0;
            const formattedPrice = price.toLocaleString('vi-VN');
            
            return `
                <div class="product-card rounded-xl p-3 cursor-pointer" onclick="viewProduct(${product.id})">
                    <div class="relative mb-3">
                        <img src="${imageUrl}" alt="${product.name}" class="product-image" 
                             onerror="this.src='https://via.placeholder.com/200x120/${bgColor.substring(1)}/FFFFFF?text=${encodeURIComponent(product.name.substring(0, 2))}'">
                        ${product.is_new ? '<div class="absolute top-2 left-2 bg-red-500 text-white text-xs px-2 py-1 rounded-full">Mới</div>' : ''}
                    </div>
                    
                    <div class="mb-2">
                        <h3 class="text-white font-medium text-sm mb-1 line-clamp-2">${product.name}</h3>
                        <div class="flex items-center gap-1 mb-1">
                            <div class="rating-stars flex">
                                ${'★'.repeat(4)}${'☆'.repeat(1)}
                            </div>
                            <span class="text-gray-400 text-xs">4.0</span>
                        </div>
                        <p class="text-primary font-bold text-sm">${formattedPrice}đ</p>
                    </div>
                    
                    <div class="add-btn" onclick="event.stopPropagation(); addToCart(${product.id})">
                        <span class="material-symbols-outlined text-background-dark text-sm">add</span>
                    </div>
                </div>
            `;
        }

        function updateProductCount() {
            $('#productCount').text(products.length);
        }

        function viewProduct(productId) {
            window.location.href = `/san-pham/${productId}`;
        }

        function addToCart(productId) {
            @auth
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                
                const cartData = {
                    product_id: productId,
                    quantity: 1
                };
                
                $.post('/api/cart/add', cartData, function(response) {
                    if (response.success) {
                        // Show success message
                        const toast = $('<div class="fixed top-4 right-4 bg-primary text-background-dark px-4 py-2 rounded-lg font-medium z-50">✓ Đã thêm vào giỏ hàng!</div>');
                        $('body').append(toast);
                        setTimeout(() => {
                            toast.fadeOut(() => toast.remove());
                        }, 2000);
                    } else {
                        console.error('Cart add error:', response);
                        alert(response.message || 'Có lỗi xảy ra, vui lòng thử lại!');
                    }
                }).fail(function(xhr, status, error) {
                    console.error('Cart add failed:', xhr.responseText, status, error);
                    let errorMessage = 'Có lỗi xảy ra, vui lòng thử lại!';
                    
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMessage = xhr.responseJSON.message;
                    }
                    
                    alert(errorMessage);
                });
            @else
                // Show login popup or redirect
                if (confirm('Vui lòng đăng nhập để thêm sản phẩm vào giỏ hàng. Chuyển đến trang đăng nhập?')) {
                    window.location.href = '{{ route("login") }}';
                }
            @endauth
        }

        function changePage(direction) {
            const totalPages = 3; // You can make this dynamic based on total products
            currentPage += direction;
            
            if (currentPage < 1) currentPage = 1;
            if (currentPage > totalPages) currentPage = totalPages;
            
            // Update pagination dots
            $('.pagination-dot').removeClass('active');
            $(`.pagination-dot[data-page="${currentPage}"]`).addClass('active');
            
            // Update button states
            $('#prevPage').toggleClass('text-gray-400', currentPage === 1);
            $('#prevPage').toggleClass('text-primary', currentPage > 1);
            $('#nextPage').toggleClass('text-gray-400', currentPage === totalPages);
            $('#nextPage').toggleClass('text-primary', currentPage < totalPages);
            
            // Reload products for new page
            loadProducts();
            
            // Scroll to top
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }

        // Make pagination dots clickable
        $(document).on('click', '.pagination-dot', function() {
            const page = $(this).data('page');
            const diff = page - currentPage;
            changePage(diff);
        });
    </script>
</body>
</html>