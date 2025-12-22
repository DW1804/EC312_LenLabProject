<!DOCTYPE html>
<html class="dark" lang="vi">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Sổ địa chỉ - LENLAB</title>
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
        
        .address-container {
            background: #0f0f0f;
            max-width: 400px;
            margin: 0 auto;
            min-height: 100vh;
        }
        
        .address-card {
            background: #1a1a1a;
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 16px;
            padding: 16px;
            margin-bottom: 16px;
            transition: all 0.3s ease;
        }
        
        .address-card.default {
            border-color: #FAC638;
            background: rgba(250, 198, 56, 0.05);
        }
        
        .address-card:hover {
            background: #2a2a2a;
        }
        
        .default-badge {
            background: #FAC638;
            color: #0f0f0f;
            font-size: 10px;
            font-weight: bold;
            padding: 2px 8px;
            border-radius: 12px;
            text-transform: uppercase;
        }
        
        .home-icon {
            background: #FAC638;
            color: #0f0f0f;
            width: 24px;
            height: 24px;
            border-radius: 4px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
        }
        
        .add-btn {
            background: #FAC638;
            transition: all 0.3s ease;
            border-radius: 25px;
        }
        
        .add-btn:hover {
            background: #e6b332;
            transform: translateY(-1px);
        }
        
        .radio-btn {
            width: 20px;
            height: 20px;
            border: 2px solid rgba(255, 255, 255, 0.3);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .radio-btn.selected {
            border-color: #FAC638;
            background: #FAC638;
        }
        
        .radio-btn.selected::after {
            content: '';
            width: 8px;
            height: 8px;
            background: #0f0f0f;
            border-radius: 50%;
        }
        
        .action-btn {
            color: #FAC638;
            font-size: 12px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .action-btn:hover {
            color: #e6b332;
        }
        
        .delete-btn {
            color: #ef4444;
        }
        
        .delete-btn:hover {
            color: #dc2626;
        }
    </style>
</head>

<body class="bg-background-dark">
    <div class="address-container">
        <!-- Header -->
        <div class="flex items-center justify-between p-4">
            <button onclick="window.location.href='/profile'" class="text-white hover:text-primary transition-colors">
                <span class="material-symbols-outlined text-2xl">arrow_back</span>
            </button>
            <h1 class="text-white font-semibold text-lg">Sổ địa chỉ</h1>
            <div class="w-8"></div>
        </div>

        <!-- Content -->
        <div class="px-4" id="addressList">
            <!-- Loading -->
            <div class="text-center py-8" id="loading">
                <div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-primary mb-4"></div>
                <p class="text-gray-400">Đang tải địa chỉ...</p>
            </div>
            
            <!-- Address items will be loaded here -->
        </div>

        <!-- Fixed Bottom Button -->
        <div class="fixed bottom-0 left-1/2 transform -translate-x-1/2 w-full max-w-[400px] bg-background-dark p-4">
            <button onclick="addNewAddress()" class="add-btn w-full py-4 text-black font-bold text-lg flex items-center justify-center gap-2">
                <span class="material-symbols-outlined">add</span>
                Thêm địa chỉ mới
            </button>
        </div>
    </div>

    <script>
        let addresses = [];

        $(document).ready(function() {
            loadAddresses();
            
            // Setup CSRF token
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
        });

        function loadAddresses() {
            $('#loading').show();
            
            @auth
                $.get('/api/user/addresses', function(response) {
                    if (response.success) {
                        addresses = response.addresses || [];
                        renderAddresses();
                    } else {
                        showEmptyState();
                    }
                    $('#loading').hide();
                }).fail(function() {
                    showEmptyState();
                    $('#loading').hide();
                });
            @else
                window.location.href = '/login';
            @endauth
        }

        function renderAddresses() {
            const container = $('#addressList');
            
            if (addresses.length === 0) {
                showEmptyState();
                return;
            }
            
            let html = '';
            
            addresses.forEach(address => {
                const isDefault = address.is_default;
                const fullAddress = `${address.specific_address}, ${address.ward?.name || ''}, ${address.province?.name || ''}`;
                
                html += `
                    <div class="address-card ${isDefault ? 'default' : ''}" data-id="${address.id}">
                        <div class="flex items-start gap-3">
                            <!-- Home Icon -->
                            <div class="home-icon">
                                <span class="material-symbols-outlined text-xs">home</span>
                            </div>
                            
                            <!-- Address Info -->
                            <div class="flex-1">
                                <div class="flex items-center gap-2 mb-1">
                                    <span class="text-white font-semibold">${address.full_name}</span>
                                    ${isDefault ? '<span class="default-badge">Mặc định</span>' : ''}
                                </div>
                                <p class="text-gray-300 text-sm mb-1">${address.phone}</p>
                                <p class="text-gray-400 text-sm mb-3">${fullAddress}</p>
                                
                                <!-- Actions -->
                                <div class="flex items-center gap-4">
                                    <span class="action-btn" onclick="editAddress(${address.id})">
                                        <span class="material-symbols-outlined text-xs mr-1">edit</span>
                                        Sửa
                                    </span>
                                    ${!isDefault ? `<span class="action-btn delete-btn" onclick="deleteAddress(${address.id})">
                                        <span class="material-symbols-outlined text-xs mr-1">delete</span>
                                        Xóa
                                    </span>` : ''}
                                </div>
                            </div>
                            
                            <!-- Radio Button -->
                            <div class="radio-btn ${isDefault ? 'selected' : ''}" onclick="setDefaultAddress(${address.id})"></div>
                        </div>
                    </div>
                `;
            });
            
            container.html(html);
        }

        function showEmptyState() {
            const container = $('#addressList');
            container.html(`
                <div class="text-center py-12">
                    <div class="w-16 h-16 bg-gray-700 rounded-full flex items-center justify-center mx-auto mb-4">
                        <span class="material-symbols-outlined text-gray-500 text-2xl">location_on</span>
                    </div>
                    <h3 class="text-white text-lg font-semibold mb-2">Chưa có địa chỉ nào</h3>
                    <p class="text-gray-400 mb-6">Thêm địa chỉ để thuận tiện cho việc đặt hàng</p>
                </div>
            `);
        }

        function addNewAddress() {
            window.location.href = '/addresses/create';
        }

        function editAddress(addressId) {
            window.location.href = `/addresses/${addressId}/edit`;
        }

        function setDefaultAddress(addressId) {
            // Don't do anything if already default
            const address = addresses.find(addr => addr.id === addressId);
            if (address && address.is_default) {
                return;
            }

            $.post(`/api/user/addresses/${addressId}/default`, function(response) {
                if (response.success) {
                    // Update local data
                    addresses.forEach(addr => {
                        addr.is_default = addr.id === addressId;
                    });
                    renderAddresses();
                } else {
                    alert('Có lỗi xảy ra: ' + response.message);
                }
            }).fail(function() {
                alert('Có lỗi xảy ra, vui lòng thử lại!');
            });
        }

        function deleteAddress(addressId) {
            if (confirm('Bạn có chắc muốn xóa địa chỉ này?')) {
                $.ajax({
                    url: `/api/user/addresses/${addressId}`,
                    type: 'DELETE',
                    success: function(response) {
                        if (response.success) {
                            // Remove from local data
                            addresses = addresses.filter(addr => addr.id !== addressId);
                            renderAddresses();
                        } else {
                            alert('Có lỗi xảy ra: ' + response.message);
                        }
                    },
                    error: function() {
                        alert('Có lỗi xảy ra, vui lòng thử lại!');
                    }
                });
            }
        }
    </script>
</body>
</html>