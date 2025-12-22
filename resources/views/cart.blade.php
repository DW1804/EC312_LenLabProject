<!DOCTYPE html>
<html class="dark" lang="vi">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Giỏ hàng - LENLAB</title>
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
        body { font-family: 'Spline Sans', sans-serif; background: #1a1a1a; min-height: 100vh; padding-bottom: 100px; }
        .cart-container { background: #1a1a1a; max-width: 400px; margin: 0 auto; min-height: 100vh; }
        .cart-item { background: rgba(45, 45, 45, 0.6); border: 1px solid rgba(255, 255, 255, 0.1); transition: all 0.3s ease; }
        .cart-item:hover { background: rgba(60, 60, 60, 0.8); }
        .quantity-btn { width: 32px; height: 32px; border-radius: 50%; background: rgba(45, 45, 45, 0.8); border: 1px solid rgba(255, 255, 255, 0.2); color: white; display: flex; align-items: center; justify-content: center; transition: all 0.3s ease; }
        .quantity-btn:hover { background: rgba(60, 60, 60, 0.9); border-color: #FAC638; }
        .quantity-btn.add { background: #FAC638; color: #1a1a1a; }
        .quantity-btn.add:hover { background: #f59e0b; }
        .item-checkbox { width: 20px; height: 20px; border-radius: 50%; border: 2px solid rgba(255, 255, 255, 0.3); background: transparent; display: flex; align-items: center; justify-content: center; cursor: pointer; transition: all 0.3s ease; }
        .item-checkbox.checked { background: #FAC638; border-color: #FAC638; }
        .item-checkbox.checked::after { content: '✓'; color: #1a1a1a; font-size: 12px; font-weight: bold; }
        .cart-item.unselected { opacity: 0.6; }
        .voucher-section { background: rgba(45, 45, 45, 0.6); border: 1px solid rgba(255, 255, 255, 0.1); max-width: 100%; overflow: hidden; }
        .voucher-input { background: rgba(26, 26, 26, 0.8); border: 1px solid rgba(255, 255, 255, 0.1); max-width: 100%; overflow: hidden; position: relative; transition: all 0.3s ease; }
        .voucher-input.invalid { border-color: rgba(239, 68, 68, 0.5); background: rgba(26, 26, 26, 0.9); }
        .checkout-btn { background: linear-gradient(135deg, #FAC638, #f59e0b); transition: all 0.3s ease; }
        .checkout-btn:hover { transform: translateY(-2px); box-shadow: 0 8px 25px rgba(250, 198, 56, 0.4); }
        .empty-cart, .loading { text-align: center; padding: 40px 20px; }
    </style>
</head>

<body class="bg-background-dark">
    <div class="cart-container">
        <div class="flex items-center justify-between p-4 border-b border-gray-700">
            <button onclick="window.location.href='/'" class="text-white hover:text-primary transition-colors">
                <span class="material-symbols-outlined text-2xl">arrow_back</span>
            </button>
            <h1 class="text-white font-semibold text-lg">
                Giỏ hàng <span class="text-primary" id="cartCount">(0)</span>
            </h1>
            <button class="text-white hover:text-primary transition-colors">
                <span class="material-symbols-outlined text-2xl">more_vert</span>
            </button>
        </div>

        <div class="p-4" id="cartItemsContainer">
            <div class="loading" id="loadingCart">
                <div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-primary mb-4"></div>
                <p class="text-gray-400">Đang tải giỏ hàng...</p>
            </div>
            
            <div id="cartItems" class="space-y-4"></div>
        </div>

        <div class="px-4 mb-6" id="voucherSection" style="display: none;">
            <div class="voucher-section rounded-xl p-4">
                <div class="flex items-center gap-3 mb-3">
                    <div class="w-8 h-8 bg-primary/20 rounded-full flex items-center justify-center">
                        <span class="material-symbols-outlined text-primary text-sm">local_offer</span>
                    </div>
                    <span class="text-white font-medium">Chọn mã giảm giá</span>
                    <span class="text-primary text-sm font-semibold ml-auto" id="voucherStatus">CHỌN</span>
                </div>
                
                <button onclick="selectVoucher()" class="voucher-input rounded-lg p-3 flex items-center justify-between w-full hover:bg-opacity-80 transition-all">
                    <span class="text-gray-400" id="voucherPlaceholder">Chọn voucher...</span>
                    <span class="material-symbols-outlined text-gray-400">chevron_right</span>
                </button>
                
                <p id="voucherMessage" class="text-sm mt-2 hidden"></p>
                
                <div id="appliedVoucher" class="hidden mt-3 p-3 bg-primary/10 border border-primary/30 rounded-lg">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <span class="material-symbols-outlined text-primary text-sm">check_circle</span>
                            <span class="text-white text-sm font-medium" id="appliedVoucherName"></span>
                        </div>
                        <button onclick="removeVoucher()" class="text-gray-400 hover:text-white transition-colors">
                            <span class="material-symbols-outlined text-sm">close</span>
                        </button>
                    </div>
                    <p class="text-primary text-xs mt-1" id="appliedVoucherDesc"></p>
                </div>
            </div>
        </div>

        <div class="px-4 mb-20" id="summarySection" style="display: none;">
            <div class="space-y-3 mb-6">
                <div class="flex justify-between items-center">
                    <span class="text-gray-400">Tiền hàng</span>
                    <span class="text-white font-semibold" id="subtotal">0đ</span>
                </div>
                
                <div id="discountRow" class="flex justify-between items-center hidden">
                    <span class="text-gray-400">Giảm giá</span>
                    <span class="text-green-400 font-semibold" id="discountAmount">-0đ</span>
                </div>
                
                <hr class="border-gray-700">
                
                <div class="flex justify-between items-center">
                    <span class="text-white text-lg font-bold">Tạm tính</span>
                    <span class="text-primary text-xl font-bold" id="totalAmount">0đ</span>
                </div>
            </div>
        </div>

        <div class="empty-cart hidden" id="emptyCart">
            <div class="w-24 h-24 bg-gray-700 rounded-full flex items-center justify-center mx-auto mb-4">
                <span class="material-symbols-outlined text-gray-500 text-4xl">shopping_cart</span>
            </div>
            <h3 class="text-white text-xl font-semibold mb-2">Giỏ hàng trống</h3>
            <p class="text-gray-400 mb-6">Hãy thêm sản phẩm vào giỏ hàng để tiếp tục mua sắm</p>
            <button onclick="window.location.href='/san-pham'" class="checkout-btn px-8 py-3 rounded-2xl text-background-dark font-bold">
                Mua sắm ngay
            </button>
        </div>
    </div>

    <div class="fixed bottom-0 left-1/2 transform -translate-x-1/2 w-full max-width-400 bg-background-dark/95 backdrop-blur-md border-t border-gray-700 p-4" id="checkoutBar" style="display: none; max-width: 400px;">
        <button onclick="checkout()" class="checkout-btn w-full py-4 rounded-2xl text-background-dark font-bold text-lg flex items-center justify-center gap-2">
            Thanh toán
            <span class="material-symbols-outlined">arrow_forward</span>
        </button>
    </div>

    <script>
        let cart = [];
        let selectedItems = new Set(); // Track selected items
        let discountAmount = 0;
        let discountPercent = 0;
        let availableVouchers = [];
        let appliedVoucher = null;

        $(document).ready(function() {
            loadCart();
            
            // Setup CSRF token
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            // Check if returning from voucher selection
            checkVoucherFromUrl();
        });

        function loadCart() {
            $('#loadingCart').show();
            
            @auth
                $.get('/api/cart', function(response) {
                    cart = response.cart || [];
                    renderCart();
                    updateSummary();
                    $('#loadingCart').hide();
                }).fail(function() {
                    $('#loadingCart').hide();
                    showEmptyCart();
                });
            @else
                // For guest users
                $('#loadingCart').hide();
                showEmptyCart();
            @endauth
        }

        function renderCart() {
            const container = $('#cartItems');
            
            if (cart.length === 0) {
                showEmptyCart();
                return;
            }
            
            // Select all items by default on first load
            if (selectedItems.size === 0) {
                cart.forEach(item => selectedItems.add(item.id));
            }
            
            $('#cartCount').text(`(${cart.length})`);
            $('#voucherSection, #summarySection, #checkoutBar').show();
            $('#emptyCart').hide();
            
            let html = '';
            
            cart.forEach(item => {
                let product, imageUrl, productName, category, price;
                
                if (item.product_type === 'digital' && item.digital_product) {
                    product = item.digital_product;
                    productName = product.name;
                    category = getDigitalProductType(product.type);
                    price = parseFloat(product.price) || 0;
                    imageUrl = product.thumbnail || `https://via.placeholder.com/80x80/FAC638/FFFFFF?text=📄`;
                } else {
                    product = item.product || {};
                    productName = product.name || 'Sản phẩm';
                    category = product.category || '';
                    price = parseFloat(product.price) || 0;
                    imageUrl = product.image && product.image !== 'default.jpg' 
                        ? `/PRODUCT-IMG/${product.image}` 
                        : `https://via.placeholder.com/80x80/FAC638/FFFFFF?text=${encodeURIComponent(productName.substring(0, 2))}`;
                }
                
                // Use variant price if available (for normal products)
                if (item.variant && item.variant.price) {
                    price = parseFloat(item.variant.price);
                }

                const formattedPrice = price.toLocaleString('vi-VN');
                const isSelected = selectedItems.has(item.id);
                
                html += `
                    <div class="cart-item rounded-xl p-4 ${!isSelected ? 'unselected' : ''}" data-item-id="${item.id}">
                        <div class="flex items-center gap-4">
                            <div class="item-checkbox ${isSelected ? 'checked' : ''}" onclick="toggleItemSelection(${item.id})"></div>
                            
                            <img src="${imageUrl}" 
                                 alt="${productName}" 
                                 class="w-20 h-20 object-cover rounded-xl"
                                 onerror="this.src='https://via.placeholder.com/80x80/FAC638/FFFFFF?text=${encodeURIComponent(productName.substring(0, 2))}'">
                            
                            <div class="flex-1">
                                <h3 class="text-white font-semibold mb-1">${productName}</h3>
                                ${getVariantInfo(item, category)}
                                <p class="text-primary font-bold">${formattedPrice}đ</p>
                                ${item.product_type === 'digital' ? '<span class="inline-block px-2 py-1 bg-blue-500/20 text-blue-300 text-xs rounded-full mt-1">Sản phẩm số</span>' : ''}
                            </div>
                            
                            <button onclick="removeFromCart(${item.id})" class="text-gray-400 hover:text-red-400 transition-colors">
                                <span class="material-symbols-outlined">delete</span>
                            </button>
                        </div>
                        
                        <div class="flex items-center justify-end gap-3 mt-4">
                            ${item.product_type === 'digital' ? 
                                `<span class="text-gray-400 text-sm">Số lượng: ${item.quantity}</span>` :
                                `<button onclick="updateQuantity(${item.id}, 'decrease')" class="quantity-btn">
                                    <span class="material-symbols-outlined text-sm">remove</span>
                                </button>
                                <span class="text-white font-semibold min-w-[20px] text-center">${item.quantity}</span>
                                <button onclick="updateQuantity(${item.id}, 'increase')" class="quantity-btn add">
                                    <span class="material-symbols-outlined text-sm">add</span>
                                </button>`
                            }
                        </div>
                    </div>
                `;
            });
            
            container.html(html);
            
            setTimeout(() => {
                if (appliedVoucher) {
                    revalidateVoucher();
                }
            }, 100);
        }

        function getDigitalProductType(type) {
            switch(type) {
                case 'course': return 'Khóa học';
                case 'file': return 'Tài liệu';
                case 'link': return 'Link';
                default: return 'Sản phẩm số';
            }
        }

        function getVariantInfo(item, category = '') {
            let variantHtml = '';
            
            if (item.product_type === 'digital') {
                variantHtml = `<p class="text-gray-400 text-sm mb-2">${category}</p>`;
            } else if (item.variant_info) {
                let variantInfo;
                try {
                    variantInfo = typeof item.variant_info === 'string' 
                        ? JSON.parse(item.variant_info) 
                        : item.variant_info;
                } catch (e) {
                    variantInfo = {};
                }
                
                if (variantInfo.variant_name) {
                    variantHtml = `<p class="text-gray-400 text-sm mb-2">Loại: ${variantInfo.variant_name}</p>`;
                } else if (variantInfo.color || variantInfo.size) {
                     let details = [];
                     if(variantInfo.color) details.push(variantInfo.color);
                     if(variantInfo.size) details.push(variantInfo.size);
                     variantHtml = `<p class="text-gray-400 text-sm mb-2">${details.join(' - ')}</p>`;
                } else {
                    variantHtml = `<p class="text-gray-400 text-sm mb-2">${category}</p>`;
                }
            } else {
                variantHtml = `<p class="text-gray-400 text-sm mb-2">${category}</p>`;
            }
            
            return variantHtml;
        }

        function showEmptyCart() {
            $('#cartCount').text('(0)');
            $('#cartItems, #voucherSection, #summarySection, #checkoutBar').hide();
            $('#emptyCart').removeClass('hidden');
        }

        function updateQuantity(itemId, action) {
            $.post('/api/cart/update', {
                id: itemId,
                action: action
            }, function(response) {
                if (response.success) {
                    loadCart();
                } else {
                    alert(response.message || 'Có lỗi xảy ra');
                }
            }).fail(function() {
                alert('Có lỗi kết nối!');
            });
        }

        function removeFromCart(itemId) {
            if (confirm('Bạn có chắc muốn xóa sản phẩm này khỏi giỏ hàng?')) {
                $.post('/api/cart/delete', {
                    id: itemId
                }, function(response) {
                    if (response.success) {
                        loadCart();
                    } else {
                        alert('Có lỗi xảy ra, vui lòng thử lại!');
                    }
                });
            }
        }

        function toggleItemSelection(itemId) {
            if (selectedItems.has(itemId)) {
                selectedItems.delete(itemId);
            } else {
                selectedItems.add(itemId);
            }
            
            const itemElement = $(`.cart-item[data-item-id="${itemId}"]`);
            const checkbox = itemElement.find('.item-checkbox');
            
            if (selectedItems.has(itemId)) {
                itemElement.removeClass('unselected');
                checkbox.addClass('checked');
            } else {
                itemElement.addClass('unselected');
                checkbox.removeClass('checked');
            }
            
            updateSummary();
            
            if (appliedVoucher) {
                revalidateVoucher();
            }
        }

        function updateSummary() {
            let subtotal = 0;
            let selectedCount = 0;
            
            cart.forEach(item => {
                // MERGED LOGIC: Check selected AND determine correct price
                if (selectedItems.has(item.id)) {
                    let price = 0;
                    
                    if (item.product_type === 'digital' && item.digital_product) {
                        price = parseFloat(item.digital_product.price) || 0;
                    } else if (item.variant && item.variant.price) {
                        price = parseFloat(item.variant.price) || 0;
                    } else if (item.product) {
                        price = parseFloat(item.product.price) || 0;
                    }
                    
                    subtotal += price * item.quantity;
                    selectedCount++;
                }
            });
            
            const discount = discountPercent > 0 ? (subtotal * discountPercent / 100) : discountAmount;
            const total = Math.max(0, subtotal - discount);
            
            $('#subtotal').text(subtotal.toLocaleString('vi-VN') + 'đ');
            $('#totalAmount').text(total.toLocaleString('vi-VN') + 'đ');
            
            const summaryTitle = selectedCount > 0 ? `Tiền hàng (${selectedCount} sản phẩm)` : 'Tiền hàng';
            $('#summarySection .text-gray-400').first().text(summaryTitle);
            
            if (discount > 0) {
                $('#discountRow').removeClass('hidden');
                $('#discountAmount').text('-' + discount.toLocaleString('vi-VN') + 'đ');
            } else {
                $('#discountRow').addClass('hidden');
            }
            
            if (selectedCount > 0) {
                $('#checkoutBar').show();
            } else {
                $('#checkoutBar').hide();
            }
        }

        function selectVoucher() {
            window.location.href = '/vouchers?return=cart';
        }

        function checkVoucherFromUrl() {
            const urlParams = new URLSearchParams(window.location.search);
            const selectedVoucher = urlParams.get('voucher');
            
            if (selectedVoucher) {
                const waitForCartAndApplyVoucher = () => {
                    if (cart.length > 0 && selectedItems.size > 0) {
                        applyVoucher(selectedVoucher);
                    } else {
                        setTimeout(waitForCartAndApplyVoucher, 200);
                    }
                };
                setTimeout(waitForCartAndApplyVoucher, 500);
                window.history.replaceState({}, document.title, window.location.pathname);
            }
        }

        function revalidateVoucher() {
            if (!appliedVoucher) return;
            
            let cartTotal = 0;
            cart.forEach(item => {
                if (selectedItems.has(item.id)) {
                    // Logic tính giá giống updateSummary
                    let price = 0;
                    if (item.product_type === 'digital' && item.digital_product) price = parseFloat(item.digital_product.price);
                    else if (item.variant && item.variant.price) price = parseFloat(item.variant.price);
                    else if (item.product) price = parseFloat(item.product.price);
                    
                    cartTotal += (price || 0) * item.quantity;
                }
            });
            
            const minOrderValue = parseFloat(appliedVoucher.min_order_value) || 0;
            
            if (minOrderValue > 0 && cartTotal < minOrderValue) {
                const needed = minOrderValue - cartTotal;
                showVoucherMessage(`Đơn tối thiểu ${minOrderValue.toLocaleString('vi-VN')}đ. Cần thêm ${needed.toLocaleString('vi-VN')}đ`, 'error');
                
                $('#voucherStatus').text('TẠM KHÓA').removeClass('text-green-400').addClass('text-red-400');
                $('#appliedVoucher').addClass('hidden');
                $('.voucher-input').addClass('invalid');
                
                let voucherDisplay = appliedVoucher.code;
                $('#voucherPlaceholder').text(voucherDisplay + ' (Không đủ điều kiện)').addClass('text-red-400');
                
                discountAmount = 0;
                discountPercent = 0;
                updateSummary();
            } else {
                $('#voucherMessage').addClass('hidden');
                $('#voucherStatus').text('ĐÃ ÁP DỤNG').addClass('text-green-400').removeClass('text-red-400');
                showAppliedVoucher();
                
                if (appliedVoucher.type === 'fixed' || appliedVoucher.type === 'fixed_amount') {
                    discountAmount = parseFloat(appliedVoucher.discount_value);
                    discountPercent = 0;
                } else if (appliedVoucher.type === 'percent' || appliedVoucher.type === 'percentage') {
                    discountPercent = parseFloat(appliedVoucher.discount_value);
                    discountAmount = 0;
                }
                
                updateSummary();
            }
        }

        function applyVoucher(code) {
            if (!code) return;

            let cartTotal = 0;
            cart.forEach(item => {
                if (selectedItems.has(item.id)) {
                    let price = 0;
                    if (item.product_type === 'digital' && item.digital_product) price = parseFloat(item.digital_product.price);
                    else if (item.variant && item.variant.price) price = parseFloat(item.variant.price);
                    else if (item.product) price = parseFloat(item.product.price);
                    cartTotal += (price || 0) * item.quantity;
                }
            });

            if (cartTotal === 0) {
                showVoucherMessage('Chưa chọn sản phẩm nào', 'error');
                return;
            }
            
            $.post('/api/vouchers/apply', {
                code: code, // Controller request uses 'code'
                cart_total: cartTotal
            }, function(response) {
                if (response.success) {
                    // Mapping response to frontend logic
                    appliedVoucher = {
                        code: response.voucher_code,
                        type: response.discount_percent > 0 ? 'percent' : 'fixed',
                        discount_value: response.discount_percent > 0 ? response.discount_percent : response.discount
                    };
                    
                    discountAmount = response.discount;
                    discountPercent = response.discount_percent;
                    
                    showAppliedVoucher();
                    showVoucherMessage('Áp dụng thành công!', 'success');
                    $('#voucherStatus').text('ĐÃ ÁP DỤNG').addClass('text-green-400');
                    updateSummary();
                } else {
                    appliedVoucher = null;
                    discountAmount = 0;
                    discountPercent = 0;
                    showVoucherMessage(response.message, 'error');
                    updateSummary();
                }
            }).fail(function(xhr) {
                let msg = 'Có lỗi xảy ra';
                if(xhr.responseJSON && xhr.responseJSON.message) msg = xhr.responseJSON.message;
                showVoucherMessage(msg, 'error');
            });
        }

        function showAppliedVoucher() {
            if (appliedVoucher) {
                let description = appliedVoucher.type === 'percent' 
                    ? `Giảm ${appliedVoucher.discount_value}%` 
                    : `Giảm ${parseInt(appliedVoucher.discount_value).toLocaleString('vi-VN')}đ`;
                
                $('#appliedVoucherName').text(appliedVoucher.code);
                $('#appliedVoucherDesc').text(description);
                $('#appliedVoucher').removeClass('hidden');
                $('#voucherPlaceholder').text(`${appliedVoucher.code} • ${description}`).removeClass('text-gray-400 text-red-400').addClass('text-white');
                $('.voucher-input').removeClass('invalid');
            }
        }

        function removeVoucher() {
            // Note: Since we use session in controller, we should call API to clear session too if needed
            // But here we reset frontend state
            appliedVoucher = null;
            discountAmount = 0;
            discountPercent = 0;
            
            $('#appliedVoucher').addClass('hidden');
            $('#voucherPlaceholder').text('Chọn voucher...').removeClass('text-white text-red-400').addClass('text-gray-400');
            $('#voucherStatus').text('CHỌN').removeClass('text-green-400 text-red-400').addClass('text-primary');
            $('#voucherMessage').addClass('hidden');
            $('.voucher-input').removeClass('invalid');
            
            updateSummary();
        }

        function showVoucherMessage(message, type) {
            const messageEl = $('#voucherMessage');
            messageEl.text(message)
                    .removeClass('hidden text-green-400 text-red-400')
                    .addClass(type === 'success' ? 'text-green-400' : 'text-red-400');
            setTimeout(() => { messageEl.addClass('hidden'); }, 3000);
        }

        function checkout() {
            if (selectedItems.size === 0) {
                alert('Vui lòng chọn ít nhất một sản phẩm!');
                return;
            }
            
            @auth
                const selectedItemIds = Array.from(selectedItems);
                $.post('/api/checkout/set-selected-items', {
                    selected_items: selectedItemIds
                }, function(response) {
                    window.location.href = '/checkout';
                }).fail(function() {
                    alert('Có lỗi xử lý thanh toán!');
                });
            @else
                if (confirm('Vui lòng đăng nhập để thanh toán?')) {
                    window.location.href = '{{ route("login") }}';
                }
            @endauth
        }
    </script>
</body>
</html>
