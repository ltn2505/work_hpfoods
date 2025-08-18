@extends('layouts.edit')
@section('title','Chỉnh sửa công việc')

@push('styles')
<style>
.form-control, .form-select {
    transition: all 0.3s ease;
}

.form-control:focus, .form-select:focus {
    border-color: #007bff;
    box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
}

.file-drop-zone:hover {
    background-color: #e9ecef !important;
    border-color: #007bff !important;
}

.priority-badge {
    transition: all 0.3s ease;
}

.priority-badge:hover {
    transform: scale(1.05);
}

.btn-success {
    transition: all 0.3s ease;
}

.btn-success:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.2) !important;
}

.card {
    transition: all 0.3s ease;
}

.card:hover {
    box-shadow: 0 8px 25px rgba(0,0,0,0.1) !important;
}

/* Fix datetime-local input */
input[type="datetime-local"] {
    z-index: 9999 !important;
    position: relative !important;
    background-color: white !important;
    cursor: pointer !important;
    pointer-events: auto !important;
}
input[type="datetime-local"]::-webkit-calendar-picker-indicator {
    cursor: pointer !important;
    opacity: 1 !important;
    pointer-events: auto !important;
}
input[type="datetime-local"]::-webkit-inner-spin-button,
input[type="datetime-local"]::-webkit-outer-spin-button {
    -webkit-appearance: none;
    margin: 0;
}

/* Fix layout for edit form */
.form-container {
    position: relative;
    z-index: 1;
    background: white;
    border-radius: 12px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.08);
}

/* Ensure form elements don't overlap */
.form-control, .form-select {
    position: relative;
    z-index: 1;
}
</style>
@endpush

@section('content')
  <div class="row mb-4">
    <div class="col-6">
      <h2 class="text-primary mb-0">
        <i class="fas fa-edit me-2"></i>
        ✏️ Chỉnh sửa công việc
      </h2>
    </div>
    <div class="col-6 text-end">
      <a href="{{ route('task-detail', $task) }}" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-left me-2"></i>
        ← Quay lại
      </a>
    </div>
  </div>

  <div class="card shadow-sm border-0 form-container">
    <div class="card-body p-4">
      <form action="{{ route('tasks.update', $task) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="row g-4">
          {{-- Cột bên trái --}}
          <div class="col-lg-6">
            <div class="mb-4">
              <label class="form-label fw-bold text-dark">
                Tiêu đề <span class="text-danger">*</span>
              </label>
              <input 
                type="text" 
                name="title" 
                class="form-control form-control-lg border-2 @error('title') is-invalid @enderror" 
                placeholder="Nhập tiêu đề công việc"
                required 
                value="{{ old('title', $task->title) }}"
              >
              @error('title')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>

            <div class="mb-4">
              <label class="form-label fw-bold text-dark">Mô tả</label>
              <textarea 
                name="description" 
                rows="6" 
                class="form-control border-2 @error('description') is-invalid @enderror" 
                placeholder="Mô tả chi tiết công việc..."
              >{{ old('description', $task->description) }}</textarea>
              @error('description')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>

            <div class="mb-4">
              <label class="form-label fw-bold text-dark">File đính kèm</label>
              <div class="file-drop-zone border-2 border-dashed rounded-3 p-4 text-center" 
                   id="fileDropZone" 
                   style="border-color: #dee2e6; background-color: #f8f9fa; cursor: pointer; transition: all 0.3s ease;">
                <i class="fas fa-cloud-upload-alt fa-2x text-muted mb-2"></i>
                <p class="mb-2 text-muted">Kéo thả file vào đây hoặc click để chọn</p>
                <small class="text-muted">Hỗ trợ: PDF, DOC, DOCX, XLS, XLSX, PPT, PPTX, JPG, PNG, GIF, WEBP, MP4, AVI, MOV, WMV, FLV, WEBM (Tối đa 50MB)</small>
                <input type="file" name="files[]" multiple class="d-none" id="fileInput">
              </div>
              <div id="filePreview" class="mt-3"></div>
            </div>
          </div>

          {{-- Cột bên phải --}}
          <div class="col-lg-6">
            <div class="mb-4">
              <label class="form-label fw-bold text-dark">Người phụ trách</label>
              <select name="assignee_id" class="form-select form-select-lg border-2 @error('assignee_id') is-invalid @enderror">
                <option value="">Chọn người phụ trách</option>
                @foreach($users as $user)
                  <option value="{{ $user->id }}" 
                          {{ old('assignee_id', $task->assignee_id) == $user->id ? 'selected' : '' }}>
                    {{ $user->name }} 
                    @if($user->department)
                      ({{ $user->department->name }})
                    @endif
                  </option>
                @endforeach
              </select>
              @error('assignee_id')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
              @if(auth()->user()->isManager())
                <small class="text-muted mt-1 d-block">
                  <i class="fas fa-info-circle me-1"></i>
                  Bạn chỉ có thể giao việc cho nhân viên cùng phòng ban
                </small>
              @endif
            </div>

            <div class="mb-4">
              <label class="form-label fw-bold text-dark">Deadline</label>
              <input 
                type="datetime-local" 
                name="deadline" 
                class="form-control form-control-lg border-2 @error('deadline') is-invalid @enderror"
                value="{{ old('deadline', $task->deadline ? $task->deadline->format('Y-m-d\TH:i') : '') }}"
                style="z-index: 9999; position: relative; background-color: white; cursor: pointer;"
              >
              @error('deadline')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>

            <div class="mb-4">
              <label class="form-label fw-bold text-dark">Độ ưu tiên</label>
              <div class="d-flex gap-2">
                <label class="priority-badge border rounded-3 p-3 flex-fill text-center cursor-pointer" 
                       style="cursor: pointer; border-color: #28a745 !important; background-color: #d4edda;">
                  <input type="radio" name="priority" value="low" class="d-none" 
                         {{ old('priority', $task->priority) == 'low' ? 'checked' : '' }}>
                  <i class="fas fa-arrow-down text-success mb-2"></i>
                  <div class="fw-bold text-success">Thấp</div>
                </label>
                <label class="priority-badge border rounded-3 p-3 flex-fill text-center cursor-pointer" 
                       style="cursor: pointer; border-color: #ffc107 !important; background-color: #fff3cd;">
                  <input type="radio" name="priority" value="medium" class="d-none" 
                         {{ old('priority', $task->priority) == 'medium' ? 'checked' : '' }}>
                  <i class="fas fa-minus text-warning mb-2"></i>
                  <div class="fw-bold text-warning">Trung bình</div>
                </label>
                <label class="priority-badge border rounded-3 p-3 flex-fill text-center cursor-pointer" 
                       style="cursor: pointer; border-color: #dc3545 !important; background-color: #f8d7da;">
                  <input type="radio" name="priority" value="high" class="d-none" 
                         {{ old('priority', $task->priority) == 'high' ? 'checked' : '' }}>
                  <i class="fas fa-arrow-up text-danger mb-2"></i>
                  <div class="fw-bold text-danger">Cao</div>
                </label>
              </div>
            </div>

            <div class="mb-4">
              <label class="form-label fw-bold text-dark">Trạng thái <span class="text-danger">*</span></label>
              <select name="status" class="form-select form-select-lg border-2 @error('status') is-invalid @enderror" required>
                <option value="in_progress" {{ old('status', $task->status) == 'in_progress' ? 'selected' : '' }}>
                  Đang làm
                </option>
                <option value="completed" {{ old('status', $task->status) == 'completed' ? 'selected' : '' }}>
                  Chờ duyệt
                </option>
                <option value="rejected" {{ old('status', $task->status) == 'rejected' ? 'selected' : '' }}>
                  Từ chối
                </option>
                <option value="overdue" {{ old('status', $task->status) == 'overdue' ? 'selected' : '' }}>
                  Trễ hạn
                </option>
                <option value="finished" {{ old('status', $task->status) == 'finished' ? 'selected' : '' }}>
                  Kết thúc
                </option>
              </select>
              @error('status')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>
          </div>
        </div>

        <div class="text-center mt-4">
          <button type="submit" class="btn btn-success btn-lg px-5 py-3 shadow">
            <i class="fas fa-save me-2"></i>
            💾 Cập nhật công việc
          </button>
        </div>
      </form>
    </div>
  </div>

@push('scripts')
<script>
// Debug datetime input
document.addEventListener('DOMContentLoaded', function() {
    const deadlineInput = document.querySelector('input[name="deadline"]');
    if (deadlineInput) {
        console.log('Deadline input found:', deadlineInput);
        deadlineInput.addEventListener('click', function() {
            console.log('Deadline input clicked');
        });
        deadlineInput.addEventListener('focus', function() {
            console.log('Deadline input focused');
        });
    } else {
        console.log('Deadline input not found');
    }
});

// Xử lý file upload
document.getElementById('fileDropZone').addEventListener('click', function() {
    document.getElementById('fileInput').click();
});

document.getElementById('fileDropZone').addEventListener('dragover', function(e) {
    e.preventDefault();
    this.style.backgroundColor = '#e9ecef';
    this.style.borderColor = '#007bff';
});

document.getElementById('fileDropZone').addEventListener('dragleave', function(e) {
    e.preventDefault();
    this.style.backgroundColor = '#f8f9fa';
    this.style.borderColor = '#dee2e6';
});

document.getElementById('fileDropZone').addEventListener('drop', function(e) {
    e.preventDefault();
    this.style.backgroundColor = '#f8f9fa';
    this.style.borderColor = '#dee2e6';
    
    const files = e.dataTransfer.files;
    document.getElementById('fileInput').files = files;
    updateFilePreview();
});

document.getElementById('fileInput').addEventListener('change', updateFilePreview);

function updateFilePreview() {
    const fileInput = document.getElementById('fileInput');
    const preview = document.getElementById('filePreview');
    const files = fileInput.files;
    
    preview.innerHTML = '';
    
    for (let i = 0; i < files.length; i++) {
        const file = files[i];
        const fileSize = (file.size / 1024 / 1024).toFixed(2);
        
        const fileDiv = document.createElement('div');
        fileDiv.className = 'alert alert-info d-flex align-items-center justify-content-between mb-2';
        fileDiv.innerHTML = `
            <div>
                <i class="fas fa-file me-2"></i>
                <strong>${file.name}</strong>
                <small class="text-muted ms-2">(${fileSize} MB)</small>
            </div>
            <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeFile(${i})">
                <i class="fas fa-times"></i>
            </button>
        `;
        preview.appendChild(fileDiv);
    }
}

function removeFile(index) {
    const fileInput = document.getElementById('fileInput');
    const dt = new DataTransfer();
    const files = fileInput.files;
    
    for (let i = 0; i < files.length; i++) {
        if (i !== index) {
            dt.items.add(files[i]);
        }
    }
    
    fileInput.files = dt.files;
    updateFilePreview();
}

// Xử lý priority badges
document.querySelectorAll('.priority-badge').forEach(badge => {
    badge.addEventListener('click', function() {
        // Bỏ chọn tất cả
        document.querySelectorAll('.priority-badge').forEach(b => {
            b.style.backgroundColor = '';
            b.style.borderColor = '';
        });
        
        // Chọn badge này
        const radio = this.querySelector('input[type="radio"]');
        radio.checked = true;
        
        // Highlight badge được chọn
        if (radio.value === 'low') {
            this.style.backgroundColor = '#d4edda';
            this.style.borderColor = '#28a745';
        } else if (radio.value === 'medium') {
            this.style.backgroundColor = '#fff3cd';
            this.style.borderColor = '#ffc107';
        } else if (radio.value === 'high') {
            this.style.backgroundColor = '#f8d7da';
            this.style.borderColor = '#dc3545';
        }
    });
});

// Highlight priority badge ban đầu
document.addEventListener('DOMContentLoaded', function() {
    const selectedPriority = document.querySelector('input[name="priority"]:checked');
    if (selectedPriority) {
        const badge = selectedPriority.closest('.priority-badge');
        if (selectedPriority.value === 'low') {
            badge.style.backgroundColor = '#d4edda';
            badge.style.borderColor = '#28a745';
        } else if (selectedPriority.value === 'medium') {
            badge.style.backgroundColor = '#fff3cd';
            badge.style.borderColor = '#ffc107';
        } else if (selectedPriority.value === 'high') {
            badge.style.backgroundColor = '#f8d7da';
            badge.style.borderColor = '#dc3545';
        }
    }
});
</script>
@endpush
