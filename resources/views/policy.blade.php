<!DOCTYPE html>
<html class="dark" lang="vi">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Điều khoản & Chính sách - LENLAB</title>
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
        
        .policy-container {
            background: #0f0f0f;
            max-width: 400px;
            margin: 0 auto;
            min-height: 100vh;
        }
        
        .policy-section {
            background: rgba(45, 45, 45, 0.6);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 16px;
            padding: 20px;
            margin-bottom: 16px;
        }
        
        .policy-icon {
            width: 32px;
            height: 32px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            margin-bottom: 12px;
        }
        
        .orange-icon {
            background: linear-gradient(135deg, #f97316, #ea580c);
            color: white;
        }
        
        .blue-icon {
            background: linear-gradient(135deg, #3b82f6, #2563eb);
            color: white;
        }
        
        .purple-icon {
            background: linear-gradient(135deg, #8b5cf6, #7c3aed);
            color: white;
        }
        
        .green-icon {
            background: linear-gradient(135deg, #10b981, #059669);
            color: white;
        }
        
        .gray-icon {
            background: linear-gradient(135deg, #6b7280, #4b5563);
            color: white;
        }
        
        .yellow-icon {
            background: linear-gradient(135deg, #eab308, #ca8a04);
            color: white;
        }
        
        .red-icon {
            background: linear-gradient(135deg, #ef4444, #dc2626);
            color: white;
        }
        
        .understand-btn {
            background: linear-gradient(135deg, #FAC638, #f59e0b);
            transition: all 0.3s ease;
            position: fixed;
            bottom: 20px;
            left: 50%;
            transform: translateX(-50%);
            width: calc(100% - 40px);
            max-width: 360px;
        }
        
        .understand-btn:hover {
            transform: translateX(-50%) translateY(-2px);
            box-shadow: 0 8px 25px rgba(250, 198, 56, 0.4);
        }
        
        .policy-list {
            list-style: none;
            padding-left: 0;
        }
        
        .policy-list li {
            position: relative;
            padding-left: 20px;
            margin-bottom: 8px;
            color: #d1d5db;
            font-size: 14px;
            line-height: 1.5;
        }
        
        .policy-list li::before {
            content: '•';
            position: absolute;
            left: 0;
            color: #FAC638;
            font-weight: bold;
        }
    </style>
</head>

<body class="bg-background-dark">
    <div class="policy-container">
        <!-- Header -->
        <div class="flex items-center justify-between p-4 border-b border-gray-700">
            <button onclick="goBack()" class="text-white hover:text-primary transition-colors">
                <span class="material-symbols-outlined text-2xl">arrow_back</span>
            </button>
            <h1 class="text-white font-semibold text-lg">Điều khoản & Chính sách</h1>
            <div class="w-8"></div>
        </div>

        <!-- Policy Content -->
        <div class="p-4 pb-24">
            <!-- Điều khoản dịch vụ -->
            <div class="policy-section">
                <div class="policy-icon orange-icon">
                    <span class="material-symbols-outlined">description</span>
                </div>
                <h3 class="text-white font-semibold text-lg mb-3">Điều khoản & Chính sách</h3>
                <p class="text-gray-300 text-sm mb-3">
                    Chào mừng bạn đến với Tiệm Len Nhỏ. Khi sử dụng dịch vụ của chúng tôi, bạn đồng ý tuân thủ các điều khoản và điều kiện sau đây. Vui lòng đọc kỹ trước khi sử dụng dịch vụ để hiểu rõ quyền và trách nhiệm của bạn.
                </p>
            </div>

            <!-- Điều khoản sử dụng chung -->
            <div class="policy-section">
                <div class="policy-icon blue-icon">
                    <span class="material-symbols-outlined">rule</span>
                </div>
                <h3 class="text-white font-semibold text-lg mb-3">Điều khoản sử dụng chung</h3>
                <p class="text-gray-300 text-sm mb-3">
                    Bằng việc truy cập và sử dụng ứng dụng Tiệm Len Nhỏ, bạn xác nhận rằng bạn đã đọc, hiểu và đồng ý tuân thủ tất cả các điều khoản và điều kiện được quy định trong tài liệu này. Nếu bạn không đồng ý với bất kỳ điều khoản nào, vui lòng ngừng sử dụng dịch vụ của chúng tôi ngay lập tức.
                </p>
                <p class="text-white font-medium text-sm mb-2">Quy định sử dụng:</p>
                <ul class="policy-list">
                    <li>Không sử dụng dịch vụ cho mục đích bất hợp pháp hoặc trái phép</li>
                    <li>Không can thiệp vào hoạt động bình thường của hệ thống</li>
                    <li>Không sao chép, phân phối nội dung mà không có sự cho phép</li>
                    <li>Tuân thủ các quy định pháp luật hiện hành</li>
                    <li>Cung cấp thông tin chính xác khi đăng ký tài khoản</li>
                </ul>
            </div>

            <!-- Chính sách bảo mật -->
            <div class="policy-section">
                <div class="policy-icon purple-icon">
                    <span class="material-symbols-outlined">security</span>
                </div>
                <h3 class="text-white font-semibold text-lg mb-3">Chính sách bảo mật</h3>
                <p class="text-gray-300 text-sm mb-3">
                    Chúng tôi cam kết bảo vệ thông tin cá nhân của bạn. Dữ liệu của bạn được mã hóa và lưu trữ an toàn. Chúng tôi không chia sẻ thông tin cá nhân với bên thứ ba mà không có sự đồng ý của bạn, trừ khi được yêu cầu bởi pháp luật.
                </p>
                <ul class="policy-list">
                    <li>Thu thập thông tin cần thiết để cung cấp dịch vụ</li>
                    <li>Bảo mật thông tin bằng công nghệ mã hóa tiên tiến</li>
                    <li>Không chia sẻ thông tin với bên thứ ba không được ủy quyền</li>
                    <li>Quyền truy cập và chỉnh sửa thông tin cá nhân của bạn</li>
                </ul>
            </div>

            <!-- Vận chuyển & Giao nhận -->
            <div class="policy-section">
                <div class="policy-icon green-icon">
                    <span class="material-symbols-outlined">local_shipping</span>
                </div>
                <h3 class="text-white font-semibold text-lg mb-3">Vận chuyển & Giao nhận</h3>
                <p class="text-gray-300 text-sm mb-3">
                    Thời gian giao hàng từ 1-7 ngày tùy theo khu vực. Vui lòng kiểm tra thông tin đơn hàng trước khi xác nhận. Phí vận chuyển được tính theo khoảng cách và trọng lượng đơn hàng.
                </p>
                <ul class="policy-list">
                    <li>Giao hàng toàn quốc</li>
                    <li>Thời gian giao hàng: 1-7 ngày làm việc</li>
                    <li>Phí vận chuyển tính theo khu vực</li>
                    <li>Hỗ trợ theo dõi đơn hàng online</li>
                </ul>
            </div>

            <!-- Cam kết Sản phẩm -->
            <div class="policy-section">
                <div class="policy-icon gray-icon">
                    <span class="material-symbols-outlined">verified</span>
                </div>
                <h3 class="text-white font-semibold text-lg mb-3">Cam kết Sản phẩm</h3>
                <p class="text-gray-300 text-sm mb-3">
                    Chúng tôi cam kết 100% len thật, chất lượng cao và an toàn cho người dùng. Tất cả sản phẩm đều được kiểm tra kỹ lưỡng trước khi giao hàng.
                </p>
                <ul class="policy-list">
                    <li>100% len thật, chất lượng cao</li>
                    <li>Kiểm tra chất lượng trước khi xuất kho</li>
                    <li>Đảm bảo màu sắc và kích thước như mô tả</li>
                    <li>Hỗ trợ tư vấn sản phẩm miễn phí</li>
                </ul>
            </div>

            <!-- Ưu đãi -->
            <div class="policy-section">
                <div class="policy-icon yellow-icon">
                    <span class="material-symbols-outlined">local_offer</span>
                </div>
                <h3 class="text-white font-semibold text-lg mb-3">Ưu đãi</h3>
                <p class="text-gray-300 text-sm mb-3">
                    Thời gian áp dụng từ ngày 01/01/2024 đến hết ngày 31/12/2024. Ưu đãi có thể thay đổi mà không cần báo trước. Không áp dụng đồng thời với các chương trình khuyến mãi khác.
                </p>
                <ul class="policy-list">
                    <li>Ưu đãi áp dụng theo thời gian quy định</li>
                    <li>Không kết hợp với chương trình khác</li>
                    <li>Có thể thay đổi mà không báo trước</li>
                    <li>Áp dụng cho khách hàng mới và cũ</li>
                </ul>
            </div>

            <!-- Đổi trả & Hoàn tiền -->
            <div class="policy-section">
                <div class="policy-icon red-icon">
                    <span class="material-symbols-outlined">currency_exchange</span>
                </div>
                <h3 class="text-white font-semibold text-lg mb-3">Đổi trả & Hoàn tiền</h3>
                <p class="text-gray-300 text-sm mb-3">
                    Hỗ trợ đổi trả trong vòng 7 ngày kể từ ngày nhận hàng nếu sản phẩm bị lỗi do nhà sản xuất. Sản phẩm đổi trả phải còn nguyên vẹn, chưa qua sử dụng.
                </p>
                <div class="grid grid-cols-2 gap-4 mt-4">
                    <div>
                        <p class="text-primary font-medium text-sm mb-2">ĐƯỢC ĐỔI TRẢ</p>
                        <ul class="policy-list text-xs">
                            <li>Lỗi do nhà sản xuất</li>
                            <li>Sai màu sắc</li>
                            <li>Sai kích thước</li>
                            <li>Hàng bị hư hỏng</li>
                        </ul>
                    </div>
                    <div>
                        <p class="text-red-400 font-medium text-sm mb-2">KHÔNG ĐỔI TRẢ</p>
                        <ul class="policy-list text-xs">
                            <li>Đã qua sử dụng</li>
                            <li>Quá 7 ngày</li>
                            <li>Không còn tem mác</li>
                            <li>Hư hỏng do người dùng</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <!-- Fixed Bottom Button -->
        <button onclick="goBack()" class="understand-btn py-4 rounded-2xl text-background-dark font-bold text-lg flex items-center justify-center gap-2">
            <span class="material-symbols-outlined">check_circle</span>
            Đã hiểu
        </button>
    </div>

    <script>
        function goBack() {
            // Check if there's a referrer or previous page
            if (document.referrer && document.referrer !== window.location.href) {
                window.history.back();
            } else {
                // Default fallback to home page
                window.location.href = '/';
            }
        }
    </script>
</body>
</html>