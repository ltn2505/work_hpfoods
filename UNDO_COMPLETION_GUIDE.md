# HƯỚNG DẪN SỬ DỤNG TÍNH NĂNG HOÀN TÁC CÔNG VIỆC

## 🎯 Tổng quan tính năng

Tính năng hoàn tác cho phép nhân viên hoàn tác trạng thái công việc từ "Hoàn thành" về "Đang làm" trong trường hợp click nhầm hoặc thực sự chưa hoàn thành công việc.

## ⏰ Thời gian hoàn tác

### Giới hạn thời gian
- **Thời gian cho phép**: 3 tiếng (180 phút) kể từ khi hoàn thành
- **Sau 3 tiếng**: Không thể hoàn tác, công việc sẽ ở trạng thái "Chờ duyệt"
- **Hiển thị**: Số giờ còn lại để hoàn tác được hiển thị trên nút

### Ví dụ thời gian
- **Hoàn thành lúc**: 14:00
- **Có thể hoàn tác đến**: 17:00
- **Sau 17:00**: Không thể hoàn tác

## 👤 Quyền hoàn tác

### Ai có thể hoàn tác
- **Người được giao việc**: Có thể hoàn tác công việc của mình
- **Người giao việc**: Không thể hoàn tác
- **Admin/Manager**: Không thể hoàn tác (chỉ có thể duyệt/từ chối)

### Điều kiện hoàn tác
1. Công việc phải ở trạng thái "completed" (Chờ duyệt)
2. Phải trong vòng 3 tiếng kể từ khi hoàn thành
3. Chỉ người được giao việc mới có thể hoàn tác

## 📱 Cách sử dụng

### Bước 1: Xác định công việc cần hoàn tác
- Vào trang chi tiết công việc
- Kiểm tra trạng thái: "Chờ duyệt" (completed)
- Kiểm tra thời gian hoàn thành

### Bước 2: Thực hiện hoàn tác
1. **Tìm nút hoàn tác**: Trong phần "Hành động"
2. **Kiểm tra thời gian**: Nút hiển thị số giờ còn lại
3. **Click hoàn tác**: Nút có icon ⏪ và text "Hoàn tác (Xh còn lại)"
4. **Xác nhận**: Click "OK" trong hộp thoại xác nhận

### Bước 3: Xác nhận hoàn tác
- Hộp thoại xác nhận: "Bạn có chắc muốn hoàn tác công việc này?"
- Click "OK" để hoàn tác
- Click "Cancel" để hủy

## 🔄 Quy trình hoàn tác

### Trước khi hoàn tác
```
Trạng thái: Đang làm (in_progress)
↓
Nhân viên hoàn thành → Trạng thái: Chờ duyệt (completed)
↓
Ghi lại thời gian hoàn thành (completed_at)
```

### Sau khi hoàn tác
```
Trạng thái: Chờ duyệt (completed)
↓
Nhân viên hoàn tác → Trạng thái: Đang làm (in_progress)
↓
Xóa thời gian hoàn thành (completed_at = null)
↓
Ghi log hoạt động
```

## 📍 Vị trí hiển thị

### Trong task-detail.blade.php
- **Vị trí**: Phần "Hành động" bên phải
- **Hiển thị**: Chỉ khi status = 'completed' và canUndo() = true
- **Style**: Nút màu vàng với icon ⏪

### Trong tasks/show.blade.php
- **Vị trí**: Phần "Hành động" bên phải
- **Hiển thị**: Chỉ khi status = 'completed' và canUndo() = true
- **Style**: Nút action-btn-warning với icon ⏪

## ⚠️ Lưu ý quan trọng

### 1. **Thời gian hoàn tác**
- Chỉ có thể hoàn tác trong vòng 3 tiếng
- Sau 3 tiếng, nút hoàn tác sẽ biến mất
- Hiển thị cảnh báo "Không thể hoàn tác sau 3 tiếng"

### 2. **Quyền hoàn tác**
- Chỉ người được giao việc mới có thể hoàn tác
- Admin/Manager không thể hoàn tác
- Người giao việc không thể hoàn tác

### 3. **Trạng thái công việc**
- Chỉ có thể hoàn tác từ trạng thái "completed"
- Không thể hoàn tác từ trạng thái khác
- Sau khi hoàn tác, trạng thái về "in_progress"

### 4. **Log hoạt động**
- Mọi lần hoàn tác đều được ghi log
- Log hiển thị: "Hoàn tác trạng thái hoàn thành về 'Đang làm'"
- Thời gian hoàn tác được ghi lại

## 🔧 Xử lý lỗi

### Lỗi thường gặp

#### 1. **Không thể hoàn tác**
**Triệu chứng**: Nút hoàn tác không hiển thị hoặc bị disable
**Nguyên nhân**:
- Đã quá 3 tiếng kể từ khi hoàn thành
- Không phải người được giao việc
- Trạng thái không phải "completed"

**Giải pháp**:
- Kiểm tra thời gian hoàn thành
- Kiểm tra quyền hoàn tác
- Liên hệ admin/manager để hỗ trợ

#### 2. **Lỗi khi submit form**
**Triệu chứng**: Hiển thị lỗi validation
**Nguyên nhân**:
- CSRF token hết hạn
- Session hết hạn
- Quyền bị thay đổi

**Giải pháp**:
- Refresh trang
- Đăng nhập lại
- Kiểm tra quyền

### Debug và kiểm tra

#### 1. **Kiểm tra thời gian**
```php
// Trong Task model
$task->completed_at; // Thời gian hoàn thành
$task->canUndo();    // Có thể hoàn tác không
```

#### 2. **Kiểm tra quyền**
```php
// Trong view
@if($task->assignee_id == auth()->id() && $task->canUndo())
    // Hiển thị nút hoàn tác
@endif
```

#### 3. **Kiểm tra log**
```bash
# Xem log hoạt động
tail -f storage/logs/laravel.log
```

## 📊 Monitoring và báo cáo

### Thống kê hoàn tác
- Số lần hoàn tác trong ngày/tuần/tháng
- Tỷ lệ công việc bị hoàn tác
- Thời gian trung bình từ hoàn thành đến hoàn tác

### Dashboard
- Hiển thị công việc có thể hoàn tác
- Cảnh báo thời gian hoàn tác sắp hết
- Thống kê theo phòng ban

### Email notifications
- Thông báo khi công việc được hoàn tác
- Cảnh báo thời gian hoàn tác sắp hết
- Báo cáo định kỳ về hoạt động hoàn tác

## 🚀 Tính năng nâng cao

### 1. **Hoàn tác có điều kiện**
- Chỉ cho phép hoàn tác nếu chưa có comment từ manager
- Giới hạn số lần hoàn tác cho mỗi công việc
- Hoàn tác theo lịch trình (chỉ trong giờ làm việc)

### 2. **Hoàn tác hàng loạt**
- Hoàn tác nhiều công việc cùng lúc
- Hoàn tác theo phòng ban
- Hoàn tác theo loại công việc

### 3. **Hoàn tác thông minh**
- AI phân tích lý do hoàn tác
- Gợi ý cải thiện quy trình
- Dự đoán công việc có thể bị hoàn tác

## 📞 Hỗ trợ kỹ thuật

### Liên hệ admin
- Email: admin@hpfoods.local
- Phone: [Số điện thoại admin]

### Báo cáo lỗi
- Tạo issue trên GitHub
- Gửi email: bugs@hpfoods.local

### Tài liệu tham khảo
- [Laravel Validation](https://laravel.com/docs/10.x/validation)
- [Carbon Date/Time](https://carbon.nesbot.com/)
- [Laravel Middleware](https://laravel.com/docs/10.x/middleware)

---

*Tài liệu này được cập nhật lần cuối: {{ date('d/m/Y H:i:s') }}*
*Phiên bản: 1.0*
