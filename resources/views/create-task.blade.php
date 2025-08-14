<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Tạo công việc - Quản lý công việc</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="{{ asset('css/home.css') }}" rel="stylesheet">
  <style>
    .form-container {
      background: white;
      border-radius: 12px;
      box-shadow: 0 4px 20px rgba(0,0,0,0.08);
      padding: 2rem;
    }
    .file-drop-zone {
      border: 2px dashed #dee2e6;
      border-radius: 8px;
      padding: 2rem;
      text-align: center;
      transition: all 0.3s ease;
      cursor: pointer;
    }
    .file-drop-zone:hover {
      border-color: #0d6efd;
      background-color: #f8f9fa;
    }
    .priority-high { color: #dc3545; }
    .priority-medium { color: #fd7e14; }
    .priority-low { color: #198754; }
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
        <a href="{{ route('create-task') }}" class="list-group-item list-group-item-action active">➕ Tạo công việc</a>
        <a href="{{ route('reports') }}" class="list-group-item list-group-item-action">📊 Báo cáo</a>
      </div>
    </div>

    <!-- Main Content -->
    <div class="col-lg-10 col-md-9 p-4">
      <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">➕ Tạo công việc mới</h2>
        <a href="{{ route('home') }}" class="btn btn-outline-secondary">← Quay lại</a>
      </div>

      <div class="form-container">
        <form>
          <div class="row">
            <!-- Cột trái -->
            <div class="col-md-6">
              <div class="mb-3">
                <label for="title" class="form-label fw-bold">Tiêu đề <span class="text-danger">*</span></label>
                <input type="text" class="form-control form-control-lg" id="title" required placeholder="Nhập tiêu đề công việc">
              </div>

              <div class="mb-3">
                <label for="description" class="form-label fw-bold">Mô tả</label>
                <textarea class="form-control" id="description" rows="4" placeholder="Mô tả chi tiết công việc..."></textarea>
              </div>

              <div class="mb-3">
                <label class="form-label fw-bold">File đính kèm</label>
                <div class="file-drop-zone" onclick="document.getElementById('fileInput').click()">
                  <div class="mb-2">
                    <i class="fas fa-cloud-upload-alt fa-2x text-muted"></i>
                  </div>
                  <p class="mb-1 fw-semibold">Kéo & thả file vào đây</p>
                  <p class="text-muted small">hoặc click để chọn file</p>
                  <input type="file" id="fileInput" multiple class="d-none">
                </div>
                <div id="fileList" class="mt-2"></div>
              </div>
            </div>

            <!-- Cột phải -->
            <div class="col-md-6">
              <div class="mb-3">
                <label for="assignee" class="form-label fw-bold">Người nhận</label>
                <select class="form-select form-select-lg" id="assignee" required>
                  <option value="">Chọn người nhận</option>
                  <option value="1">
                    👤 Ngọc Anh - Nhân viên Marketing
                  </option>
                  <option value="2">
                    👤 Minh Quân - Designer
                  </option>
                  <option value="3">
                    👤 Hồng Nhung - Kế toán
                  </option>
                </select>
              </div>

              <div class="mb-3">
                <label for="deadline" class="form-label fw-bold">Deadline</label>
                <input type="datetime-local" class="form-control form-control-lg" id="deadline" required>
              </div>

              <div class="mb-4">
                <label class="form-label fw-bold">Độ ưu tiên</label>
                <div class="d-flex gap-3">
                  <div class="form-check">
                    <input class="form-check-input" type="radio" name="priority" id="priorityLow" value="low">
                    <label class="form-check-label priority-low" for="priorityLow">
                      <i class="fas fa-arrow-down"></i> Thấp
                    </label>
                  </div>
                  <div class="form-check">
                    <input class="form-check-input" type="radio" name="priority" id="priorityMedium" value="medium" checked>
                    <label class="form-check-label priority-medium" for="priorityMedium">
                      <i class="fas fa-minus"></i> Trung bình
                    </label>
                  </div>
                  <div class="form-check">
                    <input class="form-check-input" type="radio" name="priority" id="priorityHigh" value="high">
                    <label class="form-check-label priority-high" for="priorityHigh">
                      <i class="fas fa-arrow-up"></i> Cao
                    </label>
                  </div>
                </div>
              </div>

              <div class="d-grid">
                <button type="submit" class="btn btn-success btn-lg fw-bold">
                  🚀 Giao việc
                </button>
              </div>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
// Xử lý file upload
document.getElementById('fileInput').addEventListener('change', function(e) {
  const files = e.target.files;
  const fileList = document.getElementById('fileList');
  fileList.innerHTML = '';
  
  Array.from(files).forEach(file => {
    const fileItem = document.createElement('div');
    fileItem.className = 'alert alert-info d-flex justify-content-between align-items-center';
    fileItem.innerHTML = `
      <span>📎 ${file.name}</span>
      <button type="button" class="btn-close" onclick="this.parentElement.remove()"></button>
    `;
    fileList.appendChild(fileItem);
  });
});

// Drag & Drop
const dropZone = document.querySelector('.file-drop-zone');

dropZone.addEventListener('dragover', (e) => {
  e.preventDefault();
  dropZone.style.borderColor = '#0d6efd';
  dropZone.style.backgroundColor = '#f8f9fa';
});

dropZone.addEventListener('dragleave', (e) => {
  e.preventDefault();
  dropZone.style.borderColor = '#dee2e6';
  dropZone.style.backgroundColor = 'transparent';
});

dropZone.addEventListener('drop', (e) => {
  e.preventDefault();
  dropZone.style.borderColor = '#dee2e6';
  dropZone.style.backgroundColor = 'transparent';
  
  const files = e.dataTransfer.files;
  document.getElementById('fileInput').files = files;
  
  // Trigger change event
  const event = new Event('change');
  document.getElementById('fileInput').dispatchEvent(event);
});
</script>
</body>
</html>
