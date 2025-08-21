# HƯỚNG DẪN SỬ DỤNG TÍNH NĂNG LẶP LẠI CÔNG VIỆC

## 🎯 Tổng quan tính năng

Tính năng lặp lại công việc cho phép Admin và Manager tạo các công việc tự động lặp lại theo lịch trình định sẵn. Hệ thống sẽ tự động reset công việc mỗi 7h sáng theo cài đặt.

## ⚙️ Cài đặt hệ thống

### 1. Chạy migration
```bash
php artisan migrate
```

### 2. Cài đặt cron job (Linux/Unix)
```bash
# Thêm vào crontab
* * * * * cd /path/to/your/project && php artisan schedule:run >> /dev/null 2>&1
```

### 3. Test command
```bash
php artisan tasks:reset-recurring
```

## 🔄 Các loại lặp lại

### 1. **Không lặp lại** (none)
- Công việc chỉ thực hiện một lần
- Không tự động reset

### 2. **Mỗi ngày** (daily)
- Công việc được reset mỗi 24 giờ
- Deadline tự động set cho ngày hôm sau

### 3. **Mỗi 3 ngày** (3_days)
- Công việc được reset mỗi 3 ngày
- Ví dụ: Hôm nay 21/8 → Deadline 23/8 → Reset 24/8

### 4. **Mỗi tuần** (weekly)
- Công việc được reset mỗi 7 ngày
- Deadline tự động set cho tuần tiếp theo

### 5. **Tùy chỉnh** (custom)
- Cho phép chọn ngày bắt đầu và kết thúc
- Hệ thống tự tính số ngày lặp lại

## 📝 Cách sử dụng

### Tạo công việc lặp lại

#### Bước 1: Vào trang tạo công việc
- Truy cập `/create-task`
- Hoặc click "Tạo công việc mới" từ Dashboard

#### Bước 2: Điền thông tin cơ bản
- **Tiêu đề**: Bắt buộc
- **Mô tả**: Chi tiết công việc (tối đa 45 ký tự/từ)
- **Người nhận**: Chọn nhân viên
- **Deadline**: Thời hạn hoàn thành
- **Độ ưu tiên**: Thấp/Trung bình/Cao

#### Bước 3: Cài đặt lặp lại
- **Lặp lại công việc**: Chọn loại lặp lại
- **Ngày bắt đầu**: Chỉ hiển thị khi chọn "Tùy chỉnh"
- **Ngày kết thúc**: Chỉ hiển thị khi chọn "Tùy chỉnh"

#### Bước 4: Upload file và giao việc
- Upload file đính kèm (nếu có)
- Click "🚀 Giao việc"

### Ví dụ cụ thể

#### Công việc dọn dẹp văn phòng
- **Tiêu đề**: Dọn dẹp văn phòng
- **Lặp lại**: Mỗi ngày
- **Deadline**: 22/8 17:00
- **Kết quả**: 
  - 21/8: Deadline 22/8 17:00
  - 22/8 07:00: Tự động reset → Deadline 23/8 17:00
  - 23/8 07:00: Tự động reset → Deadline 24/8 17:00

#### Công việc báo cáo tuần
- **Tiêu đề**: Báo cáo tuần
- **Lặp lại**: Mỗi tuần
- **Deadline**: 25/8 17:00
- **Kết quả**:
  - 18/8: Deadline 25/8 17:00
  - 25/8 07:00: Tự động reset → Deadline 1/9 17:00
  - 1/9 07:00: Tự động reset → Deadline 8/9 17:00

## 🕐 Xử lý thời gian làm lại

### Khi công việc bị từ chối

#### Bước 1: Admin/Manager set thời gian làm lại
- Vào chi tiết công việc bị từ chối
- Click "Set thời gian làm lại"
- Chọn số giờ (1-168 giờ, tối đa 7 ngày)

#### Bước 2: Nhân viên làm lại
- Nhân viên nhận thông báo thời gian làm lại
- Phải hoàn thành trong thời gian quy định
- Nếu hết hạn: Tự động chuyển về "Đang làm"

#### Bước 3: Hệ thống xử lý
- Đếm ngược thời gian làm lại
- Tự động chuyển trạng thái khi hết hạn
- Ghi log hoạt động

## 🎛️ Quản lý công việc lặp lại

### Tạm dừng/Tiếp tục
- **Tạm dừng**: Công việc không tự động reset
- **Tiếp tục**: Công việc tiếp tục lặp lại theo lịch

### Thay đổi cài đặt
- Chỉnh sửa loại lặp lại
- Thay đổi ngày bắt đầu/kết thúc
- Cập nhật deadline

### Xem lịch sử
- Theo dõi các lần reset
- Xem thời gian làm lại
- Kiểm tra trạng thái lặp lại

## ⚠️ Lưu ý quan trọng

### 1. **Thời gian reset**
- Hệ thống chạy lúc 7h sáng mỗi ngày
- Chỉ reset công việc có trạng thái lặp lại "active"
- Công việc đang trong thời gian làm lại không bị reset

### 2. **Quyền hạn**
- **Admin**: Quản lý tất cả công việc lặp lại
- **Manager**: Chỉ quản lý công việc trong phòng ban
- **Employee**: Chỉ xem và thực hiện công việc được giao

### 3. **Trạng thái công việc**
- **in_progress**: Đang thực hiện
- **completed**: Hoàn thành, chờ duyệt
- **rejected**: Bị từ chối, cần làm lại
- **finished**: Kết thúc (không lặp lại)
- **overdue**: Trễ hạn

### 4. **Xử lý deadline**
- Deadline tự động tính theo loại lặp lại
- Công việc custom: Deadline = Ngày bắt đầu + Số ngày
- Công việc định kỳ: Deadline = Ngày hiện tại + Khoảng thời gian

## 🔧 Troubleshooting

### Công việc không tự động reset
1. Kiểm tra trạng thái lặp lại có phải "active" không
2. Kiểm tra cron job có chạy không
3. Chạy command thủ công: `php artisan tasks:reset-recurring`
4. Xem log: `tail -f storage/logs/recurring-tasks.log`

### Lỗi validation
1. Kiểm tra ngày bắt đầu/kết thúc hợp lệ
2. Đảm bảo ngày kết thúc sau ngày bắt đầu
3. Kiểm tra định dạng ngày (YYYY-MM-DD)

### Lỗi permission
1. Kiểm tra role của user
2. Kiểm tra quyền theo phòng ban
3. Liên hệ admin để cấp quyền

## 📊 Monitoring và báo cáo

### Log files
- **recurring-tasks.log**: Log của command reset
- **laravel.log**: Log chung của ứng dụng

### Dashboard
- Hiển thị số lượng công việc lặp lại
- Trạng thái các công việc lặp lại
- Thống kê theo loại lặp lại

### Email notifications
- Thông báo khi công việc được reset
- Cảnh báo công việc trễ hạn
- Nhắc nhở thời gian làm lại

## 🚀 Tính năng nâng cao

### 1. **Lặp lại theo ngày trong tuần**
- Chỉ lặp lại vào thứ 2, 4, 6
- Lặp lại vào cuối tuần

### 2. **Lặp lại theo tháng**
- Lặp lại vào ngày đầu tháng
- Lặp lại vào ngày cuối tháng

### 3. **Lặp lại theo mùa**
- Lặp lại theo quý
- Lặp lại theo năm

### 4. **Lặp lại có điều kiện**
- Chỉ lặp lại khi hoàn thành đúng hạn
- Lặp lại theo hiệu suất nhân viên

## 📞 Hỗ trợ kỹ thuật

### Liên hệ admin
- Email: admin@hpfoods.local
- Phone: [Số điện thoại admin]

### Báo cáo lỗi
- Tạo issue trên GitHub
- Gửi email: bugs@hpfoods.local

### Tài liệu tham khảo
- [Laravel Scheduling](https://laravel.com/docs/10.x/scheduling)
- [Carbon Date/Time](https://carbon.nesbot.com/)
- [Cron Job Examples](https://crontab.guru/)

---

*Tài liệu này được cập nhật lần cuối: {{ date('d/m/Y H:i:s') }}*
*Phiên bản: 1.0*
