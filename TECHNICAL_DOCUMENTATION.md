# TÀI LIỆU KỸ THUẬT - HỆ THỐNG QUẢN LÝ CÔNG VIỆC HP FOODS

## 📋 MỤC LỤC
1. [Tổng quan hệ thống](#tổng-quan-hệ-thống)
2. [Cài đặt và cấu hình](#cài-đặt-và-cấu-hình)
3. [Cấu trúc database](#cấu-trúc-database)
4. [Hệ thống xác thực](#hệ-thống-xác-thực)
5. [Phân quyền và vai trò](#phân-quyền-và-vai-trò)
6. [Chức năng theo từng role](#chức-năng-theo-từng-role)
7. [API và Routes](#api-và-routes)
8. [Tính năng lặp lại công việc](#tính-năng-lặp-lại-công-việc)
9. [Tính năng hoàn tác hoàn thành](#tính-năng-hoàn-tác-hoàn-thành)
10. [Validation và bảo mật](#validation-và-bảo-mật)
11. [Việt hóa giao diện](#việt-hóa-giao-diện)
12. [Hướng dẫn sử dụng](#hướng-dẫn-sử-dụng)
13. [Troubleshooting](#troubleshooting)

---

## 🏗️ TỔNG QUAN HỆ THỐNG

### Mô tả
Hệ thống Quản lý Công việc HP Foods là một ứng dụng web được xây dựng trên nền tảng Laravel, giúp quản lý và theo dõi các công việc trong tổ chức một cách hiệu quả.

### Công nghệ sử dụng
- **Backend**: Laravel 10.x
- **Frontend**: Blade Templates + Bootstrap 5 + Tailwind CSS
- **Database**: MySQL/PostgreSQL
- **Authentication**: Laravel Breeze
- **File Upload**: Laravel Storage
- **Validation**: Client-side + Server-side
- **Scheduling**: Laravel Console Commands + Cron Jobs
- **Localization**: Vietnamese language support

### Kiến trúc hệ thống
- **MVC Pattern**: Model-View-Controller
- **Middleware**: Role-based access control
- **Database**: Relational database với migrations
- **File Management**: Centralized storage system

---

## ⚙️ CÀI ĐẶT VÀ CẤU HÌNH

### Yêu cầu hệ thống
- PHP >= 8.1
- Composer
- MySQL >= 5.7 hoặc PostgreSQL >= 10
- Node.js & NPM (cho frontend assets)

### Cài đặt
```bash
# Clone repository
git clone [repository-url]
cd work_hpfoods

# Cài đặt dependencies
composer install
npm install

# Cấu hình môi trường
cp .env.example .env
php artisan key:generate

# Cấu hình database trong .env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=hpfoods_db
DB_USERNAME=root
DB_PASSWORD=

# Chạy migrations và seeders
php artisan migrate:fresh --seed

# Tạo storage link
php artisan storage:link

# Build assets
npm run build
```

### Cấu hình môi trường
```env
APP_NAME="HP Foods Task Management"
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=hpfoods_db
DB_USERNAME=root
DB_PASSWORD=

FILESYSTEM_DISK=public
```

---

## 🗄️ CẤU TRÚC DATABASE

### Bảng chính

#### 1. Bảng `users`
```sql
- id (Primary Key)
- name (VARCHAR)
- email (VARCHAR, unique)
- phone (VARCHAR, unique)
- password (VARCHAR, hashed)
- role (ENUM: 'admin', 'manager', 'employee')
- department_id (Foreign Key)
- email_verified_at (TIMESTAMP)
- created_at, updated_at
```

#### 2. Bảng `departments`
```sql
- id (Primary Key)
- name (VARCHAR)
- created_at, updated_at
```

#### 3. Bảng `tasks`
```sql
- id (Primary Key)
- title (VARCHAR)
- description (TEXT)
- status (ENUM: 'in_progress', 'completed', 'rejected', 'overdue', 'finished')
- priority (ENUM: 'low', 'medium', 'high')
- attachments (JSON)
- department_id (Foreign Key)
- assignee_id (Foreign Key)
- creator_id (Foreign Key)
- deadline (TIMESTAMP)
- rejection_reason (TEXT)
- finish_note (TEXT)
- recurring_type (ENUM: 'none', 'daily', '3_days', 'weekly', 'custom')
- recurring_start_date (DATE)
- recurring_end_date (DATE)
- recurring_days (INTEGER)
- last_reset_date (DATE)
- recurring_status (ENUM: 'active', 'paused', 'completed')
- rework_hours (INTEGER)
- rework_deadline (TIMESTAMP)
- created_at, updated_at
```

#### 4. Bảng `task_activities`
```sql
- id (Primary Key)
- task_id (Foreign Key)
- user_id (Foreign Key)
- meta (TEXT)
- created_at, updated_at
```

### Relationships
- `User` ↔ `Department`: Many-to-One
- `User` ↔ `Task` (assignee): One-to-Many
- `User` ↔ `Task` (creator): One-to-Many
- `Task` ↔ `Department`: Many-to-One
- `Task` ↔ `TaskActivity`: One-to-Many

---

## 🔐 HỆ THỐNG XÁC THỰC

### Laravel Breeze Integration
Hệ thống sử dụng Laravel Breeze để xử lý xác thực người dùng với các tính năng:

#### Đăng ký (Register)
- **Route**: `POST /register`
- **Controller**: `RegisteredUserController@store`
- **Validation**:
  - Name: required, string, max:255
  - Email: required, email, unique, max:255
  - Phone: required_without:email, unique, max:20
  - Password: required, confirmed, min:8

#### Đăng nhập (Login)
- **Route**: `POST /login`
- **Controller**: `AuthenticatedSessionController@store`
- **Validation**:
  - Email/Phone: required
  - Password: required

#### Quên mật khẩu (Forgot Password)
- **Route**: `POST /forgot-password`
- **Controller**: `ForgotPasswordController@sendResetLink`
- **Tính năng**: 
  - Nhập email hoặc số điện thoại để xác minh tài khoản
  - Kiểm tra tài khoản tồn tại trong database
  - Chuyển hướng đến form đặt lại mật khẩu
- **Validation**: Tài khoản phải tồn tại trong hệ thống
- **Bảo mật**: Sử dụng session để tracking, không cần token

#### Đặt lại mật khẩu (Reset Password)
- **Route**: `POST /reset-password`
- **Controller**: `ForgotPasswordController@resetPassword`
- **Tính năng**:
  - Nhập mật khẩu mới và xác nhận
  - Kiểm tra session hợp lệ
  - Cập nhật mật khẩu mới vào database
  - Xóa session đã sử dụng
- **Validation**: 
  - Password: required, min:8, confirmed
  - Session phải hợp lệ

#### Xác thực email
- **Route**: `GET /verify-email/{id}/{hash}`
- **Controller**: `VerifyEmailController`
- **Tính năng**: Xác thực email người dùng

---

## 👥 PHÂN QUYỀN VÀ VAI TRÒ

### Hệ thống 3 cấp độ

#### 1. **ADMIN** (Quản trị viên hệ thống)
- **Quyền cao nhất**: Quản lý toàn bộ hệ thống
- **Chức năng chính**:
  - Quản lý người dùng (CRUD)
  - Quản lý phòng ban (CRUD)
  - Quản lý công việc (CRUD)
  - Xem báo cáo tổng quan
  - Phân quyền và quản lý role

#### 2. **MANAGER** (Quản lý phòng ban)
- **Quyền trung bình**: Quản lý phòng ban của mình
- **Chức năng chính**:
  - Quản lý công việc trong phòng ban
  - Giao việc cho nhân viên cùng phòng ban
  - Duyệt/ từ chối công việc
  - Xem báo cáo phòng ban
  - Quản lý nhân viên trong phòng ban

#### 3. **EMPLOYEE** (Nhân viên)
- **Quyền cơ bản**: Thực hiện công việc được giao
- **Chức năng chính**:
  - Xem danh sách công việc được giao
  - Cập nhật trạng thái công việc
  - Bình luận và thảo luận
  - Upload file đính kèm
  - Xem lịch sử công việc

### Middleware phân quyền
```php
// Admin only
Route::middleware('role:admin')->group(function () {
    Route::resource('users', UserController::class);
    Route::resource('departments', DepartmentController::class);
});

// Manager & Admin
Route::middleware('role:admin,manager')->group(function () {
    Route::resource('tasks', TaskController::class);
    Route::get('/reports', [ReportController::class, 'index']);
});

// All authenticated users
Route::middleware('role:employee,manager,admin')->group(function () {
    Route::get('/my-tasks', [TaskController::class, 'myTasks']);
    Route::post('/tasks/{task}/comment', [TaskController::class, 'comment']);
});
```

---

## 🚀 CHỨC NĂNG THEO TỪNG ROLE

### 1. **ADMIN** - Quản trị viên hệ thống

#### 1.1 Quản lý người dùng (`/users`)
- **Tạo người dùng mới**:
  - Form tạo user với các trường: name, email, phone, password, role, department
  - Validation: email/phone unique, password min:8
  - Phân quyền theo role và department

- **Danh sách người dùng**:
  - Hiển thị tất cả user trong hệ thống
  - Filter theo role, department
  - Search theo tên, email, phone
  - Pagination

- **Chỉnh sửa người dùng**:
  - Cập nhật thông tin cá nhân
  - Thay đổi role và department
  - Reset password

- **Xóa người dùng**:
  - Kiểm tra ràng buộc trước khi xóa
  - Xử lý công việc liên quan

#### 1.2 Quản lý phòng ban (`/departments`)
- **Tạo phòng ban mới**:
  - Tên phòng ban
  - Mô tả (tùy chọn)

- **Danh sách phòng ban**:
  - Hiển thị tất cả department
  - Số lượng nhân viên mỗi phòng ban
  - Số lượng công việc đang thực hiện

- **Chỉnh sửa phòng ban**:
  - Cập nhật tên và mô tả
  - Quản lý nhân viên trong phòng ban

- **Xóa phòng ban**:
  - Kiểm tra ràng buộc với users và tasks

#### 1.3 Quản lý công việc (toàn hệ thống)
- **Xem tất cả công việc**:
  - Filter theo status, priority, department, assignee
  - Search theo title, description
  - Export dữ liệu

- **Tạo công việc mới**:
  - Giao việc cho bất kỳ nhân viên nào
  - Đặt deadline và priority
  - Upload file đính kèm

- **Chỉnh sửa công việc**:
  - Cập nhật thông tin
  - Thay đổi assignee
  - Điều chỉnh deadline

#### 1.4 Báo cáo tổng quan (`/reports`)
- **Thống kê tổng quan**:
  - Tổng số công việc theo trạng thái
  - Tổng số người dùng theo role
  - Tổng số phòng ban

- **Biểu đồ và phân tích**:
  - Biểu đồ tròn: Công việc theo trạng thái
  - Biểu đồ cột: Công việc theo phòng ban
  - Biểu đồ đường: Tiến độ theo thời gian

### 2. **MANAGER** - Quản lý phòng ban

#### 2.1 Quản lý công việc trong phòng ban
- **Xem công việc phòng ban**:
  - Chỉ hiển thị công việc của phòng ban mình
  - Filter theo status, priority, assignee
  - Search theo title, description

- **Tạo công việc mới**:
  - Chỉ giao việc cho nhân viên cùng phòng ban
  - Đặt deadline và priority
  - Upload file đính kèm

- **Chỉnh sửa công việc**:
  - Cập nhật thông tin
  - Thay đổi assignee (trong cùng phòng ban)
  - Điều chỉnh deadline

#### 2.2 Duyệt và từ chối công việc
- **Duyệt công việc hoàn thành**:
  - Xem chi tiết công việc
  - Thêm ghi chú kết thúc (tùy chọn)
  - Chuyển trạng thái sang "finished"

- **Từ chối công việc**:
  - Nhập lý do từ chối (bắt buộc)
  - Chuyển trạng thái sang "rejected"
  - Trả lại cho nhân viên làm lại

#### 2.3 Báo cáo phòng ban
- **Thống kê phòng ban**:
  - Số lượng công việc theo trạng thái
  - Hiệu suất nhân viên
  - Công việc trễ hạn

- **Phân tích hiệu suất**:
  - Biểu đồ tiến độ công việc
  - So sánh với các phòng ban khác

### 3. **EMPLOYEE** - Nhân viên

#### 3.1 Quản lý công việc cá nhân
- **Xem danh sách công việc** (`/my-tasks`):
  - Công việc được giao
  - Công việc đã tạo
  - Filter theo status, priority
  - Search theo title

- **Chi tiết công việc** (`/task-detail/{task}`):
  - Thông tin chi tiết
  - File đính kèm
  - Lịch sử thay đổi
  - Bình luận và thảo luận

#### 3.2 Thực hiện công việc
- **Cập nhật trạng thái**:
  - "in_progress": Đang thực hiện
  - "completed": Hoàn thành, gửi duyệt
  - "overdue": Trễ hạn

- **Upload file đính kèm**:
  - Hỗ trợ nhiều định dạng file
  - Giới hạn kích thước: 50MB
  - Xem và tải xuống file

- **Bình luận và thảo luận**:
  - Thêm bình luận mới
  - Xem lịch sử bình luận
  - Thông báo real-time

#### 3.3 Xử lý công việc bị từ chối
- **Xem lý do từ chối**:
  - Hiển thị lý do chi tiết
  - Hướng dẫn khắc phục

- **Làm lại công việc**:
  - Cập nhật theo yêu cầu
  - Gửi lại để duyệt

#### 3.4 Hoàn tác công việc đã hoàn thành
- **Điều kiện hoàn tác**:
  - Chỉ trong vòng 3 tiếng sau khi hoàn thành
  - Chỉ người được giao việc mới có thể hoàn tác

- **Thực hiện hoàn tác**:
  - Click nút "Hoàn tác" trong phần hành động
  - Xác nhận hành động
  - Công việc chuyển về trạng thái "Đang làm"

- **Theo dõi thời gian**:
  - Hiển thị số giờ còn lại để hoàn tác
  - Cảnh báo khi hết thời gian hoàn tác

---

## 🌐 API VÀ ROUTES

### Web Routes (Chính)

#### Routes xác thực
```php
// Guest routes
Route::get('/register', 'RegisteredUserController@create');
Route::post('/register', 'RegisteredUserController@store');
Route::get('/login', 'AuthenticatedSessionController@create');
Route::post('/login', 'AuthenticatedSessionController@store');
Route::get('/forgot-password', 'PasswordResetLinkController@create');
Route::post('/forgot-password', 'PasswordResetLinkController@store');
Route::get('/reset-password/{token}', 'NewPasswordController@create');
Route::post('/reset-password', 'NewPasswordController@store');

// Authenticated routes
Route::get('/verify-email', 'EmailVerificationPromptController');
Route::get('/verify-email/{id}/{hash}', 'VerifyEmailController');
Route::post('/email/verification-notification', 'EmailVerificationNotificationController@store');
Route::get('/confirm-password', 'ConfirmablePasswordController@show');
Route::post('/confirm-password', 'ConfirmablePasswordController@store');
Route::put('/password', 'PasswordController@update');
Route::post('/logout', 'AuthenticatedSessionController@destroy');
```

#### Routes chức năng chính
```php
// Dashboard
Route::get('/dashboard', 'DashboardController@index');

// Admin routes
Route::middleware('role:admin')->group(function () {
    Route::resource('users', 'UserController');
    Route::resource('departments', 'DepartmentController');
});

// Manager & Admin routes
Route::middleware('role:admin,manager')->group(function () {
    Route::resource('tasks', 'TaskController')->except(['show']);
    Route::get('/reports', 'ReportController@index');
});

// Task management
Route::get('/task-detail/{task}', 'TaskController@show');
Route::get('/create-task', 'TaskController@create');
Route::get('/tasks/{task}/update-status', 'TaskController@updateStatus');
Route::get('/tasks/{task}/history', 'TaskController@history');
Route::post('/tasks/{task}/remove-file', 'TaskController@removeFile');
Route::post('/tasks/{task}/set-rework-time', 'TaskController@setReworkTime');
Route::post('/tasks/{task}/toggle-recurring', 'TaskController@toggleRecurring');

// Employee routes
Route::middleware('role:employee,manager,admin')->group(function () {
    Route::get('/my-tasks', 'TaskController@myTasks');
    Route::post('/tasks/{task}/comment', 'TaskController@comment');
});
```

### API Routes
```php
// API endpoints cho mobile app (nếu có)
Route::prefix('api')->group(function () {
    Route::post('/login', 'Api\AuthController@login');
    Route::get('/tasks', 'Api\TaskController@index');
    Route::post('/tasks/{task}/comment', 'Api\TaskController@comment');
});
```

---

## 🔄 TÍNH NĂNG LẶP LẠI CÔNG VIỆC (ĐÃ ĐƠN GIẢN HÓA)

### Tổng quan
Tính năng lặp lại công việc đã được đơn giản hóa, sử dụng checkbox đơn giản thay vì dropdown phức tạp. Hệ thống tự động tính toán khoảng thời gian lặp lại dựa trên duration của task gốc và tự động cập nhật deadline mỗi 7h sáng.

### Cấu trúc database (Đã đơn giản hóa)
```sql
-- Các trường mới được thêm vào bảng tasks
is_recurring BOOLEAN DEFAULT FALSE
recurring_start_date DATE NULL
recurring_days INTEGER NULL
last_reset_date DATE NULL
rework_hours INTEGER NULL
rework_deadline TIMESTAMP NULL
completed_at TIMESTAMP NULL
```

### Cơ chế hoạt động mới
1. **Checkbox đơn giản**: Chỉ cần tick vào "Lặp lại công việc"
2. **Tự động tính toán**: Hệ thống tự tính `recurring_days` từ deadline gốc
3. **Ví dụ**: Task tạo ngày 19/5, deadline 22/5 (4 ngày) → tự động lặp lại mỗi 4 ngày
4. **Ngày bắt đầu**: Có thể tùy chỉnh hoặc mặc định là ngày hiện tại

### Xử lý tự động
- **Command**: `php artisan tasks:reset-recurring`
- **Schedule**: Chạy mỗi 7h sáng hàng ngày
- **Logic**: Kiểm tra `is_recurring = true` và `needsNewDeadline()`
- **Cập nhật**: Deadline mới = `recurring_start_date + recurring_days`

### Xử lý tự động
- **Command**: `php artisan tasks:reset-recurring`
- **Schedule**: Chạy mỗi 7h sáng hàng ngày
- **Logic**: Kiểm tra và reset các công việc cần lặp lại
- **Log**: Ghi log vào `storage/logs/recurring-tasks.log`

### Xử lý thời gian làm lại
- **Khi bị từ chối**: Admin/Manager set thời gian làm lại (1-168 giờ)
- **Tự động**: Hết hạn làm lại → chuyển về trạng thái "in_progress"
- **Tracking**: Theo dõi thời gian đếm ngược

### Tính năng hoàn tác
- **Điều kiện**: Chỉ có thể hoàn tác trong vòng 3 tiếng sau khi hoàn thành
- **Quyền**: Chỉ người được giao việc mới có thể hoàn tác
- **Hành động**: Chuyển từ trạng thái "completed" về "in_progress"
- **Tracking**: Ghi log hoạt động và thời gian hoàn tác

### API Endpoints
```php
// Set thời gian làm lại
POST /tasks/{task}/set-rework-time
// Hoàn tác công việc đã hoàn thành
POST /tasks/{task}/undo-completion
```

### Middleware và quyền hạn
- **Admin**: Quản lý tất cả công việc lặp lại
- **Manager**: Chỉ quản lý trong phòng ban
- **Employee**: Chỉ xem và thực hiện

---

## ⏪ TÍNH NĂNG HOÀN TÁC HOÀN THÀNH

### Tổng quan
Tính năng hoàn tác cho phép người được giao việc (assignee) hoàn tác trạng thái "hoàn thành" về "đang làm" trong vòng 3 tiếng sau khi hoàn thành.

### Điều kiện sử dụng
- **Trạng thái**: Task phải ở trạng thái "completed"
- **Thời gian**: Chỉ trong vòng 3 tiếng sau khi hoàn thành
- **Quyền**: Chỉ người được giao việc mới có thể hoàn tác
- **Tracking**: Sử dụng field `completed_at` để theo dõi thời gian

### Cơ chế hoạt động
```php
public function canUndo(): bool
{
    if ($this->status !== 'completed' || !$this->completed_at) {
        return false;
    }
    
    $hoursSinceCompleted = Carbon::now()->diffInHours($this->completed_at);
    return $hoursSinceCompleted <= 3;
}

public function undoCompletion(): void
{
    $this->update([
        'status' => 'in_progress',
        'completed_at' => null
    ]);
    
    // Tạo activity log
    $this->activities()->create([
        'user_id' => $this->assignee_id,
        'action' => 'undo_completion',
        'meta' => 'Hoàn tác trạng thái hoàn thành về "Đang làm"'
    ]);
}
```

### Giao diện người dùng
- **Nút hoàn tác**: Chỉ hiển thị khi `canUndo() = true`
- **Thông báo**: Hiển thị thông báo khi không thể hoàn tác
- **Xác nhận**: Popup confirm trước khi hoàn tác
- **Styling**: Nút với hiệu ứng gradient và hover effects

### Activity Logging
- **Action**: `undo_completion`
- **Meta**: Mô tả hành động hoàn tác
- **User**: Người thực hiện hoàn tác
- **Timestamp**: Thời điểm hoàn tác

---

## 🇻🇳 VIỆT HÓA GIAO DIỆN

### Tổng quan
Toàn bộ giao diện hệ thống đã được việt hóa để phù hợp với đối tượng người dùng là nông dân, giúp họ dễ dàng sử dụng hệ thống mà không cần kiến thức tiếng Anh.

### Các thành phần đã việt hóa

#### 1. **Form Labels và Comments**
- **Trước**: `Title`, `Description`, `File Upload`, `Assignee`
- **Sau**: `Tiêu đề`, `Mô tả`, `Tệp đính kèm`, `Người phụ trách`

#### 2. **Button Text**
- **Trước**: `Submit`, `Update`, `Delete`, `View History`
- **Sau**: `Gửi`, `Cập nhật`, `Xóa`, `Xem lịch sử`

#### 3. **Status Messages**
- **Trước**: `Task created successfully`, `Validation failed`
- **Sau**: `Đã tạo công việc thành công`, `Xác thực thất bại`

#### 4. **Error Messages**
- **Trước**: `Field is required`, `Invalid format`
- **Sau**: `Trường này là bắt buộc`, `Định dạng không hợp lệ`

#### 5. **Navigation và Menu**
- **Trước**: `Dashboard`, `Tasks`, `Users`, `Departments`
- **Sau**: `Bảng điều khiển`, `Công việc`, `Người dùng`, `Phòng ban`

### Lợi ích của việt hóa
- **Dễ sử dụng**: Người nông dân không cần biết tiếng Anh
- **Tăng hiệu quả**: Giảm thời gian học cách sử dụng
- **Giảm lỗi**: Hiểu rõ chức năng của từng nút/trường
- **Tăng sự tin tưởng**: Giao diện quen thuộc với người Việt

### Cách thực hiện việt hóa
```php
// Sử dụng Blade directives
@if($task->status === 'completed')
    <span class="badge bg-success">Chờ duyệt</span>
@elseif($task->status === 'in_progress')
    <span class="badge bg-primary">Đang làm</span>
@endif

// Sử dụng helper functions
{{ __('messages.task_created') }}
// Trong resources/lang/vi/messages.php
'task_created' => 'Đã tạo công việc thành công'
```

---

## ✅ VALIDATION VÀ BẢO MẬT

### Client-side Validation

#### Textarea Word Length Validation
- **Giới hạn**: Tối đa 45 ký tự cho mỗi từ
- **Áp dụng cho**:
  - Description trong create/edit task
  - Rejection reason
  - Finish note
  - Comment content

#### Deadline Validation
- **Ngăn chặn**: Không cho phép set deadline trong quá khứ
- **Client-side**: HTML `min` attribute với `now()`
- **Server-side**: Laravel rule `after:today`
- **Real-time**: JavaScript validation khi user nhập

#### JavaScript Validation
```javascript
// Word length validation
function checkWordLength(text) {
    const words = text.trim().split(/\s+/);
    return words.every(word => word.length <= 45);
}

function validateTextarea(textarea, errorElement, submitBtn) {
    const text = textarea.value;
    const isValid = checkWordLength(text);
    
    if (!isValid) {
        errorElement.style.display = 'block';
        submitBtn.disabled = true;
        submitBtn.innerHTML = 'Từ quá dài (>45 ký tự)';
    } else {
        errorElement.style.display = 'none';
        submitBtn.disabled = false;
        submitBtn.innerHTML = 'Gửi';
    }
}

// Deadline validation
function validateDeadline(deadlineInput) {
    const selectedDate = new Date(deadlineInput.value);
    const now = new Date();
    
    if (selectedDate <= now) {
        deadlineInput.setCustomValidity('Deadline phải là thời gian trong tương lai');
        return false;
    } else {
        deadlineInput.setCustomValidity('');
        return true;
    }
}

// Real-time deadline validation
document.querySelector('input[name="deadline"]').addEventListener('change', function() {
    validateDeadline(this);
});
```

### Server-side Validation

#### Task Validation
```php
$request->validate([
    'title' => 'required|string|max:255',
    'description' => 'nullable|string',
    'assignee_id' => 'nullable|exists:users,id',
    'deadline' => 'nullable|date|after:today',
    'priority' => 'nullable|in:low,medium,high',
    'status' => 'required|in:in_progress,completed,rejected,overdue,finished',
    'rejection_reason' => 'nullable|string|max:1000',
    'files.*' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx,ppt,pptx,jpg,jpeg,png,gif,webp,mp4,avi,mov,wmv,flv,webm|max:51200',
]);
```

#### User Validation
```php
$request->validate([
    'name' => 'required|string|max:255',
    'email' => 'required|string|lowercase|email|max:255|unique:users',
    'phone' => 'required_without:email|nullable|string|max:20|unique:users',
    'password' => 'required|confirmed|min:8',
    'role' => 'required|in:admin,manager,employee',
    'department_id' => 'required|exists:departments,id',
]);
```

### Bảo mật

#### CSRF Protection
- Tất cả form POST đều có CSRF token
- Middleware `VerifyCsrfToken` bảo vệ

#### Role-based Access Control
- Middleware `RoleMiddleware` kiểm tra quyền
- Kiểm tra role trong controller

#### File Upload Security
- Giới hạn kích thước file: 50MB
- Kiểm tra MIME type
- Lưu trữ an toàn trong storage

### UI/UX Improvements

#### Button Effects và Animations
- **Gradient backgrounds**: Sử dụng CSS gradients cho buttons
- **Hover effects**: Transform, shadow, opacity changes
- **Smooth transitions**: CSS transitions cho tất cả interactive elements
- **Responsive design**: Mobile-first approach với Bootstrap 5

#### Nút Hoàn tác Styling
```css
.btn-undo {
    background: linear-gradient(135deg, #f59e0b 0%, #f97316 100%);
    color: #fff;
    border: none;
    border-radius: 8px;
    padding: 12px 20px;
    font-weight: 500;
    transition: all 0.3s ease;
    box-shadow: 0 2px 8px rgba(245, 158, 11, 0.3);
}

.btn-undo:hover {
    background: linear-gradient(135deg, #f97316 0%, #ea580c 100%);
    transform: translateY(-3px);
    box-shadow: 0 6px 20px rgba(245, 158, 11, 0.4);
}
```

#### Form Styling
- **Focus effects**: Border color changes và box-shadow
- **Error states**: Red borders và error messages
- **Success states**: Green borders và success indicators
- **Loading states**: Disabled buttons và spinners

---

## 📖 HƯỚNG DẪN SỬ DỤNG

### 1. **Đăng ký tài khoản mới**

#### Bước 1: Truy cập trang đăng ký
- Vào đường dẫn: `/register`
- Hoặc click "Đăng ký" từ trang đăng nhập

#### Bước 2: Điền thông tin
- **Họ tên**: Bắt buộc, tối đa 255 ký tự
- **Email**: Bắt buộc, định dạng email hợp lệ, duy nhất
- **Số điện thoại**: Bắt buộc nếu không có email, duy nhất
- **Mật khẩu**: Tối thiểu 8 ký tự, xác nhận lại

#### Bước 3: Xác thực email
- Kiểm tra email và click link xác thực
- Hoặc sử dụng chức năng gửi lại email xác thực

### 2. **Đăng nhập hệ thống**

#### Bước 1: Truy cập trang đăng nhập
- Vào đường dẫn: `/login`
- Hoặc click "Đăng nhập" từ trang đăng ký

#### Bước 2: Điền thông tin đăng nhập
- **Email/Số điện thoại**: Nhập email hoặc số điện thoại đã đăng ký
- **Mật khẩu**: Nhập mật khẩu tài khoản

#### Bước 3: Xử lý đăng nhập
- Hệ thống kiểm tra thông tin
- Chuyển hướng đến dashboard nếu thành công
- Hiển thị lỗi nếu thông tin không chính xác

### 3. **Quản lý công việc (Manager/Admin)**

#### Tạo công việc mới
1. Vào Dashboard → Click "Tạo công việc mới"
2. Điền thông tin:
   - **Tiêu đề**: Bắt buộc, mô tả ngắn gọn
   - **Mô tả**: Chi tiết công việc (tối đa 45 ký tự/từ)
   - **Người nhận**: Chọn nhân viên (Manager chỉ chọn cùng phòng ban)
   - **Deadline**: Thời hạn hoàn thành
   - **Độ ưu tiên**: Thấp/Trung bình/Cao
   - **Lặp lại công việc**: Chọn loại lặp lại (Không lặp lại/Mỗi ngày/Mỗi 3 ngày/Mỗi tuần/Tùy chỉnh)
   - **Ngày bắt đầu/kết thúc**: Chỉ hiển thị khi chọn "Tùy chỉnh"
   - **File đính kèm**: Upload tài liệu liên quan
3. Click "Giao việc" để tạo

#### Quản lý công việc lặp lại
- **Tạm dừng/Tiếp tục**: Click nút toggle để tạm dừng hoặc tiếp tục công việc lặp lại
- **Set thời gian làm lại**: Khi công việc bị từ chối, set số giờ để nhân viên làm lại (1-168 giờ)
- **Theo dõi lịch sử**: Xem các lần reset và thời gian làm lại

#### Duyệt công việc
1. Vào Dashboard → Xem công việc "Chờ duyệt"
2. Click vào công việc để xem chi tiết
3. Chọn hành động:
   - **Kết thúc**: Thêm ghi chú (tùy chọn) → Click "Kết thúc"
   - **Từ chối**: Nhập lý do từ chối → Click "Từ chối"

### 4. **Thực hiện công việc (Employee)**

#### Xem danh sách công việc
1. Vào Dashboard → "Công việc của tôi"
2. Filter theo trạng thái: Đang làm/Chờ duyệt/Từ chối/Trễ hạn
3. Search theo tiêu đề hoặc mô tả

#### Cập nhật trạng thái
1. Click vào công việc để xem chi tiết
2. Chọn hành động phù hợp:
   - **Bắt đầu làm**: Chuyển sang "Đang làm"
   - **Hoàn thành**: Chuyển sang "Chờ duyệt"
   - **Ghi chú**: Thêm bình luận hoặc file đính kèm

#### Xử lý công việc bị từ chối
1. Xem lý do từ chối trong thông báo
2. Cập nhật theo yêu cầu
3. Gửi lại để duyệt

### 5. **Quản lý người dùng (Admin)**

#### Tạo người dùng mới
1. Vào Dashboard → "Quản lý người dùng" → "Tạo mới"
2. Điền thông tin:
   - **Họ tên**: Bắt buộc
   - **Email**: Bắt buộc, duy nhất
   - **Số điện thoại**: Bắt buộc, duy nhất
   - **Vai trò**: Admin/Manager/Employee
   - **Phòng ban**: Chọn phòng ban
   - **Mật khẩu**: Tối thiểu 8 ký tự
3. Click "Tạo người dùng"

#### Chỉnh sửa người dùng
1. Vào danh sách người dùng → Click "Chỉnh sửa"
2. Cập nhật thông tin cần thiết
3. Click "Cập nhật"

### 6. **Xem báo cáo (Manager/Admin)**

#### Báo cáo tổng quan
1. Vào Dashboard → "Báo cáo"
2. Xem các biểu đồ:
   - **Công việc theo trạng thái**: Biểu đồ tròn
   - **Công việc theo phòng ban**: Biểu đồ cột
   - **Tiến độ theo thời gian**: Biểu đồ đường

#### Export dữ liệu
1. Chọn loại báo cáo
2. Chọn khoảng thời gian
3. Click "Xuất Excel/PDF"

---

## 🔧 TROUBLESHOOTING

### Các lỗi thường gặp

#### 1. **Lỗi đăng nhập**
**Triệu chứng**: Không thể đăng nhập, hiển thị "Thông tin đăng nhập không chính xác"
**Nguyên nhân**:
- Email/số điện thoại không tồn tại
- Mật khẩu sai
- Tài khoản bị khóa

**Giải pháp**:
- Kiểm tra lại thông tin đăng nhập
- Sử dụng chức năng "Quên mật khẩu"
- Liên hệ admin để kiểm tra tài khoản

#### 2. **Lỗi upload file**
**Triệu chứng**: Không thể upload file, hiển thị lỗi
**Nguyên nhân**:
- File quá lớn (>50MB)
- Định dạng file không được hỗ trợ
- Quyền ghi thư mục storage

**Giải pháp**:
- Kiểm tra kích thước file
- Chuyển đổi sang định dạng được hỗ trợ
- Kiểm tra quyền thư mục storage

#### 3. **Lỗi validation textarea**
**Triệu chứng**: Không thể submit form, hiển thị "Từ quá dài (>45 ký tự)"
**Nguyên nhân**: Có từ dài hơn 45 ký tự trong textarea
**Giải pháp**: Rút ngắn từ dài hoặc chia nhỏ thành nhiều từ

#### 4. **Lỗi phân quyền**
**Triệu chứng**: Không thể truy cập chức năng, hiển thị "403 Forbidden"
**Nguyên nhân**: Không có quyền truy cập chức năng
**Giải pháp**: Liên hệ admin để cấp quyền

#### 5. **Lỗi database**
**Triệu chứng**: Hiển thị lỗi SQL hoặc không thể lưu dữ liệu
**Nguyên nhân**:
- Database connection lỗi
- Bảng chưa được tạo
- Constraint violation

**Giải pháp**:
- Kiểm tra cấu hình database trong .env
- Chạy `php artisan migrate:fresh --seed`
- Kiểm tra dữ liệu đầu vào

#### 6. **Lỗi công việc lặp lại**
**Triệu chứng**: Công việc không tự động reset, không thể set thời gian làm lại
**Nguyên nhân**:
- Cron job chưa được cài đặt
- Command không chạy được
- Trạng thái lặp lại không đúng

**Giải pháp**:
- Cài đặt cron job: `* * * * * cd /path/to/project && php artisan schedule:run`
- Chạy command thủ công: `php artisan tasks:reset-recurring`
- Kiểm tra trạng thái lặp lại có phải "active" không
- Xem log: `tail -f storage/logs/recurring-tasks.log`

### Hướng dẫn debug

#### 1. **Bật chế độ debug**
```env
APP_DEBUG=true
APP_ENV=local
```

#### 2. **Xem log lỗi**
```bash
tail -f storage/logs/laravel.log
```

#### 3. **Clear cache**
```bash
php artisan config:clear
php artisan cache:clear
php artisan view:clear
```

#### 4. **Kiểm tra database**
```bash
php artisan tinker
>>> DB::connection()->getPdo();
>>> Schema::hasTable('users');
```

#### 5. **Kiểm tra công việc lặp lại**
```bash
# Chạy command reset thủ công
php artisan tasks:reset-recurring

# Xem log của command
tail -f storage/logs/recurring-tasks.log

# Kiểm tra schedule
php artisan schedule:list

# Test cron job
php artisan schedule:run
```

---

## 📚 TÀI LIỆU THAM KHẢO

### Laravel Documentation
- [Laravel 10.x Documentation](https://laravel.com/docs/10.x)
- [Laravel Breeze Documentation](https://laravel.com/docs/10.x/starter-kits#laravel-breeze)
- [Laravel Validation](https://laravel.com/docs/10.x/validation)

### Frontend Resources
- [Bootstrap 5 Documentation](https://getbootstrap.com/docs/5.3/)
- [Tailwind CSS Documentation](https://tailwindcss.com/docs)

### Database & Security
- [MySQL Documentation](https://dev.mysql.com/doc/)
- [OWASP Security Guidelines](https://owasp.org/www-project-top-ten/)

---

## 📞 LIÊN HỆ HỖ TRỢ

### Đội phát triển
- **Developer**: [Tên developer]
- **Email**: [email@domain.com]
- **Phone**: [Số điện thoại]

### Hỗ trợ kỹ thuật
- **Technical Support**: [Tên support]
- **Email**: [support@domain.com]
- **Phone**: [Số điện thoại]

### Báo cáo lỗi
- **GitHub Issues**: [Repository URL]/issues
- **Email**: [bugs@domain.com]

---

*Tài liệu này được cập nhật lần cuối: {{ date('d/m/Y H:i:s') }}*
*Phiên bản: 1.0*
