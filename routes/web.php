<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ReportController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Vào root thì chuyển sang dashboard
Route::get('/', fn () => redirect()->route('dashboard'));

// Các route yêu cầu đăng nhập (KHÔNG dùng verified)
Route::middleware(['auth'])->group(function () {

    // Dashboard: đổ dữ liệu động (controller trả về view 'welcome')
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Chỉ Admin: quản lý user
    Route::middleware('role:admin')->group(function () {
        Route::resource('users', UserController::class)->except(['show']);
        // Nếu có DepartmentController thì thêm ở đây
        // Route::resource('departments', DepartmentController::class);
    });

    // Manager & Admin: CRUD Task (tránh trùng, bỏ 'show' vì dùng alias riêng)
    Route::middleware('role:admin,manager')->group(function () {
        Route::resource('tasks', TaskController::class)->except(['show']);
        // Cập nhật trạng thái task (nút hành động ở trang chi tiết)
        Route::get('/tasks/{task}/update-status', [TaskController::class, 'updateStatus'])
            ->name('tasks.updateStatus');
    });

    // Alias để khớp link của giao diện cũ (mọi role đều có thể xem chi tiết)
    Route::get('/task-detail/{task}', [TaskController::class, 'show'])->name('task-detail');
    // Alias cho form tạo (trùng với tasks.create nhưng để khớp UI cũ)
    Route::get('/create-task', [TaskController::class, 'create'])->name('create-task');

    // Employee/Manager/Admin: trang "my tasks" & comment trên task
    Route::middleware('role:employee,manager,admin')->group(function () {
        Route::get('/my-tasks', [TaskController::class, 'myTasks'])->name('tasks.mine');
        Route::post('/tasks/{task}/comment', [TaskController::class, 'comment'])->name('tasks.comment');
    });

    // Báo cáo tổng quan (thường cho manager & admin)
    Route::middleware('role:admin,manager')->group(function () {
        Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
    });
});

// Chỉ require auth.php nếu đã cài Breeze/Jetstream (tránh lỗi file không tồn tại)
if (file_exists(__DIR__ . '/auth.php')) {
    require __DIR__ . '/auth.php';
}
