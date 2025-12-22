<header class="bg-surface-light dark:bg-surface-dark border-b border-border-light dark:border-border-dark sticky top-0 z-10 px-6 py-4 flex items-center justify-between transition-colors duration-300">
    {{-- Mobile --}}
    <div class="flex items-center gap-4 md:hidden">
        <button type="button" class="text-gray-500 dark:text-gray-400" id="btnSidebar">
            <span class="material-icons-round">menu</span>
        </button>
        <h1 class="text-xl font-bold text-gray-900 dark:text-white">
            {{ $pageDescription ?? 'Quản lý kho hàng và danh mục sản phẩm của bạn.' }}
        </h1>
    </div>

    {{-- Desktop --}}
    <div class="hidden md:block">
        <h2 class="text-2xl font-bold text-gray-900 dark:text-white">
            {{ $pageDescription ?? 'Quản lý kho hàng và danh mục sản phẩm của bạn.' }}
        </h2>
    </div>

    <div class="flex items-center gap-3">
        {{-- Dark mode --}}
        <button type="button"
            class="p-2 rounded-full text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors"
            id="btnTheme">
            <span class="material-icons-round" id="themeIcon">dark_mode</span>
        </button>
    </div>

    <script>
        // Theme toggle: add/remove class "dark" on <html>
        (function() {
            const root = document.documentElement;
            const btn = document.getElementById('btnTheme');
            const icon = document.getElementById('themeIcon');

            // load from localStorage
            const saved = localStorage.getItem('theme');
            if (saved === 'dark') root.classList.add('dark');
            
            function updateIcon() {
                if(icon) icon.textContent = root.classList.contains('dark') ? 'light_mode' : 'dark_mode';
            }
            updateIcon();

            btn?.addEventListener('click', () => {
                root.classList.toggle('dark');
                const isDark = root.classList.contains('dark');
                localStorage.setItem('theme', isDark ? 'dark' : 'light');
                updateIcon();
            });
        })();
    </script>
</header>