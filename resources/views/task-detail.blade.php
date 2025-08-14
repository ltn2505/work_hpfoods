<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Chi tiết công việc - Quản lý công việc</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="{{ asset('css/home.css') }}" rel="stylesheet">
  <style>
    .task-header {
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      color: white;
      padding: 2rem;
      border-radius: 12px;
      margin-bottom: 2rem;
    }
    .progress-section {
      background: white;
      border-radius: 12px;
      box-shadow: 0 4px 20px rgba(0,0,0,0.08);
      padding: 1.5rem;
      margin-bottom: 1.5rem;
    }
    .comment-section {
      background: white;
      border-radius: 12px;
      box-shadow: 0 4px 20px rgba(0,0,0,0.08);
      padding: 1.5rem;
      margin-bottom: 1.5rem;
    }
    .comment-item {
      border-left: 3px solid #0d6efd;
      padding-left: 1rem;
      margin-bottom: 1rem;
    }
    .file-attachment {
      background: #f8f9fa;
      border-radius: 8px;
      padding: 0.75rem;
      margin-bottom: 0.5rem;
      display: flex;
      align-items: center;
      gap: 0.5rem;
    }
    .priority-badge {
      font-size: 0.875rem;
      padding: 0.25rem 0.75rem;
    }
    .action-buttons {
      position: fixed;
      bottom: 2rem;
      right: 2rem;
      z-index: 1000;
    }
  </style>
</head>
<body>

@include('partials.navbar')

<div class="container-fluid">
  <div class="row">
    <!-- Sidebar -->
    <div class="col-lg-2 col-md-3 p-0 sidebar">
      <div class="list-group list-group-flush mt-3">
        <a href="{{ route('home') }}" class="list-group-item list-group-item-action">📋 Danh sách</a>
        <a href="{{ route('create-task') }}" class="list-group-item list-group-item-action">➕ Tạo công việc</a>
        <a href="{{ route('reports') }}" class="list-group-item list-group-item-action">📊 Báo cáo</a>
      </div>
    </div>

    <!-- Main Content -->
    <div class="col-lg-10 col-md-9 p-4">
      <!-- Header -->
      <div class="task-header">
        <div class="d-flex justify-content-between align-items-start">
          <div>
            <h1 class="mb-2">🎯 Thiết kế banner sự kiện mùa hè</h1>
            <div class="d-flex align-items-center gap-3">
              <span class="badge bg-success fs-6">Hoàn thành</span>
              <span class="badge bg-warning priority-badge">Độ ưu tiên: Cao</span>
            </div>
          </div>
          <a href="{{ route('home') }}" class="btn btn-outline-light">← Quay lại</a>
        </div>
      </div>

      <div class="row">
        <!-- Cột trái - Thông tin chung -->
        <div class="col-lg-8">
          <!-- Thông tin chung -->
          <div class="progress-section">
            <h4 class="mb-3">📋 Thông tin chung</h4>
            <div class="row">
              <div class="col-md-6">
                <p><strong>👤 Người giao:</strong> Anh Tuấn (Leader)</p>
                <p><strong>👤 Người nhận:</strong> Minh Quân (Designer)</p>
                <p><strong>📅 Ngày giao:</strong> 13/08/2025</p>
              </div>
              <div class="col-md-6">
                <p><strong>⏰ Deadline:</strong> 15/08/2025</p>
                <p><strong>🎯 Độ ưu tiên:</strong> <span class="text-danger">Cao</span></p>
                <p><strong>📊 Trạng thái:</strong> <span class="text-success">Hoàn thành</span></p>
              </div>
            </div>
          </div>

            
          <!-- Thảo luận -->
          <div class="comment-section">
            <h4 class="mb-3">💬 Thảo luận</h4>
            <div class="mb-3">
              <textarea class="form-control" rows="3" placeholder="Viết bình luận..."></textarea>
              <div class="d-flex justify-content-end mt-2">
                <button class="btn btn-primary">Gửi bình luận</button>
              </div>
            </div>
            
            <div class="comment-item">
              <div class="d-flex justify-content-between">
                <strong>Minh Quân</strong>
                <small class="text-muted">2 giờ trước</small>
              </div>
              <p class="mb-1">Đã hoàn thành banner theo yêu cầu. Mọi người xem và cho ý kiến nhé!</p>
            </div>
            
            <div class="comment-item">
              <div class="d-flex justify-content-between">
                <strong>Anh Tuấn</strong>
                <small class="text-muted">1 giờ trước</small>
              </div>
              <p class="mb-1">Banner đẹp lắm! Cảm ơn Minh Quân đã hoàn thành đúng hạn.</p>
            </div>
            
            <div class="comment-item">
              <div class="d-flex justify-content-between">
                <strong>Ngọc Anh</strong>
                <small class="text-muted">30 phút trước</small>
              </div>
              <p class="mb-1">Màu sắc rất phù hợp với chủ đề mùa hè. Tuyệt vời!</p>
            </div>
          </div>
        </div>

        <!-- Cột phải - File đính kèm & Hành động -->
        <div class="col-lg-4">
          <!-- File đính kèm -->
          <div class="progress-section">
            <h4 class="mb-3">📎 File đính kèm</h4>
            <div class="file-attachment">
              <span class="text-primary">🎨</span>
              <span>Banner_su_kien_mua_he.psd</span>
              <small class="text-muted">(2.5 MB)</small>
            </div>
            <div class="file-attachment">
              <span class="text-success">🖼️</span>
              <span>Banner_su_kien_mua_he.png</span>
              <small class="text-muted">(1.8 MB)</small>
            </div>
            <div class="file-attachment">
              <span class="text-info">📋</span>
              <span>Yeu_cau_thiet_ke.pdf</span>
              <small class="text-muted">(0.5 MB)</small>
            </div>
          </div>

                     <!-- Hành động -->
           <div class="progress-section">
             <h4 class="mb-3">⚡ Hành động</h4>
             <div class="d-grid gap-2">
               <button class="btn btn-success">✅ Hoàn thành</button>
               <button class="btn btn-primary">📝 Cập nhật trạng thái</button>
               <button class="btn btn-warning">🔄 Yêu cầu chỉnh sửa</button>
               <button class="btn btn-info">👁️ Xem lịch sử</button>
             </div>
           </div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Nút tạo công việc nổi -->
<div class="action-buttons">
  <a href="{{ route('create-task') }}" class="btn btn-success btn-lg shadow">
    ➕ Tạo công việc
  </a>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
// Có thể thêm các chức năng JavaScript khác ở đây nếu cần
</script>
</body>
</html>
