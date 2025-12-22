# Hướng dẫn sử dụng UI Configuration

## Tổng quan
Hệ thống UI Configuration cho phép admin tùy chỉnh giao diện website bao gồm:
- Tên website
- Màu sắc chủ đạo (với preview real-time)
- Logo website (tự động resize)
- Favicon (tự động resize)
- Cấu hình thông báo (Email & Browser)

## Các file đã tạo

### Backend
1. **Migration**: `database/migrations/2025_12_22_000000_create_settings_table.php`
   - Tạo bảng `settings` để lưu cấu hình
   - Tự động insert dữ liệu mặc định

2. **Model**: `app/Models/Setting.php`
   - Model với cache support
   - Methods: `get()`, `set()`, `getAll()`, `clearCache()`

3. **Controller**: `app/Http/Controllers/Admin/UIConfigController.php`
   - `index()`: Hiển thị trang cấu hình
   - `update()`: Lưu cấu hình (với validation)
   - `getSettings()`: API lấy settings hiện tại
   - `deleteFile()`: Xóa logo/favicon
   - Tự động resize ảnh bằng GD extension

4. **Helper**: `app/Helpers/SettingsHelper.php`
   - Helper functions để sử dụng settings trong toàn bộ app
   - Tạo dynamic CSS

5. **Service Provider**: `app/Providers/SettingsServiceProvider.php`
   - Share settings với tất cả views
   - Tự động load dynamic CSS

6. **Seeder**: `database/seeders/SettingsSeeder.php`
   - Tạo dữ liệu mặc định cho settings

### Frontend
1. **View**: `resources/views/admin/ui_config.blade.php`
   - Form với tất cả fields
   - Preview cho logo/favicon
   - Toggle switches cho notifications

2. **JavaScript**: `resources/js/ui-config.js`
   - Class-based JavaScript
   - Handle form submission với AJAX
   - File upload với preview
   - Color picker với real-time preview
   - Validation
   - Notifications

3. **CSS**: `resources/css/app.css`
   - Styles cho toggle switches
   - Dynamic primary color classes
   - Animations

### Routes
```php
// View
GET  /admin/ui-configuration

// API
POST   /admin/ui-configuration/update
GET    /admin/ui-configuration/settings
DELETE /admin/ui-configuration/file
GET    /css/dynamic.css
```

## Cách sử dụng

### 1. Truy cập trang cấu hình
- Đăng nhập admin
- Vào menu: **Cấu hình hệ thống** hoặc truy cập `/admin/ui-configuration`

### 2. Thay đổi cấu hình

#### Tên Website
- Nhập tên website mới
- Tối đa 255 ký tự

#### Màu sắc chủ đạo
- Click nút "Thay đổi"
- Chọn màu từ color picker
- Xem preview real-time trên trang

#### Logo Website
- Click vào khung upload hoặc kéo thả file
- Chấp nhận: JPEG, PNG, JPG, SVG
- Max size: 2MB
- Tự động resize về 800x400px (giữ tỷ lệ)
- Hover để xem nút xóa

#### Favicon
- Click vào khung upload nhỏ
- Chấp nhận: PNG, ICO
- Max size: 512KB
- Tự động resize về 32x32px

#### Thông báo
- Toggle ON/OFF cho Email notifications
- Toggle ON/OFF cho Browser notifications

### 3. Lưu thay đổi
- Click nút "Lưu thay đổi"
- Hệ thống sẽ validate và lưu
- Hiển thị notification thành công/lỗi
- Cache tự động được clear

### 4. Hủy bỏ
- Click nút "Hủy bỏ"
- Tất cả thay đổi sẽ được reset về giá trị hiện tại

## Sử dụng Settings trong code

### Trong PHP
```php
use App\Helpers\SettingsHelper;

// Lấy giá trị
$siteName = SettingsHelper::siteName();
$primaryColor = SettingsHelper::primaryColor();
$logoUrl = SettingsHelper::logoUrl();
$faviconUrl = SettingsHelper::faviconUrl();

// Hoặc dùng Model trực tiếp
use App\Models\Setting;

$value = Setting::get('site_name', 'Default');
Setting::set('site_name', 'New Name');
```

### Trong Blade
```blade
{{-- Các biến này tự động available trong tất cả views --}}
{{ $siteName }}
{{ $primaryColor }}
{{ $logoUrl }}
{{ $faviconUrl }}

{{-- Dynamic CSS cũng tự động available --}}
<style>
    {!! $dynamicCss !!}
</style>
```

### Trong JavaScript
```javascript
// Load settings qua API
fetch('/admin/ui-configuration/settings')
    .then(res => res.json())
    .then(data => {
        console.log(data.data.site_name);
        console.log(data.data.primary_color);
    });
```

## Validation Rules

### Tên Website
- Required
- String
- Max 255 characters

### Màu sắc
- Required
- Hex format: #RRGGBB hoặc #RGB
- Regex: `/^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/`

### Logo
- Optional
- Image file
- MIME types: jpeg, png, jpg, svg
- Max size: 2MB (2048KB)
- Auto resize to 800x400px

### Favicon
- Optional
- Image file
- MIME types: png, ico
- Max size: 512KB
- Auto resize to 32x32px

### Notifications
- Boolean (0 or 1)
- Default: email=1, browser=0

## Features

### ✅ Real-time Preview
- Màu sắc thay đổi ngay trên trang
- Không cần reload

### ✅ Auto Resize
- Logo tự động resize về 800x400px
- Favicon tự động resize về 32x32px
- Giữ nguyên aspect ratio
- Hỗ trợ transparency (PNG)

### ✅ Cache Support
- Settings được cache 1 giờ
- Tự động clear cache khi update
- Improve performance

### ✅ Validation
- Frontend validation (JavaScript)
- Backend validation (Laravel)
- Hiển thị lỗi chi tiết

### ✅ File Management
- Upload với preview
- Delete với confirmation
- Tự động xóa file cũ khi upload mới

### ✅ Responsive
- Mobile-friendly
- Sticky header
- Fixed bottom action bar

### ✅ Dark Mode Support
- Tất cả UI hỗ trợ dark mode
- Toggle tự động theo theme

## Troubleshooting

### Lỗi upload file
- Kiểm tra `php.ini`: `upload_max_filesize` và `post_max_size`
- Kiểm tra quyền thư mục `storage/app/uploads`

### Lỗi resize ảnh
- Đảm bảo GD extension được enable trong PHP
- Check: `php -m | grep -i gd`

### Cache không clear
- Chạy: `php artisan cache:clear`
- Hoặc: `Setting::clearCache()`

### CSS không apply
- Build lại: `npm run build`
- Clear browser cache
- Check dynamic CSS route: `/css/dynamic.css`

## Testing

### Test upload
1. Truy cập `/admin/ui-configuration`
2. Upload logo (test với file > 2MB để test validation)
3. Upload favicon
4. Check preview
5. Save và reload trang

### Test color picker
1. Click "Thay đổi" ở màu sắc
2. Chọn màu mới
3. Xem preview real-time
4. Save và check các element khác

### Test notifications
1. Toggle email notifications
2. Toggle browser notifications
3. Save
4. Reload và check state

### Test validation
1. Để trống tên website → Error
2. Nhập màu sai format → Error
3. Upload file quá lớn → Error
4. Upload file sai format → Error

## Next Steps (Optional)

### Thêm settings mới
1. Thêm vào migration hoặc seeder
2. Thêm field vào form
3. Thêm validation rule
4. Update JavaScript nếu cần

### Thêm preview cho logo
- Hiển thị logo trên header
- Update real-time khi upload

### Export/Import settings
- Tạo API export settings as JSON
- Tạo API import settings from JSON

### Settings history
- Track changes
- Rollback feature

## Support
Nếu có vấn đề, hãy check:
1. Laravel logs: `storage/logs/laravel.log`
2. Browser console
3. Network tab (XHR requests)
4. Database: `SELECT * FROM settings`