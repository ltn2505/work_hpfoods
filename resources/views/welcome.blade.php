<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Dashboard - Quản lý công việc</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="{{ asset('css/home.css') }}" rel="stylesheet">
</head>
<body>

@include('partials.navbar')

<div class="container-fluid">
  <div class="row">
    <!-- Sidebar -->
    <div class="col-lg-2 col-md-3 p-0 sidebar">
      <div class="list-group list-group-flush mt-3">
        <a href="#" class="list-group-item list-group-item-action active">📋 Danh sách</a>
        <a href="#" class="list-group-item list-group-item-action">➕ Tạo công việc</a>
        <a href="#" class="list-group-item list-group-item-action">📊 Báo cáo</a>
      </div>
    </div>

    <!-- Main Content -->
    <div class="col-lg-10 col-md-9 p-4">
      <!-- Stats Cards -->
      <div class="row g-3 mb-4">
        <div class="col-md-3 col-sm-6">
          <div class="card card-stat text-center p-3">
            <h5 class="text-primary">20</h5>
            <p class="mb-0">Công việc đang làm</p>
          </div>
        </div>
        <div class="col-md-3 col-sm-6">
          <div class="card card-stat text-center p-3">
            <h5 class="text-success">15</h5>
            <p class="mb-0">Hoàn thành</p>
          </div>
        </div>
        <div class="col-md-3 col-sm-6">
          <div class="card card-stat text-center p-3">
            <h5 class="text-danger">5</h5>
            <p class="mb-0">Trễ hạn</p>
          </div>
        </div>
        <div class="col-md-3 col-sm-6">
          <div class="card card-stat text-center p-3">
            <h5 class="text-warning">8</h5>
            <p class="mb-0">Chưa bắt đầu</p>
          </div>
        </div>
      </div>

      <!-- Filter Buttons -->
      <div class="mb-3">
        <button class="btn btn-outline-secondary btn-sm">Tất cả</button>
        <button class="btn btn-outline-primary btn-sm">Đang làm</button>
        <button class="btn btn-outline-success btn-sm">Hoàn thành</button>
        <button class="btn btn-outline-danger btn-sm">Trễ</button>
      </div>

      <!-- Task Table -->
      <div class="card shadow-sm">
        <div class="card-body table-responsive">
          <table class="table align-middle">
            <thead>
              <tr>
                <th>Tiêu đề</th>
                <th>Người phụ trách</th>
                <th>Ngày giao</th>
                <th>Deadline</th>
                <th>Trạng thái</th>
                <th>Hành động</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td>Làm báo cáo tuần</td>
                <td>Ngọc Anh</td>
                <td>14/08/2025</td>
                <td>17/08/2025</td>
                <td><span class="badge bg-warning">Đang làm</span></td>
                <td>
                  <a href="{{ route('task-detail') }}" class="btn btn-sm btn-outline-info">👁 Xem</a>
                  <button class="btn btn-sm btn-outline-warning">✏ Sửa</button>
                  <button class="btn btn-sm btn-outline-success">✅ Duyệt</button>
                </td>
              </tr>
              <tr>
                <td>Thiết kế banner sự kiện</td>
                <td>Minh Quân</td>
                <td>13/08/2025</td>
                <td>15/08/2025</td>
                <td><span class="badge bg-success">Hoàn thành</span></td>
                <td>
                  <a href="{{ route('task-detail') }}" class="btn btn-sm btn-outline-info">👁 Xem</a>
                  <button class="btn btn-sm btn-outline-warning">✏ Sửa</button>
                  <button class="btn btn-sm btn-outline-success">✅ Duyệt</button>
                </td>
              </tr>
              <tr>
                <td>Kiểm tra tồn kho</td>
                <td>Hồng Nhung</td>
                <td>12/08/2025</td>
                <td>14/08/2025</td>
                <td><span class="badge bg-danger">Trễ</span></td>
                <td>
                  <a href="{{ route('task-detail') }}" class="btn btn-sm btn-outline-info">👁 Xem</a>
                  <button class="btn btn-sm btn-outline-warning">✏ Sửa</button>
                  <button class="btn btn-sm btn-outline-success">✅ Duyệt</button>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>

    </div>
  </div>
</div>

<!-- Nút tạo công việc nổi -->
<div class="position-fixed" style="bottom: 2rem; right: 2rem; z-index: 1000;">
  <a href="{{ route('create-task') }}" class="btn btn-success btn-lg shadow">
    ➕ Tạo công việc
  </a>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
