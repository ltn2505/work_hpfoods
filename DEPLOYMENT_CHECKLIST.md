# HP FOODS - Deployment Checklist

## Trước khi upload lên hosting:

### 1. Cấu hình Database
- [ ] Cập nhật file `.env` với thông tin database production
- [ ] Đặt `APP_ENV=production`
- [ ] Đặt `APP_DEBUG=false`
- [ ] Đặt `APP_URL=https://yourdomain.com` (hoặc http://yourdomain.com)

### 2. Cấu trúc File
- [ ] Upload toàn bộ project lên hosting
- [ ] Đảm bảo thư mục `public_html` chứa:
  - [ ] File `.htaccess`
  - [ ] File `index.php`
  - [ ] Tất cả assets (CSS, JS, images)

### 3. Thiết lập Database
- [ ] Chạy migrations: `php artisan migrate`
- [ ] Chạy seeders: `php artisan db:seed`
- [ ] Kiểm tra user admin đã tồn tại

### 4. Quyền File
- [ ] Đặt quyền thư mục `storage` là 755
- [ ] Đặt quyền thư mục `bootstrap/cache` là 755
- [ ] Đảm bảo file log có thể ghi được

### 5. Sau khi Deploy
- [ ] Test chức năng đăng nhập
- [ ] Test tạo và quản lý task
- [ ] Test upload file
- [ ] Kiểm tra báo cáo có hoạt động
- [ ] Xác nhận tất cả routes đều truy cập được

## Các lỗi thường gặp & Giải pháp:

### Lỗi 500 Internal Server Error
- Kiểm tra file `.env` có tồn tại và cấu hình đúng
- Xác nhận kết nối database
- Kiểm tra quyền file
- Xem log lỗi tại `storage/logs/laravel.log`

### Lỗi Class Not Found
- Chạy `composer dump-autoload`
- Xóa tất cả cache: `php artisan optimize:clear`
- Kiểm tra thư mục vendor đã upload

### Lỗi Permission Denied
- Đặt quyền file đúng
- Đảm bảo web server có thể ghi vào thư mục storage

## Liên hệ hỗ trợ:
Nếu vẫn gặp vấn đề, kiểm tra Laravel logs tại: `storage/logs/laravel.log`

## Cấu trúc thư mục sau khi deploy:
```
yourdomain.com/
├── public_html/          # Document root của hosting
│   ├── .htaccess
│   ├── index.php
│   ├── favicon.ico
│   ├── css/
│   ├── js/
│   └── storage/
├── app/
├── bootstrap/
├── config/
├── database/
├── resources/
├── routes/
├── storage/
├── vendor/
└── .env
```
