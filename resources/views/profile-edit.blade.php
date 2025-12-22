<!DOCTYPE html>
<html class="dark" lang="vi">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Chỉnh sửa hồ sơ - LENLAB</title>
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
                        "background-dark": "#0f0f0f",
                        "surface-dark": "#1a1a1a",
                        "card-dark": "#2a2a2a"
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
        /* Force dark theme - cache buster v2 */
        body {
            font-family: 'Spline Sans', sans-serif;
            background: #0f0f0f;
            min-height: 100vh;
        }
        
        .profile-container {
            background: #0f0f0f;
            max-width: 400px;
            margin: 0 auto;
            min-height: 100vh;
        }
        
        .profile-header {
            background: #0f0f0f;
            padding: 20px;
            text-align: center;
            position: relative;
        }
        
        .avatar-container {
            position: relative;
            display: inline-block;
            margin-bottom: 16px;
        }
        
        .avatar {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            border: 3px solid #FAC638;
            background: rgba(45, 45, 45, 0.8);
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-label {
            display: block;
            color: #9ca3af;
            font-size: 14px;
            margin-bottom: 8px;
            font-weight: 500;
        }
        
        /* CRITICAL: Dark theme input styling with maximum specificity */
        input.form-input,
        select.form-input,
        .form-input {
            width: 100% !important;
            background-color: #2d2d2d !important;
            background: #2d2d2d !important;
            border: 1px solid rgba(255, 255, 255, 0.3) !important;
            border-radius: 8px !important;
            padding: 16px !important;
            color: #ffffff !important;
            font-size: 16px !important;
            transition: all 0.3s ease !important;
            -webkit-appearance: none !important;
            -moz-appearance: none !important;
            appearance: none !important;
        }
        
        input.form-input:focus,
        select.form-input:focus,
        .form-input:focus {
            outline: none !important;
            border-color: #FAC638 !important;
            background-color: #373737 !important;
            background: #373737 !important;
            box-shadow: 0 0 0 2px rgba(250, 198, 56, 0.2) !important;
        }
        
        input.form-input::placeholder,
        .form-input::placeholder {
            color: #9ca3af !important;
        }
        
        /* Force dark theme for all input types */
        input[type="text"].form-input,
        input[type="email"].form-input,
        input[type="tel"].form-input,
        input[type="password"].form-input,
        input[type="date"].form-input,
        select.form-input {
            background-color: #2d2d2d !important;
            color: #ffffff !important;
            border: 1px solid rgba(255, 255, 255, 0.3) !important;
        }
        
        .input-icon {
            position: absolute;
            right: 16px;
            top: 50%;
            transform: translateY(-50%);
            color: #9ca3af;
            pointer-events: none;
        }
        
        .input-group {
            position: relative;
        }
        
        .password-toggle {
            position: absolute;
            right: 16px;
            top: 50%;
            transform: translateY(-50%);
            color: #9ca3af;
            cursor: pointer;
            transition: color 0.3s ease;
        }
        
        .password-toggle:hover {
            color: #FAC638;
        }
        
        .select-wrapper {
            position: relative;
        }
        
        .form-select {
            appearance: none !important;
            -webkit-appearance: none !important;
            -moz-appearance: none !important;
            background-color: #2d2d2d !important;
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%239ca3af' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='m6 8 4 4 4-4'/%3e%3c/svg%3e") !important;
            background-position: right 12px center !important;
            background-repeat: no-repeat !important;
            background-size: 16px !important;
            padding-right: 40px !important;
            color: #ffffff !important;
        }
        
        .date-group {
            display: flex;
            gap: 12px;
        }
        
        .date-group .form-group {
            flex: 1;
            margin-bottom: 0;
        }
        
        .save-btn {
            width: 100%;
            background: linear-gradient(135deg, #FAC638, #f59e0b);
            color: #0f0f0f;
            border: none;
            border-radius: 16px;
            padding: 18px;
            font-size: 18px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-top: 20px;
        }
        
        .save-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(250, 198, 56, 0.4);
        }
        
        .save-btn:disabled {
            opacity: 0.6;
            cursor: not-allowed;
            transform: none;
        }
        
        .loading-spinner {
            display: none;
            width: 20px;
            height: 20px;
            border: 2px solid transparent;
            border-top: 2px solid #0f0f0f;
            border-radius: 50%;
            animation: spin 1s linear infinite;
            margin-right: 8px;
        }
        
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        
        .password-note {
            color: #FAC638;
            font-size: 12px;
            font-weight: 600;
        }
        
        /* Additional override for browser defaults */
        * {
            box-sizing: border-box;
        }
        
        input:not([type="submit"]):not([type="button"]):not([type="reset"]) {
            background-color: #2d2d2d !important;
            color: #ffffff !important;
        }
        
        select {
            background-color: #2d2d2d !important;
            color: #ffffff !important;
        }
    </style>
</head>

<body class="bg-background-dark">
    <div class="profile-container">
        <!-- Header -->
        <div class="profile-header">
            <div class="flex items-center justify-between mb-6">
                <button onclick="window.location.href='/profile'" class="text-white hover:text-primary transition-colors">
                    <span class="material-symbols-outlined text-2xl">arrow_back</span>
                </button>
                <h1 class="text-white font-semibold text-lg">Chỉnh sửa hồ sơ</h1>
                <button onclick="saveProfile()" class="text-primary hover:text-yellow-400 transition-colors font-semibold">
                    Lưu
                </button>
            </div>
            
            <!-- Avatar Section -->
            <div class="avatar-container">
                <div class="avatar bg-gray-600 flex items-center justify-center">
                    <span class="material-symbols-outlined text-4xl text-gray-300">person</span>
                </div>
            </div>
        </div>

        <!-- Form Section -->
        <div class="p-6">
            <form id="profileForm">
                <!-- Họ và tên -->
                <div class="form-group">
                    <label class="form-label">Họ và tên</label>
                    <div class="input-group">
                        <input type="text" 
                               class="form-input" 
                               id="fullName"
                               name="name"
                               value="{{ Auth::user()->name ?? '' }}"
                               placeholder="Nhập họ và tên">
                        <span class="input-icon">
                            <span class="material-symbols-outlined text-sm">person</span>
                        </span>
                    </div>
                </div>

                <!-- Số điện thoại -->
                <div class="form-group">
                    <label class="form-label">Số điện thoại</label>
                    <div class="input-group">
                        <input type="tel" 
                               class="form-input" 
                               id="phone"
                               name="phone"
                               value="{{ Auth::user()->phone ?? '' }}"
                               placeholder="Nhập số điện thoại">
                        <span class="input-icon">
                            <span class="material-symbols-outlined text-sm">call</span>
                        </span>
                    </div>
                </div>

                <!-- Email -->
                <div class="form-group">
                    <label class="form-label">Email</label>
                    <div class="input-group">
                        <input type="email" 
                               class="form-input" 
                               id="email"
                               name="email"
                               value="{{ Auth::user()->email ?? '' }}"
                               placeholder="Nhập email">
                        <span class="input-icon">
                            <span class="material-symbols-outlined text-sm">lock</span>
                        </span>
                    </div>
                </div>

                <!-- Đổi mật khẩu -->
                <div class="form-group">
                    <label class="form-label">Đổi mật khẩu <span class="password-note">Bỏ trống</span></label>
                    
                    <!-- Mật khẩu hiện tại -->
                    <div class="input-group mb-3">
                        <input type="password" 
                               class="form-input" 
                               id="currentPassword"
                               name="current_password"
                               placeholder="••••••">
                        <span class="password-toggle" onclick="togglePassword('currentPassword')">
                            <span class="material-symbols-outlined text-sm">visibility</span>
                        </span>
                    </div>
                    
                    <!-- Mật khẩu mới -->
                    <div class="input-group mb-3">
                        <input type="password" 
                               class="form-input" 
                               id="newPassword"
                               name="password"
                               placeholder="Mật khẩu mới">
                        <span class="password-toggle" onclick="togglePassword('newPassword')">
                            <span class="material-symbols-outlined text-sm">visibility</span>
                        </span>
                    </div>
                    
                    <!-- Nhập lại mật khẩu mới -->
                    <div class="input-group">
                        <input type="password" 
                               class="form-input" 
                               id="confirmPassword"
                               name="password_confirmation"
                               placeholder="Nhập lại mật khẩu mới">
                        <span class="password-toggle" onclick="togglePassword('confirmPassword')">
                            <span class="material-symbols-outlined text-sm">visibility</span>
                        </span>
                    </div>
                </div>

                <!-- Giới tính và Ngày sinh -->
                <div class="date-group mb-6">
                    <div class="form-group">
                        <label class="form-label">Giới tính</label>
                        <div class="select-wrapper">
                            <select class="form-input form-select" id="gender" name="gender">
                                <option value="">Chọn</option>
                                <option value="male" {{ (Auth::user()->gender ?? '') == 'male' ? 'selected' : '' }}>Nam</option>
                                <option value="female" {{ (Auth::user()->gender ?? '') == 'female' ? 'selected' : '' }}>Nữ</option>
                                <option value="other" {{ (Auth::user()->gender ?? '') == 'other' ? 'selected' : '' }}>Khác</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Ngày sinh</label>
                        <input type="date" 
                               class="form-input" 
                               id="birthDate"
                               name="birth_date"
                               value="{{ Auth::user()->birth_date ?? '' }}">
                    </div>
                </div>

                <!-- Save Button -->
                <button type="submit" class="save-btn" id="saveBtn">
                    <div class="loading-spinner" id="loadingSpinner"></div>
                    <span id="saveText">Lưu thay đổi</span>
                </button>
            </form>
        </div>
    </div>

    <script>
        function togglePassword(inputId) {
            const input = document.getElementById(inputId);
            const icon = input.nextElementSibling.querySelector('.material-symbols-outlined');
            
            if (input.type === 'password') {
                input.type = 'text';
                icon.textContent = 'visibility_off';
            } else {
                input.type = 'password';
                icon.textContent = 'visibility';
            }
        }

        function saveProfile() {
            document.getElementById('profileForm').dispatchEvent(new Event('submit'));
        }

        document.getElementById('profileForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const saveBtn = document.getElementById('saveBtn');
            const loadingSpinner = document.getElementById('loadingSpinner');
            const saveText = document.getElementById('saveText');
            
            // Show loading
            saveBtn.disabled = true;
            loadingSpinner.style.display = 'inline-block';
            saveText.textContent = 'Đang lưu...';
            
            const formData = new FormData(this);
            
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            
            $.ajax({
                url: '/profile/update',
                method: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    if (response.success) {
                        alert('Cập nhật hồ sơ thành công!');
                        window.location.href = '/profile';
                    } else {
                        alert(response.message || 'Có lỗi xảy ra');
                    }
                },
                error: function(xhr) {
                    let message = 'Có lỗi xảy ra, vui lòng thử lại!';
                    
                    if (xhr.responseJSON && xhr.responseJSON.errors) {
                        const errors = xhr.responseJSON.errors;
                        message = Object.values(errors).flat().join('\n');
                    } else if (xhr.responseJSON && xhr.responseJSON.message) {
                        message = xhr.responseJSON.message;
                    }
                    
                    alert(message);
                },
                complete: function() {
                    // Hide loading
                    saveBtn.disabled = false;
                    loadingSpinner.style.display = 'none';
                    saveText.textContent = 'Lưu thay đổi';
                }
            });
        });
    </script>
</body>
</html>