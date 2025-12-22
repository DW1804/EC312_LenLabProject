<!DOCTYPE html>
<html class="dark" lang="vi">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Hồ sơ của tôi - LENLAB</title>
    <link href="https://fonts.googleapis.com/css2?family=Spline+Sans:wght@300;400;500;600;700&family=Noto+Sans:wght@400;500;700&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
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
        
        .profile-container {
            background: #1a1a1a;
            max-width: 400px;
            margin: 0 auto;
            min-height: 100vh;
        }
        
        .menu-item {
            background: rgba(45, 45, 45, 0.8);
            border: 1px solid rgba(255, 255, 255, 0.1);
            transition: all 0.3s ease;
        }
        
        .menu-item:hover {
            background: rgba(60, 60, 60, 0.9);
            transform: translateX(4px);
        }
        
        .stats-card {
            background: rgba(45, 45, 45, 0.6);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        .avatar {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, #FAC638, #f59e0b);
            border: 3px solid rgba(250, 198, 56, 0.3);
        }
        
        .badge {
            background: #FAC638;
            color: #1a1a1a;
            font-size: 12px;
            font-weight: 600;
        }
    </style>
</head>

<body class="bg-background-dark">
    <div class="profile-container">
        <!-- Header -->
        <div class="flex items-center justify-between p-4 border-b border-gray-700">
            <button onclick="window.location.href='/'" class="text-white hover:text-primary transition-colors">
                <span class="material-symbols-outlined text-2xl">arrow_back</span>
            </button>
            <h1 class="text-white font-semibold text-lg">Hồ sơ của tôi</h1>
            <div class="w-8"></div>
        </div>

        <!-- Profile Header -->
        <div class="p-6 text-center">
            <div class="relative inline-block mb-4">
                <div class="avatar rounded-full flex items-center justify-center">
                    <span class="material-symbols-outlined text-background-dark text-3xl">person</span>
                </div>
            </div>
            
            <h2 class="text-white text-xl font-semibold mb-1">{{ Auth::user()->name }}</h2>
            <p class="text-gray-400 text-sm">{{ Auth::user()->email }}</p>
        </div>

        <!-- Menu Section: MUA SẮM -->
        <div class="px-4 mb-6">
            <h3 class="text-gray-400 text-sm font-medium mb-3 uppercase tracking-wider">MUA SẮM</h3>
                      
            <!-- Đơn hàng của tôi -->
            <div class="menu-item rounded-xl p-4 mb-3 flex items-center justify-between cursor-pointer" onclick="window.location.href='/orders'">
                <div class="flex items-center gap-4">
                    <div class="w-10 h-10 bg-blue-500/20 rounded-full flex items-center justify-center">
                        <span class="material-symbols-outlined text-blue-400">shopping_bag</span>
                    </div>
                    <div class="flex items-center gap-3">
                        <span class="text-white font-medium">Đơn hàng của tôi</span>
                    </div>
                </div>
                <span class="material-symbols-outlined text-gray-400">chevron_right</span>
            </div>
            
            <!-- Voucher của tôi -->
            <div class="menu-item rounded-xl p-4 mb-3 flex items-center justify-between cursor-pointer" onclick="window.location.href='/vouchers'">
                <div class="flex items-center gap-4">
                    <div class="w-10 h-10 bg-orange-500/20 rounded-full flex items-center justify-center">
                        <span class="material-symbols-outlined text-orange-400">local_offer</span>
                    </div>
                    <span class="text-white font-medium">Voucher của tôi</span>
                </div>
                <span class="material-symbols-outlined text-gray-400">chevron_right</span>
            </div>
            
            <!-- Số địa chỉ -->
            <div class="menu-item rounded-xl p-4 mb-3 flex items-center justify-between cursor-pointer" onclick="window.location.href='/addresses'">
                <div class="flex items-center gap-4">
                    <div class="w-10 h-10 bg-green-500/20 rounded-full flex items-center justify-center">
                        <span class="material-symbols-outlined text-green-400">location_on</span>
                    </div>
                    <span class="text-white font-medium">Số địa chỉ</span>
                </div>
                <span class="material-symbols-outlined text-gray-400">chevron_right</span>
            </div>
        </div>

        <!-- Menu Section: CÀI ĐẶT & ỨNG DỤNG -->
        <div class="px-4 mb-6">
            <h3 class="text-gray-400 text-sm font-medium mb-3 uppercase tracking-wider">CÀI ĐẶT & ỨNG DỤNG</h3>
            
            <!-- Cài đặt tài khoản -->
            <div class="menu-item rounded-xl p-4 mb-3 flex items-center justify-between cursor-pointer" onclick="window.location.href='/profile/edit'">
                <div class="flex items-center gap-4">
                    <div class="w-10 h-10 bg-purple-500/20 rounded-full flex items-center justify-center">
                        <span class="material-symbols-outlined text-purple-400">settings</span>
                    </div>
                    <span class="text-white font-medium">Cài đặt tài khoản</span>
                </div>
                <span class="material-symbols-outlined text-gray-400">chevron_right</span>
            </div>
            
            <!-- Trung tâm trợ giúp -->
            <div class="menu-item rounded-xl p-4 mb-3 flex items-center justify-between cursor-pointer" onclick="window.location.href='#'">
                <div class="flex items-center gap-4">
                    <div class="w-10 h-10 bg-indigo-500/20 rounded-full flex items-center justify-center">
                        <span class="material-symbols-outlined text-indigo-400">help</span>
                    </div>
                    <span class="text-white font-medium">Trung tâm trợ giúp</span>
                </div>
                <span class="material-symbols-outlined text-gray-400">chevron_right</span>
            </div>
        </div>

        <!-- Logout Button -->
        <div class="px-4 pb-8">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full text-red-400 font-medium py-4 hover:text-red-300 transition-colors">
                    Đăng xuất
                </button>
            </form>
            
        </div>
    </div>

    <!-- Account Settings Modal -->
    <div id="accountSettingsModal" class="fixed inset-0 bg-black/60 backdrop-blur-sm z-50 hidden">
        <div class="flex items-end justify-center min-h-screen">
            <div class="bg-surface-dark w-full max-w-md rounded-t-3xl p-6 transform translate-y-full transition-transform duration-300" id="modalContent">
                <div class="w-12 h-1 bg-gray-600 rounded-full mx-auto mb-6"></div>
                
                <h3 class="text-white text-xl font-semibold mb-6">Cài đặt tài khoản</h3>
                
                <div class="space-y-4">
                    <!-- Edit Profile -->
                    <div class="flex items-center justify-between p-4 bg-card-dark rounded-xl cursor-pointer hover:bg-gray-600 transition-colors" onclick="showEditProfile()">
                        <div class="flex items-center gap-3">
                            <span class="material-symbols-outlined text-primary">edit</span>
                            <span class="text-white">Chỉnh sửa hồ sơ</span>
                        </div>
                        <span class="material-symbols-outlined text-gray-400">chevron_right</span>
                    </div>
                    
                    <!-- Change Password -->
                    <div class="flex items-center justify-between p-4 bg-card-dark rounded-xl cursor-pointer hover:bg-gray-600 transition-colors" onclick="showChangePassword()">
                        <div class="flex items-center gap-3">
                            <span class="material-symbols-outlined text-primary">lock</span>
                            <span class="text-white">Đổi mật khẩu</span>
                        </div>
                        <span class="material-symbols-outlined text-gray-400">chevron_right</span>
                    </div>
                    
                    <!-- Privacy Settings -->
                    <div class="flex items-center justify-between p-4 bg-card-dark rounded-xl cursor-pointer hover:bg-gray-600 transition-colors">
                        <div class="flex items-center gap-3">
                            <span class="material-symbols-outlined text-primary">privacy_tip</span>
                            <span class="text-white">Quyền riêng tư</span>
                        </div>
                        <span class="material-symbols-outlined text-gray-400">chevron_right</span>
                    </div>
                </div>
                
                <button onclick="hideAccountSettings()" class="w-full mt-6 py-3 text-gray-400 hover:text-white transition-colors">
                    Đóng
                </button>
            </div>
        </div>
    </div>

    <script>
        function showAccountSettings() {
            const modal = document.getElementById('accountSettingsModal');
            const content = document.getElementById('modalContent');
            
            modal.classList.remove('hidden');
            setTimeout(() => {
                content.classList.remove('translate-y-full');
            }, 10);
        }
        
        function hideAccountSettings() {
            const modal = document.getElementById('accountSettingsModal');
            const content = document.getElementById('modalContent');
            
            content.classList.add('translate-y-full');
            setTimeout(() => {
                modal.classList.add('hidden');
            }, 300);
        }
        
        function showEditProfile() {
            alert('Chức năng chỉnh sửa hồ sơ sẽ được phát triển trong phiên bản tiếp theo');
        }
        
        function showChangePassword() {
            alert('Chức năng đổi mật khẩu sẽ được phát triển trong phiên bản tiếp theo');
        }
        
        // Close modal when clicking outside
        document.getElementById('accountSettingsModal').addEventListener('click', function(e) {
            if (e.target === this) {
                hideAccountSettings();
            }
        });
    </script>
</body>
</html>