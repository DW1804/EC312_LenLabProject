# 🗄️ Database Setup Guide

## 📋 Tổng quan
Hướng dẫn thiết lập database cho hệ thống LenLab E-commerce với các tính năng:
- ✅ Quản lý sản phẩm và đơn hàng
- ✅ Hệ thống đánh giá sản phẩm
- ✅ Sản phẩm số (Digital Products)
- ✅ Giỏ hàng và thanh toán
- ✅ Quản lý người dùng và admin

## 🚀 Cài đặt nhanh

### 1. Clone repository
```bash
git clone https://github.com/DW1804/EC312_LenLabProject.git
cd EC312_LenLabProject
```

### 2. Cài đặt dependencies
```bash
composer install
npm install
```

### 3. Cấu hình môi trường
```bash
cp .env.example .env
php artisan key:generate
```

### 4. Cấu hình database trong `.env`
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=lenlab
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

### 5. Tạo database
```sql
CREATE DATABASE lenlab CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

### 6. Chạy migrations
```bash
php artisan migrate
```

### 7. Tạo dữ liệu mẫu
```bash
# Tạo admin mặc định
php artisan db:seed --class=AdminSeeder

# Tạo dữ liệu sản phẩm mẫu
php artisan db:seed --class=ProductSeeder

# Tạo đánh giá mẫu
php artisan db:seed --class=ReviewSeeder
```

### 8. Tạo symbolic link cho storage
```bash
php artisan storage:link
```

### 9. Khởi động server
```bash
php artisan serve
```

## 📊 Cấu trúc Database

### Bảng chính
- `users` - Thông tin khách hàng
- `admins` - Thông tin admin
- `products` - Sản phẩm thường
- `digital_products` - Sản phẩm số
- `orders` - Đơn hàng
- `order_items` - Chi tiết đơn hàng
- `cart` - Giỏ hàng
- `reviews` - Đánh giá sản phẩm
- `settings` - Cấu hình hệ thống

### Bảng hỗ trợ
- `provinces` - Tỉnh/thành phố
- `wards` - Phường/xã
- `addresses` - Địa chỉ giao hàng
- `vouchers` - Mã giảm giá
- `categories` - Danh mục sản phẩm

## 🔧 Migrations quan trọng

### Reviews System (Mới nhất)
```bash
# Migration tạo bảng reviews
2025_12_22_170815_create_reviews_table.php
```

### Digital Products System
```bash
# Migration sản phẩm số
2025_12_22_100000_create_digital_products_table.php
2025_12_22_100001_create_digital_product_purchases_table.php
2025_12_22_110000_add_product_type_to_cart_table.php
```

### Settings System
```bash
# Migration cấu hình UI
2025_12_22_000000_create_settings_table.php
```

## 🎯 Tài khoản mặc định

### Admin
- **Email**: admin@lenlab.com
- **Password**: admin123
- **URL**: `/admin`

### Test User
- **Email**: user@test.com
- **Password**: password
- **URL**: `/`

## 📁 Thư mục Storage

Đảm bảo các thư mục sau có quyền ghi:
```
storage/app/public/
├── products/           # Ảnh sản phẩm
├── digital-products/   # Files sản phẩm số
│   ├── thumbnails/     # Ảnh đại diện
│   └── files/          # Files tải về
├── reviews/            # Ảnh đánh giá
├── avatars/            # Avatar người dùng
└── transfer-images/    # Ảnh chuyển khoản
```

## 🔍 Kiểm tra cài đặt

### 1. Kiểm tra migrations
```bash
php artisan migrate:status
```

### 2. Kiểm tra routes
```bash
php artisan route:list --name=reviews
php artisan route:list --name=digital-products
```

### 3. Kiểm tra storage link
```bash
ls -la public/storage
```

## 🐛 Troubleshooting

### Lỗi Foreign Key
Nếu gặp lỗi foreign key constraint:
```bash
# Tắt foreign key checks tạm thời
SET FOREIGN_KEY_CHECKS=0;
# Chạy migration
php artisan migrate
# Bật lại foreign key checks
SET FOREIGN_KEY_CHECKS=1;
```

### Lỗi Storage Permission
```bash
# Linux/Mac
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache

# Windows (chạy cmd as Administrator)
icacls storage /grant Everyone:F /T
icacls bootstrap/cache /grant Everyone:F /T
```

### Reset Database
```bash
php artisan migrate:fresh --seed
```

## 📈 Dữ liệu mẫu

Sau khi chạy seeders, bạn sẽ có:
- ✅ 1 admin account
- ✅ 5-10 sản phẩm mẫu
- ✅ 3-5 sản phẩm số mẫu
- ✅ 20+ đánh giá mẫu
- ✅ Cấu hình UI cơ bản

## 🔗 Links hữu ích

- **Admin Panel**: `/admin`
- **Reviews Management**: `/admin/reviews`
- **Digital Products**: `/admin/digital-products`
- **UI Configuration**: `/admin/ui-configuration`
- **API Documentation**: `/api/documentation`

## 📞 Hỗ trợ

Nếu gặp vấn đề, vui lòng:
1. Kiểm tra log: `storage/logs/laravel.log`
2. Chạy: `php artisan config:clear && php artisan cache:clear`
3. Tạo issue trên GitHub

---
**Cập nhật lần cuối**: 23/12/2025
**Phiên bản**: v2.0.0 (Review System Update)