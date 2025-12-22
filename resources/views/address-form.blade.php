<!DOCTYPE html>
<html class="dark" lang="vi">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ isset($address) ? 'Sửa địa chỉ' : 'Thêm địa chỉ mới' }} - LENLAB</title>
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
        body {
            font-family: 'Spline Sans', sans-serif;
            background: #0f0f0f;
            min-height: 100vh;
            padding-bottom: 100px;
        }
        
        .form-container {
            background: #0f0f0f;
            max-width: 400px;
            margin: 0 auto;
            min-height: 100vh;
        }
        
        .form-input {
            background: rgba(45, 45, 45, 0.8) !important;
            border: 1px solid rgba(255, 255, 255, 0.1) !important;
            color: white !important;
            border-radius: 12px !important;
            padding: 12px 16px !important;
            width: 100% !important;
            transition: all 0.3s ease;
        }
        
        .form-input:focus {
            outline: none !important;
            border-color: #FAC638 !important;
            background: rgba(45, 45, 45, 0.9) !important;
            box-shadow: none !important;
        }
        
        .form-input::placeholder {
            color: rgba(255, 255, 255, 0.5) !important;
        }
        
        .custom-select {
            position: relative;
        }
        
        .custom-select-button {
            background: rgba(45, 45, 45, 0.8);
            border: 1px solid rgba(255, 255, 255, 0.1);
            color: white;
            border-radius: 12px;
            padding: 12px 16px;
            width: 100%;
            text-align: left;
            cursor: pointer;
            display: flex;
            justify-content: space-between;
            align-items: center;
            transition: all 0.3s ease;
        }
        
        .custom-select-button:focus {
            outline: none;
            border-color: #FAC638;
            background-color: rgba(45, 45, 45, 0.9);
        }
        
        .custom-select-button:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }
        
        .custom-select-dropdown {
            position: absolute;
            top: 100%;
            left: 0;
            right: 0;
            background: rgba(45, 45, 45, 0.95);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 12px;
            margin-top: 4px;
            max-height: 200px;
            overflow-y: auto;
            z-index: 1000;
            backdrop-filter: blur(10px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.3);
        }
        
        .custom-select-option {
            padding: 12px 16px;
            color: white;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .custom-select-option:hover {
            background: rgba(250, 198, 56, 0.1);
            color: #FAC638;
        }
        
        .custom-select-option:first-child {
            border-radius: 12px 12px 0 0;
        }
        
        .custom-select-option:last-child {
            border-radius: 0 0 12px 12px;
        }
        
        .toggle-switch {
            position: relative;
            width: 44px;
            height: 24px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 12px;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .toggle-switch.active {
            background: #FAC638;
        }
        
        .toggle-switch::after {
            content: '';
            position: absolute;
            top: 2px;
            left: 2px;
            width: 20px;
            height: 20px;
            background: white;
            border-radius: 50%;
            transition: all 0.3s ease;
        }
        
        .toggle-switch.active::after {
            transform: translateX(20px);
        }
        
        .save-btn {
            background: #FAC638;
            transition: all 0.3s ease;
            border-radius: 25px;
        }
        
        .save-btn:hover {
            background: #e6b332;
            transform: translateY(-1px);
        }
        
        .save-btn:disabled {
            opacity: 0.5;
            cursor: not-allowed;
            transform: none;
        }
    </style>
</head>

<body class="bg-background-dark">
    <div class="form-container">
        <!-- Header -->
        <div class="flex items-center justify-between p-4">
            <button onclick="window.location.href='/addresses'" class="text-white hover:text-primary transition-colors">
                <span class="material-symbols-outlined text-2xl">arrow_back</span>
            </button>
            <h1 class="text-white font-semibold text-lg">{{ isset($address) ? 'Sửa địa chỉ' : 'Thêm địa chỉ mới' }}</h1>
            <div class="w-8"></div>
        </div>

        <!-- Form -->
        <div class="p-6">
            <form id="addressForm">
                @csrf
                @if(isset($address))
                    @method('PUT')
                    <input type="hidden" name="address_id" value="{{ $address->id }}">
                @endif
                
                <div class="mb-4">
                    <label class="block text-white text-sm font-medium mb-2">Họ và tên</label>
                    <input type="text" name="full_name" class="form-input" placeholder="Nhập họ và tên" required 
                           value="{{ isset($address) ? $address->full_name : '' }}">
                </div>

                <div class="mb-4">
                    <label class="block text-white text-sm font-medium mb-2">Số điện thoại</label>
                    <input type="tel" name="phone" class="form-input" placeholder="Nhập số điện thoại" required
                           value="{{ isset($address) ? $address->phone : '' }}">
                </div>

                <div class="mb-4">
                    <label class="block text-white text-sm font-medium mb-2">Tỉnh / Thành phố</label>
                    <div class="custom-select">
                        <button type="button" class="custom-select-button" id="provinceButton">
                            <span id="provinceText">{{ isset($address) && $address->province ? $address->province->name : 'Chọn Tỉnh/Thành phố' }}</span>
                            <span class="material-symbols-outlined text-primary">expand_more</span>
                        </button>
                        <div class="custom-select-dropdown hidden" id="provinceDropdown">
                            <!-- Provinces will be loaded from database -->
                        </div>
                        <input type="hidden" name="province_id" id="provinceValue" required 
                               value="{{ isset($address) ? $address->province_id : '' }}">
                    </div>
                </div>

                <div class="mb-4">
                    <label class="block text-white text-sm font-medium mb-2">Xã / Phường</label>
                    <div class="custom-select">
                        <button type="button" class="custom-select-button" id="wardButton" {{ !isset($address) ? 'disabled' : '' }}>
                            <span id="wardText">{{ isset($address) && $address->ward ? $address->ward->name : 'Chọn Xã/Phường' }}</span>
                            <span class="material-symbols-outlined text-primary">expand_more</span>
                        </button>
                        <div class="custom-select-dropdown hidden" id="wardDropdown">
                            <!-- Ward options will be loaded here -->
                        </div>
                        <input type="hidden" name="ward_id" id="wardValue" required 
                               value="{{ isset($address) ? $address->ward_id : '' }}">
                    </div>
                </div>

                <div class="mb-6">
                    <label class="block text-white text-sm font-medium mb-2">Địa chỉ cụ thể</label>
                    <textarea name="specific_address" class="form-input" rows="3" placeholder="Số nhà, tên đường, tòa nhà" required>{{ isset($address) ? $address->specific_address : '' }}</textarea>
                </div>

                <!-- Đặt làm mặc định -->
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <p class="text-white font-medium">Đặt làm địa chỉ mặc định</p>
                        <p class="text-gray-400 text-sm">Sử dụng làm địa chỉ giao hàng mặc định</p>
                    </div>
                    <div class="toggle-switch {{ isset($address) && $address->is_default ? 'active' : '' }}" onclick="toggleDefault()" id="defaultToggle"></div>
                    <input type="hidden" name="is_default" id="isDefaultValue" value="{{ isset($address) && $address->is_default ? '1' : '0' }}">
                </div>
            </form>
        </div>

        <!-- Fixed Bottom Button -->
        <div class="fixed bottom-0 left-1/2 transform -translate-x-1/2 w-full max-w-[400px] bg-background-dark p-4">
            <button onclick="saveAddress()" class="save-btn w-full py-4 text-black font-bold text-lg">
                {{ isset($address) ? 'Cập nhật địa chỉ' : 'Lưu địa chỉ' }}
            </button>
        </div>
    </div>

    <script>
        let isDefault = {{ isset($address) && $address->is_default ? 'true' : 'false' }};
        let isEditMode = {{ isset($address) ? 'true' : 'false' }};
        let addressId = {{ isset($address) ? $address->id : 'null' }};

        $(document).ready(function() {
            loadProvinces();
            setupCustomSelects();
            
            // If editing and has province, load wards
            @if(isset($address) && $address->province_id)
                loadWards({{ $address->province_id }});
            @endif
            
            // Setup CSRF token
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
        });

        function loadProvinces() {
            $.get('/api/provinces', function(response) {
                if (response.success) {
                    let html = '';
                    response.provinces.forEach(province => {
                        html += `<div class="custom-select-option" data-value="${province.id}">${province.name}</div>`;
                    });
                    $('#provinceDropdown').html(html);
                }
            }).fail(function() {
                console.error('Failed to load provinces');
            });
        }

        function loadWards(provinceId) {
            if (!provinceId) return;
            
            $.get(`/api/provinces/${provinceId}/wards`, function(response) {
                if (response.success) {
                    let html = '';
                    response.wards.forEach(ward => {
                        html += `<div class="custom-select-option" data-value="${ward.id}">${ward.name}</div>`;
                    });
                    $('#wardDropdown').html(html);
                    $('#wardButton').prop('disabled', false);
                    
                    // Reset ward selection if not already set
                    if (!$('#wardValue').val()) {
                        $('#wardValue').val('');
                        $('#wardText').text('Chọn Xã/Phường');
                    }
                } else {
                    $('#wardDropdown').html('');
                    $('#wardButton').prop('disabled', true);
                    $('#wardValue').val('');
                    $('#wardText').text('Chọn Xã/Phường');
                }
            }).fail(function() {
                console.error('Failed to load wards');
                $('#wardDropdown').html('');
                $('#wardButton').prop('disabled', true);
                $('#wardValue').val('');
                $('#wardText').text('Chọn Xã/Phường');
            });
        }

        function setupCustomSelects() {
            // Setup province dropdown
            setupCustomSelect('province', function(value, text) {
                $('#provinceValue').val(value);
                $('#provinceText').text(text);
                loadWards(value);
                // Reset ward when province changes
                $('#wardValue').val('');
                $('#wardText').text('Chọn Xã/Phường');
            });
            
            // Setup ward dropdown
            setupCustomSelect('ward', function(value, text) {
                $('#wardValue').val(value);
                $('#wardText').text(text);
            });
        }

        function setupCustomSelect(type, callback) {
            const button = $(`#${type}Button`);
            const dropdown = $(`#${type}Dropdown`);
            
            // Toggle dropdown
            button.off('click').on('click', function(e) {
                e.preventDefault();
                if ($(this).prop('disabled')) return;
                
                // Close other dropdowns
                $('.custom-select-dropdown').addClass('hidden');
                
                // Toggle current dropdown
                dropdown.toggleClass('hidden');
            });
            
            // Select option
            dropdown.off('click').on('click', '.custom-select-option', function() {
                const value = $(this).data('value');
                const text = $(this).text();
                
                dropdown.addClass('hidden');
                
                if (callback) {
                    callback(value, text);
                }
            });
        }

        // Close dropdowns when clicking outside
        $(document).on('click', function(e) {
            if (!$(e.target).closest('.custom-select').length) {
                $('.custom-select-dropdown').addClass('hidden');
            }
        });

        function toggleDefault() {
            isDefault = !isDefault;
            console.log('Toggle isDefault to:', isDefault, 'type:', typeof isDefault);
            $('#defaultToggle').toggleClass('active', isDefault);
            $('#isDefaultValue').val(isDefault ? '1' : '0');
        }

        function saveAddress() {
            // Validate form
            const fullName = $('input[name="full_name"]').val().trim();
            const phone = $('input[name="phone"]').val().trim();
            const provinceId = $('#provinceValue').val();
            const wardId = $('#wardValue').val();
            const specificAddress = $('textarea[name="specific_address"]').val().trim();

            if (!fullName) {
                alert('Vui lòng nhập họ và tên');
                return;
            }
            if (!phone) {
                alert('Vui lòng nhập số điện thoại');
                return;
            }
            if (!provinceId) {
                alert('Vui lòng chọn tỉnh/thành phố');
                return;
            }
            if (!wardId) {
                alert('Vui lòng chọn xã/phường');
                return;
            }
            if (!specificAddress) {
                alert('Vui lòng nhập địa chỉ cụ thể');
                return;
            }

            // Prepare data
            const addressData = {
                full_name: fullName,
                phone: phone,
                province_id: parseInt(provinceId),
                ward_id: parseInt(wardId),
                specific_address: specificAddress,
                is_default: isDefault // Send boolean directly
            };

            // Show loading state
            $('.save-btn').prop('disabled', true).html(`
                <span class="animate-spin material-symbols-outlined mr-2">refresh</span>
                Đang lưu...
            `);

            // API call
            const url = isEditMode ? `/api/user/addresses/${addressId}` : '/api/user/addresses';
            const method = isEditMode ? 'PUT' : 'POST';

            $.ajax({
                url: url,
                type: method,
                data: addressData,
                success: function(response) {
                    if (response.success) {
                        alert(response.message || 'Đã lưu địa chỉ thành công!');
                        window.location.href = '/addresses';
                    } else {
                        alert('Có lỗi xảy ra: ' + response.message);
                        $('.save-btn').prop('disabled', false).text(isEditMode ? 'Cập nhật địa chỉ' : 'Lưu địa chỉ');
                    }
                },
                error: function(xhr) {
                    let errorMessage = 'Có lỗi xảy ra, vui lòng thử lại!';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMessage = xhr.responseJSON.message;
                    }
                    
                    alert(errorMessage);
                    $('.save-btn').prop('disabled', false).text(isEditMode ? 'Cập nhật địa chỉ' : 'Lưu địa chỉ');
                }
            });
        }
    </script>
</body>
</html>