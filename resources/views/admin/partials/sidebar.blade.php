<aside class="flex flex-col w-64 h-screen bg-white dark:bg-surface-dark border-r border-border-light dark:border-border-dark flex-shrink-0">
    {{-- Brand --}}
    <div class="h-16 flex items-center px-6 border-b border-border-light dark:border-border-dark bg-white dark:bg-surface-dark">
        <div class="flex items-center gap-3">
            @if($logoUrl)
                <img src="{{ $logoUrl }}" alt="{{ $siteName }}" class="w-8 h-8 object-contain">
            @else
                <span class="material-icons-round text-primary text-3xl">gesture</span>
            @endif
            <h1 class="text-xl font-bold tracking-tight text-gray-900 dark:text-white">{{ $siteName }}</h1>
        </div>
    </div>

    {{-- Nav --}}
    <nav class="flex-1 flex flex-col overflow-y-auto bg-white dark:bg-surface-dark">
        <ul class="space-y-1 px-3 py-4">

{{-- Tổng quan --}}
            <li>
                {{-- Nút cha (Parent) --}}
                <a href="{{ route('admin.dashboard') }}"
                   class="flex items-center gap-3 px-3 py-2 rounded-lg cursor-pointer transition-colors group
                   {{-- Logic active cho nút cha: Sáng khi đang ở Dashboard HOẶC UI Config --}}
                   {{ request()->routeIs('admin.dashboard') || request()->routeIs('admin.ui_config') 
                        ? 'text-primary bg-primary/10 dark:bg-primary/20 font-semibold' 
                        : 'text-gray-700 dark:text-gray-300 font-semibold hover:bg-gray-100 dark:hover:bg-gray-800' 
                   }}">
                    <span class="material-icons-round transition-colors
                        {{ request()->routeIs('admin.dashboard') || request()->routeIs('admin.ui_config') 
                            ? 'text-primary' 
                            : 'text-gray-400 group-hover:text-primary' 
                        }}">
                        dashboard
                    </span>
                    <span>Tổng quan</span>
                </a>

                {{-- Menu con (Submenu) --}}
                <ul class="space-y-1 pl-11 mt-1">
                    
                    {{-- Mục con 1: Dashboard --}}
                    <li>
                        <a class="block px-3 py-1.5 text-sm transition-colors
                           {{ request()->routeIs('admin.dashboard') 
                                ? 'text-primary font-medium' 
                                : 'text-gray-500 dark:text-gray-400 hover:text-primary dark:hover:text-primary' 
                           }}"
                           href="{{ route('admin.dashboard') }}">
                            Dashboard
                        </a>
                    </li>

                    {{-- Mục con 2: Cấu hình UI (Đã chỉnh style giống hệt Dashboard) --}}
                    <li>
                        <a href="{{ route('admin.ui_config') }}"
                           class="block px-3 py-1.5 text-sm transition-colors
                           {{ request()->routeIs('admin.ui_config') 
                                ? 'text-primary font-medium' 
                                : 'text-gray-500 dark:text-gray-400 hover:text-primary dark:hover:text-primary' 
                           }}">
                            Cấu hình UI
                        </a>
                    </li>
                </ul>
            </li>
</a>
                    </li>
                </ul>
            </li>

            {{-- Sản phẩm --}}
            <li>
                <div class="flex items-center gap-3 px-3 py-2 rounded-lg cursor-pointer transition-colors
                    {{ request()->routeIs('admin.products.*') ? 'text-primary bg-primary/10 dark:bg-primary/20 font-semibold' : 'text-gray-700 dark:text-gray-300 font-semibold hover:bg-gray-100 dark:hover:bg-gray-800 group' }}">
                    <span class="material-icons-round
                        {{ request()->routeIs('admin.products.*') ? 'text-primary' : 'text-gray-400 group-hover:text-primary' }}">
                        inventory_2
                    </span>
                    <span>Sản phẩm</span>
                </div>

                <ul class="space-y-1 pl-11 mt-1">
                    <li>
                        <a class="block px-3 py-1.5 text-sm transition-colors
                           {{ request()->routeIs('admin.products.index') ? 'text-primary font-medium' : 'text-gray-500 dark:text-gray-400 hover:text-primary dark:hover:text-primary' }}"
                           href="{{ route('admin.products.index') }}">
                            Danh sách sản phẩm
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.digital-products.index') }}" 
                             class="block px-3 py-1.5 text-sm transition-colors
                             {{ request()->routeIs('admin.digital-products.*') 
                             ? 'text-primary font-medium' 
                           : 'text-gray-500 dark:text-gray-400 hover:text-primary dark:hover:text-primary' 
                             }}">
                          Sản phẩm số
                        </a>
                    </li>
                    <li>
                        <a class="block px-3 py-1.5 text-sm text-gray-500 dark:text-gray-400 hover:text-primary dark:hover:text-primary transition-colors"
                           href="#">
                            Đánh giá
                        </a>
                    </li>
                </ul>
            </li>

            {{-- Đơn hàng --}}
            <li>
                <a href="{{ route('admin.orders.index') }}"
                   class="flex items-center gap-3 px-3 py-2 rounded-lg cursor-pointer transition-colors group
                   {{ request()->routeIs('admin.orders.*') ? 'text-primary bg-primary/10 dark:bg-primary/20 font-semibold' : 'text-gray-700 dark:text-gray-300 font-semibold hover:bg-gray-100 dark:hover:bg-gray-800' }}">
                    <span class="material-icons-round transition-colors
                        {{ request()->routeIs('admin.orders.*') ? 'text-primary' : 'text-gray-400 group-hover:text-primary' }}">
                        shopping_bag
                    </span>

                    <span class="flex-1">Đơn hàng</span>

                    {{-- Badge (để số tĩnh trước, muốn động thì nối DB sau) --}}
                    <span class="bg-red-500 text-white text-[10px] font-bold px-1.5 py-0.5 rounded-full min-w-[18px] text-center leading-none">
                        3
                    </span>
                </a>

                <ul class="space-y-1 pl-11 mt-1">
                    <li>
                        <a class="block px-3 py-1.5 text-sm text-gray-500 dark:text-gray-400 hover:text-primary dark:hover:text-primary transition-colors"
                           href="{{ route('admin.orders.index') }}">
                            Đơn hàng thường
                        </a>
                    </li>
                    <li>
                        <a class="block px-3 py-1.5 text-sm text-gray-500 dark:text-gray-400 hover:text-primary dark:hover:text-primary transition-colors"
                           href="#">
                            Đơn đặt làm (Custom)
                        </a>
                    </li>
                </ul>
            </li>

            {{-- Chat box --}}
            <li>
                <div class="flex items-center gap-3 px-3 py-2 text-gray-700 dark:text-gray-300 font-semibold hover:bg-gray-100 dark:hover:bg-gray-800 rounded-lg cursor-pointer transition-colors group">
                    <span class="material-icons-round text-gray-400 group-hover:text-primary transition-colors">chat</span>
                    <span class="flex-1">Chat box</span>
                    <span class="bg-red-500 text-white text-[10px] font-bold px-1.5 py-0.5 rounded-full min-w-[18px] text-center leading-none">
                        5
                    </span>
                </div>
                <ul class="space-y-1 pl-11 mt-1">
                    <li>
                        <a class="block px-3 py-1.5 text-sm text-gray-500 dark:text-gray-400 hover:text-primary dark:hover:text-primary transition-colors"
                           href="#">
                            Tin nhắn khách hàng
                        </a>
                    </li>
                    <li>
                        <a class="block px-3 py-1.5 text-sm text-gray-500 dark:text-gray-400 hover:text-primary dark:hover:text-primary transition-colors"
                           href="#">
                            Kịch bản Chatbot
                        </a>
                    </li>
                </ul>
            </li>

            {{-- Khách hàng --}}
            <li>
                <a href="{{ route('admin.customers.index') }}"
                   class="flex items-center gap-3 px-3 py-2 rounded-lg cursor-pointer transition-colors group
                   {{ request()->routeIs('admin.customers.*') ? 'text-primary bg-primary/10 dark:bg-primary/20 font-semibold' : 'text-gray-700 dark:text-gray-300 font-semibold hover:bg-gray-100 dark:hover:bg-gray-800' }}">
                    <span class="material-icons-round transition-colors
                        {{ request()->routeIs('admin.customers.*') ? 'text-primary' : 'text-gray-400 group-hover:text-primary' }}">
                        people
                    </span>
                    <span>Khách hàng</span>
                </a>

                <ul class="space-y-1 pl-11 mt-1">
                    <li>
                        <a class="block px-3 py-1.5 text-sm transition-colors
                           {{ request()->routeIs('admin.customers.index') ? 'text-primary font-medium' : 'text-gray-500 dark:text-gray-400 hover:text-primary dark:hover:text-primary' }}"
                           href="{{ route('admin.customers.index') }}">
                            Danh sách người dùng
                        </a>
                    </li>
                    <li>
                        <a class="block px-3 py-1.5 text-sm text-gray-500 dark:text-gray-400 hover:text-primary dark:hover:text-primary transition-colors"
                           href="#">
                            Sản phẩm yêu thích
                        </a>
                    </li>
                </ul>
            </li>

            {{-- Marketing --}}
            <li>
                <div class="flex items-center gap-3 px-3 py-2 text-gray-700 dark:text-gray-300 font-semibold hover:bg-gray-100 dark:hover:bg-gray-800 rounded-lg cursor-pointer transition-colors group">
                    <span class="material-icons-round text-gray-400 group-hover:text-primary transition-colors">campaign</span>
                    <span>Marketing</span>
                </div>
                <ul class="space-y-1 pl-11 mt-1">
                    <li>
                        <a class="block px-3 py-1.5 text-sm text-gray-500 dark:text-gray-400 hover:text-primary dark:hover:text-primary transition-colors"
                           href="#">
                            Bài viết &amp; Banners
                        </a>
                    </li>
                    <li>
                        <a class="block px-3 py-1.5 text-sm text-gray-500 dark:text-gray-400 hover:text-primary dark:hover:text-primary transition-colors"
                           href="#">
                            Mã giảm giá
                        </a>
                    </li>
                    <li>
                        <a class="block px-3 py-1.5 text-sm text-gray-500 dark:text-gray-400 hover:text-primary dark:hover:text-primary transition-colors"
                           href="#">
                            Giỏ hàng bị bỏ quên
                        </a>
                    </li>
                </ul>
            </li>

        </ul>

        {{-- Footer links --}}
        <div class="mt-auto px-3 pb-2 pt-4">
            <h3 class="px-3 text-xs font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider mb-2">
                Hệ thống
            </h3>

            <a class="flex items-center gap-3 px-3 py-2 text-gray-700 dark:text-gray-300 font-medium hover:bg-gray-100 dark:hover:bg-gray-800 rounded-lg transition-colors group"
               href="#">
                <span class="material-icons-round text-gray-400 group-hover:text-primary transition-colors">settings</span>
                <span>Cấu hình chung</span>
            </a>
        </div>
    </nav>

    {{-- User card --}}
    <div class="p-4 border-t border-border-light dark:border-border-dark bg-white dark:bg-surface-dark">
        <div class="flex items-center gap-3">
            <img alt="Admin Avatar"
                 class="w-9 h-9 rounded-full border border-gray-200 dark:border-gray-600"
                 src="https://lh3.googleusercontent.com/aida-public/AB6AXuAwZ9i6K-jrpXxbKUeZu5BCoHObOMQPXEt1yQg1jrDcNbBE9H8N1fvjIR3gYsXthHna5g2naGmgr-qKu72LY0EgCutWCdvqvFlgP2JKTTFQPPoNWy8RsVR_NaRBLHY6zA7jJeOhmiuJgkvZrl4w8PhfeDXd8ElZ4RQQhEzoWv0vGT1YJ7ccc-sZwd_I98RujGUFShcC_S7-CYhloQDLUo9J9m7gjVVFoP49HQujbVNMUvApsXIyFQJPe4VAvR1XCsYjrQ2b049YH1o"/>

            <div class="flex-1 min-w-0">
                <p class="text-sm font-medium text-gray-900 dark:text-white truncate">
                    {{ auth()->user()->name ?? 'Admin User' }}
                </p>
                <p class="text-xs text-gray-500 dark:text-gray-400 truncate">
                    {{ auth()->user()->email ?? 'admin@lenlab.vn' }}
                </p>
            </div>

            {{-- Logout (POST) --}}
            <form method="POST" action="{{ route('admin.logout') }}">
                @csrf
                <button type="submit" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 transition-colors" title="Đăng xuất">
                    <span class="material-icons-round text-xl">logout</span>
                </button>
            </form>
        </div>
    </div>
</aside>
