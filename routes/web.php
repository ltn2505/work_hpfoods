<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\DashboardController;

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

    // Manager & Admin: CRUD Task (tránh trùng, nên bỏ 'show' vì ta có alias riêng)
    Route::middleware('role:admin,manager')->group(function () {
        Route::resource('tasks', TaskController::class)->except(['show']);
    });

    // Alias để khớp link của giao diện cũ
    Route::get('/task-detail/{task}', [TaskController::class, 'show'])->name('task-detail');
    Route::get('/create-task', [TaskController::class, 'create'])->name('create-task');

    // Employee/Manager/Admin: trang "my tasks"
    Route::middleware('role:employee,manager,admin')->group(function () {
        Route::get('/my-tasks', [TaskController::class, 'myTasks'])->name('tasks.mine');
    });
});

// Chỉ require auth.php nếu đã cài Breeze/Jetstream (tránh lỗi file không tồn tại)
if (file_exists(__DIR__ . '/auth.php')) {
    require __DIR__ . '/auth.php';
}
