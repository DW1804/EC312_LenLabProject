<!DOCTYPE html>
<html class="dark" lang="vi">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Thanh toán - LENLAB</title>
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
            padding-bottom: 100px;
        }
        
        .checkout-container {
            background: #1a1a1a;
            max-width: 400px;
            margin: 0 auto;
            min-height: 100vh;
            position: relative;
            overflow-x: hidden;
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
        
        /* Ensure textarea also gets dark styling */
        textarea.form-input {
            resize: none !important;
            background: rgba(45, 45, 45, 0.8) !important;
        }
        
        /* Override any Tailwind focus styles */
        input:focus, textarea:focus {
            outline: none !important;
            border-color: #FAC638 !important;
            background: rgba(45, 45, 45, 0.9) !important;
            box-shadow: none !important;
            ring: none !important;
        }
        
        /* Ensure placeholder text is visible */
        input::placeholder, textarea::placeholder {
            color: rgba(255, 255, 255, 0.5) !important;
            opacity: 1 !important;
        }
        
        .form-select {
            background: rgba(45, 45, 45, 0.8);
            border: 1px solid rgba(255, 255, 255, 0.1);
            color: white;
            border-radius: 12px;
            padding: 12px 16px;
            width: 100%;
            appearance: none;
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%23FAC638' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='m6 8 4 4 4-4'/%3e%3c/svg%3e");
            background-position: right 12px center;
            background-repeat: no-repeat;
            background-size: 16px;
            position: relative;
            z-index: 1;
        }
        
        .form-select:focus {
            outline: none;
            border-color: #FAC638;
            background-color: rgba(45, 45, 45, 0.9);
        }
        
        /* Custom dropdown */
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
            justify-content: between;
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
        
        .address-card {
            background: rgba(45, 45, 45, 0.6);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 16px;
            padding: 16px;
            transition: all 0.3s ease;
        }
        
        .address-card.selected {
            border-color: #FAC638;
            background: rgba(250, 198, 56, 0.1);
        }
        
        .address-card:hover {
            background: rgba(60, 60, 60, 0.8);
        }
        
        .checkout-btn {
            background: linear-gradient(135deg, #FAC638, #f59e0b);
            transition: all 0.3s ease;
        }
        
        .checkout-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(250, 198, 56, 0.4);
        }
        
        .checkout-btn:disabled {
            opacity: 0.5;
            cursor: not-allowed;
            transform: none;
            box-shadow: none;
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
        
        /* Progress Bar */
        .progress-bar {
            height: 4px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 2px;
            overflow: hidden;
        }
        
        .progress-fill {
            height: 100%;
            background: #FAC638;
            border-radius: 2px;
            transition: width 0.3s ease;
            width: 33%; /* 33% for step 1 of 3 */
        }
    </style>
</head>

<body class="bg-background-dark">
    <div class="checkout-container">
        <!-- Header -->
        <div class="flex items-center justify-between p-4">
            <button onclick="window.location.href='/cart'" class="text-white hover:text-primary transition-colors">
                <span class="material-symbols-outlined text-2xl">arrow_back</span>
            </button>
            <h1 class="text-white font-semibold text-lg">Địa chỉ giao hàng</h1>
            <div class="w-8"></div>
        </div>

        <!-- Progress Bar -->
        <div class="px-4 mb-6">
            <div class="progress-bar">
                <div class="progress-fill"></div>
            </div>
        </div>

        <!-- Content -->
        <div class="p-6">
            <!-- Sử dụng địa chỉ đã lưu -->
            <div class="mb-6">
                <div class="flex items-center justify-between mb-4">
                    <p class="text-white font-medium">Sử dụng địa chỉ đã lưu</p>
                    <button class="text-primary text-sm" onclick="showAddressList()">Chọn →</button>
                </div>
                
                <div id="selectedAddress" class="address-card selected" style="display: none;">
                    <div class="flex items-start justify-between">
                        <div class="flex-1">
                            <p class="text-white font-medium mb-1" id="selectedName">Nguyễn Văn A</p>
                            <p class="text-gray-300 text-sm mb-2" id="selectedPhone">0901234567</p>
                            <p class="text-gray-300 text-sm" id="selectedFullAddress">Số nhà, tên đường, tòa nhà</p>
                        </div>
                        <button onclick="editAddress()" class="text-primary">
                            <span class="material-symbols-outlined text-lg">edit</span>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Form nhập địa chỉ mới -->
            <form id="addressForm">
                @csrf
                
                <div class="mb-4">
                    <label class="block text-white text-sm font-medium mb-2">Họ và tên</label>
                    <input type="text" name="full_name" class="form-input" placeholder="Nhập họ và tên" required 
                           style="background: rgba(45, 45, 45, 0.8) !important; color: white !important; border: 1px solid rgba(255, 255, 255, 0.1) !important;">
                </div>

                <div class="mb-4">
                    <label class="block text-white text-sm font-medium mb-2">Số điện thoại</label>
                    <input type="tel" name="phone" class="form-input" placeholder="Nhập số điện thoại" required
                           style="background: rgba(45, 45, 45, 0.8) !important; color: white !important; border: 1px solid rgba(255, 255, 255, 0.1) !important;">
                </div>

                <div class="mb-4">
                    <label class="block text-white text-sm font-medium mb-2">Tỉnh / Thành phố</label>
                    <div class="custom-select">
                        <button type="button" class="custom-select-button" id="provinceButton">
                            <span id="provinceText">Chọn Tỉnh/Thành phố</span>
                            <span class="material-symbols-outlined text-primary">expand_more</span>
                        </button>
                        <div class="custom-select-dropdown hidden" id="provinceDropdown">
                            <!-- Provinces will be loaded from database -->
                        </div>
                        <input type="hidden" name="province_id" id="provinceValue" required>
                    </div>
                </div>

                <div class="mb-4">
                    <label class="block text-white text-sm font-medium mb-2">Xã / Phường</label>
                    <div class="custom-select">
                        <button type="button" class="custom-select-button" id="wardButton" disabled>
                            <span id="wardText">Chọn Xã/Phường</span>
                            <span class="material-symbols-outlined text-primary">expand_more</span>
                        </button>
                        <div class="custom-select-dropdown hidden" id="wardDropdown">
                            <!-- Ward options will be loaded here -->
                        </div>
                        <input type="hidden" name="ward_id" id="wardValue" required>
                    </div>
                </div>

                <div class="mb-6">
                    <label class="block text-white text-sm font-medium mb-2">Địa chỉ cụ thể</label>
                    <textarea name="specific_address" class="form-input" rows="3" placeholder="Số nhà, tên đường, tòa nhà" required
                              style="background: rgba(45, 45, 45, 0.8) !important; color: white !important; border: 1px solid rgba(255, 255, 255, 0.1) !important; resize: none;"></textarea>
                </div>

                <!-- Lưu địa chỉ này -->
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <p class="text-white font-medium">Lưu địa chỉ này</p>
                        <p class="text-gray-400 text-sm">Lưu trong sổ địa chỉ của bạn</p>
                    </div>
                    <div class="toggle-switch active" onclick="toggleSaveAddress()" id="saveAddressToggle"></div>
                </div>
            </form>
        </div>
    </div>

    <!-- Fixed Bottom Button -->
    <div class="fixed bottom-0 left-1/2 transform -translate-x-1/2 w-full max-w-[400px] bg-background-dark border-t border-gray-700 p-4">
        <button onclick="proceedToPayment()" class="checkout-btn w-full py-4 rounded-2xl text-background-dark font-bold text-lg">
            Tiếp tục
        </button>
    </div>

    <!-- Address List Modal -->
    <div id="addressListModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 hidden">
        <div class="absolute bottom-0 left-1/2 transform -translate-x-1/2 w-full max-w-[400px] bg-background-dark rounded-t-3xl">
            <div class="p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-white font-semibold text-lg">Chọn địa chỉ</h3>
                    <button onclick="hideAddressList()" class="text-gray-400">
                        <span class="material-symbols-outlined">close</span>
                    </button>
                </div>
                
                <div id="addressList" class="space-y-3 max-h-60 overflow-y-auto">
                    <!-- Addresses will be loaded here -->
                </div>
                
                <button onclick="addNewAddress()" class="w-full mt-4 py-3 border border-primary text-primary rounded-2xl font-medium">
                    + Thêm địa chỉ mới
                </button>
            </div>
        </div>
    </div>

    <script>
        let selectedAddressId = null;
        let saveAddress = true; // Boolean value
        let userAddresses = [];

        $(document).ready(function() {
            loadProvinces();
            // Không tự động load địa chỉ nữa
            setupProvinceChange();
            
            // Pre-fill user info if logged in
            @auth
                $('input[name="full_name"]').val('{{ auth()->user()->name ?? "" }}');
                $('input[name="phone"]').val('{{ auth()->user()->phone ?? "" }}');
            @endauth
        });

        function loadUserAddresses() {
            @auth
                $.get('/api/user/addresses', function(response) {
                    if (response.success && response.addresses.length > 0) {
                        userAddresses = response.addresses;
                        // Không tự động chọn địa chỉ đầu tiên nữa
                    }
                }).fail(function() {
                    console.log('No saved addresses found');
                });
            @endauth
        }

        function selectAddress(address) {
            selectedAddressId = address.id;
            $('#selectedName').text(address.full_name);
            $('#selectedPhone').text(address.phone);
            $('#selectedFullAddress').text(address.full_address || `${address.specific_address}, ${address.ward?.name || ''}, ${address.province?.name || ''}`);
            $('#selectedAddress').show();
            
            // Fill form with selected address
            $('input[name="full_name"]').val(address.full_name);
            $('input[name="phone"]').val(address.phone);
            $('#provinceValue').val(address.province_id);
            $('#provinceText').text(address.province?.name || '');
            $('#wardValue').val(address.ward_id);
            $('#wardText').text(address.ward?.name || '');
            $('textarea[name="specific_address"]').val(address.specific_address);
            
            // Load wards for selected province
            if (address.province_id) {
                loadWards(address.province_id);
            }
        }

        function showAddressList() {
            // Load addresses when user clicks "Chọn"
            if (userAddresses.length === 0) {
                loadUserAddresses();
                // Wait a bit for addresses to load
                setTimeout(() => {
                    if (userAddresses.length === 0) {
                        alert('Chưa có địa chỉ đã lưu');
                        return;
                    }
                    showAddressModal();
                }, 500);
            } else {
                showAddressModal();
            }
        }

        function showAddressModal() {
            let html = '';
            userAddresses.forEach(address => {
                html += `
                <div class="address-card cursor-pointer" onclick="selectAddressFromList(${address.id})">
                    <div class="flex items-start justify-between">
                        <div class="flex-1">
                            <p class="text-white font-medium mb-1">${address.full_name}</p>
                            <p class="text-gray-300 text-sm mb-2">${address.phone}</p>
                            <p class="text-gray-300 text-sm">${address.specific_address}, ${address.ward?.name || ''}, ${address.province?.name || ''}</p>
                        </div>
                    </div>
                </div>
            `;
            });
            
            $('#addressList').html(html);
            $('#addressListModal').removeClass('hidden');
        }

        function selectAddressFromList(addressId) {
            const address = userAddresses.find(addr => addr.id === addressId);
            if (address) {
                selectAddress(address);
                hideAddressList();
            }
        }

        function hideAddressList() {
            $('#addressListModal').addClass('hidden');
        }

        function addNewAddress() {
            hideAddressList();
            // Clear form for new address
            $('#addressForm')[0].reset();
            $('#selectedAddress').hide();
            selectedAddressId = null;
        }

        function editAddress() {
            $('#selectedAddress').hide();
            selectedAddressId = null;
        }

        function toggleSaveAddress() {
            saveAddress = !saveAddress;
            console.log('Toggle saveAddress to:', saveAddress, 'type:', typeof saveAddress);
            $('#saveAddressToggle').toggleClass('active', saveAddress);
        }

        function setupProvinceChange() {
            // Setup custom dropdown for province
            setupCustomSelect('province', null, function(value, text) {
                $('#provinceValue').val(value);
                $('#provinceText').text(text);
                loadWards(value);
            });
            
            // Setup custom dropdown for ward
            setupCustomSelect('ward', null, function(value, text) {
                $('#wardValue').val(value);
                $('#wardText').text(text);
            });
        }

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

            // Load shipping fee for selected province
            loadShippingFee(provinceId);
        }

        function loadShippingFee(provinceId) {
            if (!provinceId) return;
            
            $.get(`/api/shipping-fee/${provinceId}`, function(response) {
                if (response.success) {
                    // Display shipping fee and delivery time (if you want to show it on checkout page)
                    console.log('Shipping fee:', response.shipping_fee);
                    console.log('Delivery zone:', response.zone);
                    console.log('Province:', response.province_name);
                    
                    // You can add UI elements to show this info if needed
                    // For example: $('#shippingInfo').html(`Phí ship: ${response.shipping_fee.toLocaleString()}đ`);
                }
            }).fail(function() {
                console.error('Failed to load shipping fee');
            });
        }

        function setupCustomSelect(type, dropdown, callback) {
            const button = $(`#${type}Button`);
            const dropdownEl = dropdown || $(`#${type}Dropdown`);
            
            // Toggle dropdown
            button.off('click').on('click', function(e) {
                e.preventDefault();
                if ($(this).prop('disabled')) return;
                
                // Close other dropdowns
                $('.custom-select-dropdown').addClass('hidden');
                
                // Toggle current dropdown
                dropdownEl.toggleClass('hidden');
            });
            
            // Select option
            dropdownEl.off('click').on('click', '.custom-select-option', function() {
                const value = $(this).data('value');
                const text = $(this).text();
                
                dropdownEl.addClass('hidden');
                
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

        // This function is no longer needed as we get province names from database

        function proceedToPayment() {
            // Custom validation for required fields
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

            // Collect address data
            const addressData = {
                full_name: fullName,
                phone: phone,
                province_id: parseInt(provinceId),
                ward_id: parseInt(wardId),
                specific_address: specificAddress,
                save_address: saveAddress === true ? true : false, // Explicit boolean conversion
                selected_address_id: selectedAddressId ? parseInt(selectedAddressId) : null
            };

            // Store address data in session and proceed to payment
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            console.log('saveAddress variable:', saveAddress, 'type:', typeof saveAddress);
            console.log('Sending address data:', addressData);

            $.post('/api/checkout/set-address', addressData, function(response) {
                if (response.success) {
                    window.location.href = '/checkout/confirm';
                } else {
                    console.error('Server error:', response);
                    alert('Có lỗi xảy ra: ' + response.message);
                }
            }).fail(function(xhr, status, error) {
                console.error('Request failed:', xhr.responseText, status, error);
                
                let errorMessage = 'Có lỗi xảy ra, vui lòng thử lại!';
                
                if (xhr.responseJSON) {
                    if (xhr.responseJSON.message) {
                        errorMessage = xhr.responseJSON.message;
                    }
                    if (xhr.responseJSON.errors) {
                        console.error('Validation errors:', xhr.responseJSON.errors);
                        const firstError = Object.values(xhr.responseJSON.errors)[0];
                        if (firstError && firstError[0]) {
                            errorMessage = firstError[0];
                        }
                    }
                }
                
                alert(errorMessage);
            });
        }
    </script>
</body>
</html>