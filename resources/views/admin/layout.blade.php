<!DOCTYPE html>
<html lang="vi" class="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', $siteName . ' - Dashboard')</title>

    {{-- Favicon --}}
    <link rel="icon" type="image/x-icon" href="{{ $faviconUrl }}">

    {{-- Dynamic CSS from settings --}}
    <style>
        {!! $dynamicCss ?? '' !!}
        
        /* Additional dark mode fixes */
        .dark {
            color-scheme: dark;
        }
        
        .dark * {
            border-color: rgb(63 63 70 / 1) !important;
        }
        
        .dark .bg-white {
            background-color: rgb(39 39 42 / 1) !important;
        }
        
        .dark .text-gray-900 {
            color: rgb(229 231 235 / 1) !important;
        }
        
        .dark .text-gray-800 {
            color: rgb(229 231 235 / 1) !important;
        }
        
        .dark .bg-gray-50 {
            background-color: rgb(39 39 42 / 1) !important;
        }
        
        .dark .bg-gray-100 {
            background-color: rgb(63 63 70 / 1) !important;
        }
        
        /* Ensure cards have proper dark background */
        .dark .bg-surface-light {
            background-color: rgb(39 39 42 / 1) !important;
        }
        
        /* Fix any remaining white backgrounds */
        .dark [class*="bg-white"] {
            background-color: rgb(39 39 42 / 1) !important;
        }
    </style>

    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        primary: '{{ $primaryColor }}',
                        'primary-hover': '{{ \App\Helpers\SettingsHelper::adjustBrightness($primaryColor, -20) }}',
                        secondary: '#64748b',
                        success: '#10b981',
                        danger: '#ef4444',
                        warning: '#f59e0b',
                        info: '#06b6d4',
                        'background-light': '#F3F4F6',
                        'background-dark': '#18181B',
                        'surface-light': '#FFFFFF',
                        'surface-dark': '#27272A',
                        'text-light': '#1F2937',
                        'text-dark': '#E5E7EB',
                        'border-light': '#E5E7EB',
                        'border-dark': '#3F3F46'
                    },
                    fontFamily: {
                        sans: ['Inter', 'sans-serif']
                    },
                    borderRadius: {
                        DEFAULT: '0.5rem',
                        'xl': '1rem'
                    }
                }
            }
        };
    </script>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Round" rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <style>
        /* Reset Bootstrap conflicts with Tailwind */
        .btn {
            border-radius: 0.5rem !important;
            font-weight: 500 !important;
            transition: all 0.3s ease !important;
        }
        
        .btn-primary {
            background-color: #2563eb !important;
            border-color: #2563eb !important;
        }
        
        .btn-primary:hover {
            background-color: #1d4ed8 !important;
            border-color: #1d4ed8 !important;
            transform: translateY(-1px) !important;
        }
        
        .card {
            border: none !important;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1) !important;
            border-radius: 0.75rem !important;
        }
        
        .table {
            border-radius: 0.5rem !important;
            overflow: hidden !important;
        }
        
        .table thead th {
            background-color: #f8fafc !important;
            border: none !important;
            font-weight: 600 !important;
            color: #1e293b !important;
        }
        
        .badge {
            font-weight: 500 !important;
            padding: 0.375rem 0.75rem !important;
            border-radius: 0.5rem !important;
        }
        
        .form-control, .form-select {
            border-radius: 0.5rem !important;
            border: 1px solid #d1d5db !important;
            transition: all 0.3s ease !important;
        }
        
        .form-control:focus, .form-select:focus {
            border-color: #2563eb !important;
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1) !important;
        }
        
        .alert {
            border-radius: 0.5rem !important;
            border: none !important;
        }

        /* Mobile sidebar toggle */
        @media (max-width: 768px) {
            .sidebar-mobile {
                transform: translateX(-100%);
                transition: transform 0.3s ease;
            }
            .sidebar-mobile.show {
                transform: translateX(0);
            }
        }
    </style>

    @stack('styles')
</head>
<body class="bg-background-light dark:bg-background-dark text-text-light dark:text-text-dark font-sans transition-colors duration-200 min-h-screen">
    <div class="flex h-screen overflow-hidden">
        @include('admin.partials.sidebar')

        <main class="flex-1 overflow-y-auto bg-background-light dark:bg-background-dark">
            
            @hasSection('header')
                @yield('header')
            @else
                @include('admin.partials.header')
            @endif
            <div class="p-6">
                @if(session('success'))
                    <div class="mb-4 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 text-green-800 dark:text-green-200 px-4 py-3 rounded-lg flex items-center gap-2" 
                         id="successAlert">
                        <span class="material-icons-round text-green-600 dark:text-green-400">check_circle</span>
                        <span>{{ session('success') }}</span>
                        <button onclick="document.getElementById('successAlert').remove()" 
                                class="ml-auto text-green-600 dark:text-green-400 hover:text-green-800 dark:hover:text-green-200">
                            <span class="material-icons-round text-sm">close</span>
                        </button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="mb-4 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 text-red-800 dark:text-red-200 px-4 py-3 rounded-lg flex items-center gap-2" 
                         id="errorAlert">
                        <span class="material-icons-round text-red-600 dark:text-red-400">error</span>
                        <span>{{ session('error') }}</span>
                        <button onclick="document.getElementById('errorAlert').remove()" 
                                class="ml-auto text-red-600 dark:text-red-400 hover:text-red-800 dark:hover:text-red-200">
                            <span class="material-icons-round text-sm">close</span>
                        </button>
                    </div>
                @endif

                @if($errors->any())
                    <div class="mb-4 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 text-red-800 dark:text-red-200 px-4 py-3 rounded-lg" 
                         id="errorsAlert">
                        <div class="flex items-center gap-2 mb-2">
                            <span class="material-icons-round text-red-600 dark:text-red-400">warning</span>
                            <span class="font-medium">Có lỗi xảy ra:</span>
                            <button onclick="document.getElementById('errorsAlert').remove()" 
                                    class="ml-auto text-red-600 dark:text-red-400 hover:text-red-800 dark:hover:text-red-200">
                                <span class="material-icons-round text-sm">close</span>
                            </button>
                        </div>
                        <ul class="list-disc list-inside space-y-1 text-sm">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @yield('content')
            </div>
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Mobile sidebar toggle (sử dụng ID từ header của bạn)
        document.getElementById('btnSidebar')?.addEventListener('click', function() {
            const sidebar = document.querySelector('aside');
            sidebar.classList.toggle('sidebar-mobile');
            sidebar.classList.toggle('show');
        });

        // Auto dismiss alerts
        setTimeout(function() {
            const alerts = document.querySelectorAll('[id$="Alert"]');
            alerts.forEach(alert => {
                if (alert) {
                    alert.style.transition = 'opacity 0.5s ease';
                    alert.style.opacity = '0';
                    setTimeout(() => alert.remove(), 500);
                }
            });
        }, 5000);

        // CSRF Token for AJAX
        window.csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        
        // Dark mode initialization and persistence
        (function() {
            // Check for saved theme preference or default to 'light'
            const theme = localStorage.getItem('theme') || 'light';
            
            // Apply theme
            if (theme === 'dark') {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }
            
            // Force refresh of styles
            setTimeout(() => {
                document.body.style.display = 'none';
                document.body.offsetHeight; // Trigger reflow
                document.body.style.display = '';
            }, 10);
        })();
        
        // Theme toggle function (if you have a toggle button)
        window.toggleTheme = function() {
            const isDark = document.documentElement.classList.contains('dark');
            
            if (isDark) {
                document.documentElement.classList.remove('dark');
                localStorage.setItem('theme', 'light');
            } else {
                document.documentElement.classList.add('dark');
                localStorage.setItem('theme', 'dark');
            }
            
            // Force refresh
            setTimeout(() => {
                document.body.style.display = 'none';
                document.body.offsetHeight;
                document.body.style.display = '';
            }, 10);
        };
    </script>

    @stack('scripts')
</body>
</html>
