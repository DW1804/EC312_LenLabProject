<!DOCTYPE html>
<html class="dark" lang="vi">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Chuyển khoản ngân hàng - LENLAB</title>
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
        
        .bank-container {
            background: #0f0f0f;
            max-width: 400px;
            margin: 0 auto;
            min-height: 100vh;
        }
        
        .qr-section {
            background: linear-gradient(135deg, #2a2a2a, #1a1a1a);
            border-radius: 20px;
            padding: 24px;
            margin: 20px;
            text-align: center;
        }
        
        .qr-image {
            width: 200px;
            height: 200px;
            background: white;
            border-radius: 16px;
            margin: 0 auto 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }
        
        .qr-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        
        .amount-display {
            color: #FAC638;
            font-size: 32px;
            font-weight: bold;
            margin: 20px 0;
        }
        
        .info-item {
            background: rgba(45, 45, 45, 0.6);
            border-radius: 12px;
            padding: 16px;
            margin: 12px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .copy-btn {
            background: #FAC638;
            color: #0f0f0f;
            border: none;
            border-radius: 50%;
            width: 32px;
            height: 32px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .copy-btn:hover {
            background: #e6b332;
            transform: scale(1.1);
        }
        
        .upload-section {
            background: rgba(45, 45, 45, 0.6);
            border: 2px dashed rgba(255, 255, 255, 0.3);
            border-radius: 12px;
            padding: 24px;
            margin: 20px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .upload-section:hover {
            border-color: #FAC638;
            background: rgba(250, 198, 56, 0.1);
        }
        
        .upload-section.has-file {
            border-color: #FAC638;
            background: rgba(250, 198, 56, 0.1);
        }
        
        .complete-btn {
            background: #FAC638;
            transition: all 0.3s ease;
            border-radius: 25px;
        }
        
        .complete-btn:hover {
            background: #e6b332;
            transform: translateY(-1px);
        }
        
        .complete-btn:disabled {
            opacity: 0.5;
            cursor: not-allowed;
            transform: none;
        }
        
        .save-qr-btn {
            background: rgba(250, 198, 56, 0.2);
            border: 1px solid #FAC638;
            color: #FAC638;
            padding: 8px 16px;
            border-radius: 20px;
            font-size: 14px;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .save-qr-btn:hover {
            background: #FAC638;
            color: #0f0f0f;
        }
        
        .preview-image {
            max-width: 100%;
            max-height: 200px;
            border-radius: 8px;
            margin-top: 12px;
        }
    </style>
</head>

<body class="bg-background-dark">
    <div class="bank-container">
        <!-- Header -->
        <div class="flex items-center justify-between p-4">
            <button onclick="window.location.href='/checkout/payment'" class="text-white hover:text-primary transition-colors">
                <span class="material-symbols-outlined text-2xl">arrow_back</span>
            </button>
            <h1 class="text-white font-semibold text-lg">Chuyển khoản ngân hàng</h1>
            <div class="w-8"></div>
        </div>

        <!-- QR Code Section -->
        <div class="qr-section">
            <div class="qr-image">
                <img src="/QR nhận tiền.jpg" alt="QR Code" 
                     onerror="this.src='https://via.placeholder.com/200x200/FAC638/000000?text=QR+CODE'">
            </div>
            <p class="text-white font-medium mb-2">Quét mã để thanh toán</p>
            <p class="text-gray-400 text-sm mb-4">Sử dụng ứng dụng ngân hàng của bạn</p>
            <button class="save-qr-btn" onclick="saveQRCode()">
                <span class="material-symbols-outlined text-sm mr-1">download</span>
                Lưu mã QR
            </button>
        </div>

        <!-- Amount Breakdown -->
        <div class="px-6 mb-6">
            <div class="bg-surface-dark/60 border border-gray-700 rounded-lg p-4">
                <h3 class="text-white font-semibold mb-4">Chi tiết thanh toán</h3>
                
                <div class="space-y-3">
                    <div class="flex justify-between items-center">
                        <span class="text-gray-300">Tạm tính</span>
                        <span class="text-white">{{ number_format($subtotal) }}đ</span>
                    </div>
                    
                    <div class="flex justify-between items-center">
                        <span class="text-gray-300">Phí vận chuyển</span>
                        <span class="text-white">{{ number_format($shippingFee) }}đ</span>
                    </div>
                    
                    @if(isset($discountAmount) && $discountAmount > 0)
                        <div class="flex justify-between items-center">
                            <span class="text-gray-300">Giảm giá</span>
                            <span class="text-red-400">-{{ number_format($discountAmount) }}đ</span>
                        </div>
                    @endif
                    
                    <hr class="border-gray-600">
                    
                    <div class="flex justify-between items-center pt-2">
                        <span class="text-white font-bold">Tổng cộng</span>
                        <span class="text-primary font-bold text-lg">{{ number_format($total) }}đ</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Amount Display -->
        <div class="text-center mb-6">
            <p class="text-gray-400 text-sm">Số tiền cần thanh toán</p>
            <div class="amount-display">{{ number_format($total) }}đ</div>
        </div>

        <!-- Bank Info -->
        <div class="info-item">
            <div>
                <p class="text-gray-400 text-sm">CHỦ TÀI KHOẢN</p>
                <p class="text-white font-semibold">NGUYEN THANH LONG</p>
            </div>
        </div>

        <div class="info-item">
            <div class="flex-1">
                <p class="text-gray-400 text-sm">SỐ TÀI KHOẢN</p>
                <p class="text-white font-semibold">1040614582</p>
            </div>
            <button class="copy-btn" onclick="copyToClipboard('1040614582', 'Đã sao chép số tài khoản!')">
                <span class="material-symbols-outlined text-sm">content_copy</span>
            </button>
        </div>

        <div class="info-item">
            <div class="flex-1">
                <p class="text-gray-400 text-sm">NỘI DUNG CHUYỂN KHOẢN</p>
                <p class="text-white font-semibold" id="orderCode">{{ $orderCode }}</p>
            </div>
            <button class="copy-btn" onclick="copyToClipboard('{{ $orderCode }}', 'Đã sao chép nội dung chuyển khoản!')">
                <span class="material-symbols-outlined text-sm">content_copy</span>
            </button>
        </div>

        <!-- Notice -->
        <div class="px-6 py-4">
            <div class="flex items-start gap-3">
                <span class="material-symbols-outlined text-blue-400 text-lg mt-0.5">info</span>
                <p class="text-gray-400 text-sm leading-relaxed">
                    Vui lòng chuyển khoản đúng số tiền và nội dung để đơn hàng được xử lý nhanh nhất.
                </p>
            </div>
        </div>

        <!-- Upload Section -->
        <div class="upload-section" onclick="document.getElementById('transferImage').click()" id="uploadSection">
            <div id="uploadContent">
                <span class="material-symbols-outlined text-primary text-4xl mb-3">cloud_upload</span>
                <p class="text-white font-medium mb-2">Tải ảnh minh chứng</p>
                <p class="text-gray-400 text-sm">Vui lòng tải lên ảnh chụp màn hình giao dịch thành công</p>
            </div>
            <div id="previewContent" style="display: none;">
                <img id="previewImage" class="preview-image" alt="Preview">
                <p class="text-primary font-medium mt-2">Đã tải lên ảnh minh chứng</p>
                <p class="text-gray-400 text-sm">Nhấn để thay đổi ảnh khác</p>
            </div>
        </div>

        <input type="file" id="transferImage" accept="image/*" style="display: none;" onchange="handleImageUpload(this)">

        <!-- Fixed Bottom Button -->
        <div class="fixed bottom-0 left-1/2 transform -translate-x-1/2 w-full max-w-[400px] bg-background-dark p-4">
            <button onclick="completeOrder()" class="complete-btn w-full py-4 text-black font-bold text-lg" id="completeBtn" disabled>
                <span class="material-symbols-outlined mr-2">check_circle</span>
                Đã chuyển khoản
            </button>
        </div>
    </div>

    <script>
        let uploadedImage = null;
        let orderCode = '{{ $orderCode }}';

        $(document).ready(function() {
            // Setup CSRF token
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
        });

        function copyToClipboard(text, message) {
            navigator.clipboard.writeText(text).then(function() {
                // Show success message
                showToast(message);
            }).catch(function(err) {
                // Fallback for older browsers
                const textArea = document.createElement('textarea');
                textArea.value = text;
                document.body.appendChild(textArea);
                textArea.select();
                document.execCommand('copy');
                document.body.removeChild(textArea);
                showToast(message);
            });
        }

        function showToast(message) {
            // Create toast element
            const toast = $(`
                <div class="fixed top-4 left-1/2 transform -translate-x-1/2 bg-primary text-black px-4 py-2 rounded-lg font-medium z-50 toast-message">
                    ${message}
                </div>
            `);
            
            $('body').append(toast);
            
            // Remove after 3 seconds
            setTimeout(() => {
                toast.fadeOut(300, function() {
                    $(this).remove();
                });
            }, 3000);
        }

        function saveQRCode() {
            // Create a link to download the QR image
            const link = document.createElement('a');
            link.href = '/QR nhận tiền.jpg';
            link.download = 'QR_Code_LENLAB.jpg';
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
            
            showToast('Đã lưu mã QR thành công!');
        }

        function handleImageUpload(input) {
            if (input.files && input.files[0]) {
                const file = input.files[0];
                
                // Validate file type
                if (!file.type.startsWith('image/')) {
                    alert('Vui lòng chọn file ảnh!');
                    return;
                }
                
                // Validate file size (max 5MB)
                if (file.size > 5 * 1024 * 1024) {
                    alert('Kích thước file không được vượt quá 5MB!');
                    return;
                }
                
                const reader = new FileReader();
                reader.onload = function(e) {
                    // Show preview
                    $('#previewImage').attr('src', e.target.result);
                    $('#uploadContent').hide();
                    $('#previewContent').show();
                    $('#uploadSection').addClass('has-file');
                    
                    // Enable complete button
                    $('#completeBtn').prop('disabled', false);
                    
                    uploadedImage = file;
                };
                reader.readAsDataURL(file);
            }
        }

        function completeOrder() {
            if (!uploadedImage) {
                alert('Vui lòng tải lên ảnh minh chứng chuyển khoản!');
                return;
            }

            // Show loading state
            $('#completeBtn').prop('disabled', true).html(`
                <span class="animate-spin material-symbols-outlined mr-2">refresh</span>
                Đang xử lý...
            `);

            // Create FormData for file upload
            const formData = new FormData();
            formData.append('transfer_image', uploadedImage);
            formData.append('payment_method', 'bank_transfer');
            formData.append('order_code', orderCode);

            $.ajax({
                url: '/api/checkout/complete-bank-transfer',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    if (response.success) {
                        // Show success state
                        $('#completeBtn').removeClass('bg-primary').addClass('bg-green-500').html(`
                            <span class="material-symbols-outlined mr-2">check_circle</span>
                            Đặt hàng thành công!
                        `);
                        
                        showToast('Đặt hàng thành công! Chúng tôi sẽ xác nhận thanh toán trong thời gian sớm nhất.');
                        
                        setTimeout(() => {
                            window.location.href = response.redirect_url;
                        }, 2000);
                    } else {
                        alert('Có lỗi xảy ra: ' + response.message);
                        $('#completeBtn').prop('disabled', false).html(`
                            <span class="material-symbols-outlined mr-2">check_circle</span>
                            Đã chuyển khoản
                        `);
                    }
                },
                error: function(xhr) {
                    let errorMessage = 'Có lỗi xảy ra, vui lòng thử lại!';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMessage = xhr.responseJSON.message;
                    }
                    
                    alert(errorMessage);
                    $('#completeBtn').prop('disabled', false).html(`
                        <span class="material-symbols-outlined mr-2">check_circle</span>
                        Đã chuyển khoản
                    `);
                }
            });
        }
    </script>
</body>
</html>