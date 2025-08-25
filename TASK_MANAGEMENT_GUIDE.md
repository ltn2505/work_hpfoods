# Hướng dẫn sử dụng Quản lý công việc thống nhất

## Tổng quan

Hệ thống đã được cập nhật để thay thế cơ chế phân loại công việc theo phòng ban bằng một mục quản lý chung với các filter linh hoạt. Điều này giúp tránh vấn đề khi thêm công việc multi-department sẽ làm mất các mục quản lý công việc của các phòng ban không được add.

## Tính năng mới

### 1. Quản lý công việc thống nhất
- **Đường dẫn**: `/admin/tasks` (chỉ Admin)
- **Mô tả**: Giao diện quản lý tất cả công việc trong một màn hình duy nhất
- **Truy cập**: Menu sidebar "Quản lý công việc" (chỉ Admin)

### 2. Bộ lọc linh hoạt

#### Filter theo phòng ban
- Tất cả phòng ban
- Phòng ban cụ thể
- Kết hợp với các filter khác

#### Filter theo trạng thái
- Đang làm
- Chờ duyệt
- Hoàn thành
- Từ chối
- Trễ hạn

#### Filter theo mức độ ưu tiên
- Thấp
- Trung bình
- Cao

#### Filter theo loại công việc
- Đơn phòng ban
- Đa phòng ban

#### Filter theo thời gian
- **Mới nhất**: Sắp xếp theo ngày tạo mới nhất (mặc định)
- **Cũ nhất**: Sắp xếp theo ngày tạo cũ nhất
- **Deadline sớm nhất**: Sắp xếp theo deadline gần nhất
- **Deadline muộn nhất**: Sắp xếp theo deadline xa nhất
- **Trễ hạn**: Chỉ hiển thị công việc trễ hạn

#### Tìm kiếm
- Tìm kiếm theo tiêu đề công việc

### 3. Thống kê tổng quan
- Tổng số công việc
- Số công việc đang làm
- Số công việc hoàn thành
- Số công việc trễ hạn
- Số công việc từ chối
- Số công việc đa phòng ban

### 4. Hiển thị thông tin chi tiết
- Tiêu đề và mô tả
- Người giao việc
- Người nhận việc (hỗ trợ nhiều người)
- Phòng ban (hiển thị rõ đơn/đa phòng ban)
- Deadline (cảnh báo trễ hạn)
- Mức độ ưu tiên
- Trạng thái
- Loại công việc

## Cách sử dụng

### 1. Truy cập quản lý công việc
1. Đăng nhập với tài khoản Admin
2. Vào menu sidebar "Quản lý công việc"
3. Hoặc truy cập trực tiếp `/admin/tasks`

### 2. Sử dụng bộ lọc
1. **Tìm kiếm**: Nhập từ khóa vào ô tìm kiếm
2. **Lọc phòng ban**: Chọn phòng ban cụ thể hoặc để "Tất cả phòng ban"
3. **Lọc trạng thái**: Chọn trạng thái cần xem
4. **Lọc mức độ**: Chọn mức độ ưu tiên
5. **Lọc loại**: Chọn loại công việc (đơn/đa phòng ban)
6. **Sắp xếp**: Chọn cách sắp xếp theo thời gian
7. Nhấn nút "Lọc" để áp dụng

### 3. Xem thông tin chi tiết
- Nhấn nút "👁 Xem" để xem chi tiết công việc
- Nhấn nút "✏️ Sửa" để chỉnh sửa công việc

### 4. Làm mới bộ lọc
- Nhấn nút "Làm mới" để xóa tất cả bộ lọc và quay về mặc định

## Lợi ích của hệ thống mới

### 1. Quản lý tập trung
- Tất cả công việc hiển thị trong một màn hình
- Không bị phân tán theo phòng ban
- Dễ dàng theo dõi và quản lý

### 2. Filter linh hoạt
- Kết hợp nhiều tiêu chí lọc
- Tìm kiếm nhanh chóng
- Sắp xếp theo nhiều tiêu chí

### 3. Tránh mất dữ liệu
- Công việc multi-department không làm mất quản lý của phòng ban khác
- Hiển thị rõ ràng loại công việc
- Dễ dàng phân biệt và quản lý

### 4. Giao diện hiện đại
- Thiết kế responsive
- Thông tin hiển thị rõ ràng
- Dễ dàng thao tác

## Tương thích ngược

Hệ thống vẫn giữ nguyên:
- Giao diện dashboard theo phòng ban (cho tương thích)
- Các chức năng tạo, sửa, xóa công việc
- Quyền hạn và phân quyền
- Cấu trúc dữ liệu

## Hỗ trợ

Nếu gặp vấn đề hoặc cần hỗ trợ:
1. Kiểm tra quyền truy cập (chỉ Admin)
2. Kiểm tra đường dẫn URL
3. Liên hệ quản trị viên hệ thống

## Cập nhật trong tương lai

- Tính năng export dữ liệu
- Thêm các filter nâng cao
- Báo cáo chi tiết
- Dashboard thống kê nâng cao
