@extends('layouts.master')
@section('title','Tạo công việc')

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
    z-index: 1 !important;
    position: relative !important;
    background-color: white !important;
}
input[type="datetime-local"]::-webkit-calendar-picker-indicator {
    cursor: pointer;
    opacity: 1;
}

/* Custom Dropdown Styles */
.custom-dropdown {
    position: relative;
    width: 100%;
}

.dropdown-header {
    border: 2px solid #dee2e6;
    border-radius: 0.375rem;
    padding: 0.75rem 1rem;
    background-color: white;
    cursor: pointer;
    display: flex;
    justify-content: space-between;
    align-items: center;
    transition: all 0.3s ease;
}

.dropdown-header:hover {
    border-color: #007bff;
    box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
}

.dropdown-header.active {
    border-color: #007bff;
    box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
}

.dropdown-arrow {
    transition: transform 0.3s ease;
}

.dropdown-header.active .dropdown-arrow {
    transform: rotate(180deg);
}

.dropdown-content {
    position: absolute;
    top: 100%;
    left: 0;
    right: 0;
    background: white;
    border: 2px solid #dee2e6;
    border-top: none;
    border-radius: 0 0 0.375rem 0.375rem;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    z-index: 1000;
    display: none;
}

.dropdown-content.show {
    display: block;
}

.dropdown-item {
    padding: 0.5rem 1rem;
    cursor: pointer;
    transition: background-color 0.2s ease;
    display: flex;
    align-items: center;
}

.dropdown-item:hover {
    background-color: #f8f9fa;
}

.dropdown-item input[type="checkbox"] {
    margin-right: 0.5rem;
}

.dropdown-item label {
    margin: 0;
    cursor: pointer;
    flex-grow: 1;
}

.dropdown-group-header {
    background-color: #e9ecef;
    padding: 0.5rem 1rem;
    font-weight: bold;
    font-size: 0.875rem;
    color: #495057;
    border-bottom: 1px solid #dee2e6;
}

.dropdown-group {
    border-bottom: 1px solid #f1f3f4;
}

.dropdown-group:last-child {
    border-bottom: none;
}
</style>
@endpush

@section('content')
<div class="container-fluid">
  <div class="row mb-4">
    <div class="col-6">
      <h2 class="text-primary mb-0">
        <i class="fas fa-plus-circle me-2"></i>
        + Tạo công việc mới
      </h2>
    </div>
    <div class="col-6 text-end">
      <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-left me-2"></i>
        ← Quay lại
      </a>
    </div>
  </div>

  <div class="card shadow-sm border-0">
    <div class="card-body p-4">
      <form action="{{ route('tasks.store') }}" method="POST" enctype="multipart/form-data" id="createTaskForm">
            {{-- Hidden fields for multi-user and multi-department --}}
            <input type="hidden" name="is_multi_user" id="is_multi_user" value="0">
            <input type="hidden" name="is_multi_department" id="is_multi_department" value="0">
        @csrf
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
                class="form-control form-control-lg border-2" 
                placeholder="Nhập tiêu đề công việc"
                required 
                value="{{ old('title') }}"
              >
            </div>

            <div class="mb-4">
              <label class="form-label fw-bold text-dark">Mô tả</label>
              <textarea 
                name="description" 
                rows="6" 
                class="form-control border-2" 
                placeholder="Mô tả chi tiết công việc..."
                id="descriptionTextarea"
              >{{ old('description') }}</textarea>
              <div id="descriptionError" class="text-danger mt-1" style="display: none;">
                <i class="bi bi-exclamation-triangle me-1"></i>
                Không được phép nhập từ dài hơn 45 ký tự!
              </div>
            </div>

            <div class="mb-4">
              <label class="form-label fw-bold text-dark">File đính kèm</label>
              <div class="file-drop-zone border-2 border-dashed rounded-3 p-4 text-center" 
                   id="fileDropZone" 
                   style="border-color: #dee2e6; background-color: #f8f9fa; cursor: pointer; transition: all 0.3s ease;">
                <i class="fas fa-cloud-upload-alt fa-2x text-muted mb-2"></i>
                <p class="mb-1 fw-medium">Kéo & thả file vào đây</p>
                <small class="text-muted">hoặc click để chọn file</small>
                <div class="mt-2">
                  <small class="text-muted">Hỗ trợ: PDF, DOC, DOCX, XLS, XLSX, PPT, PPTX, JPG, PNG, GIF, WEBP, MP4, AVI, MOV, WMV, FLV, WEBM (Tối đa 300MB)</small>
                </div>
                <div id="fileList" class="mt-3"></div>
              </div>
              <input type="file" name="files[]" id="fileInput" class="d-none" multiple>
            </div>
          </div>

          {{-- Cột bên phải --}}
          <div class="col-lg-6">
            <div class="mb-4">
              <label class="form-label fw-bold text-dark">Người nhận</label>
              
              {{-- Select phòng ban --}}
              <div class="mb-3">
                <label class="form-label fw-bold text-dark">Chọn phòng ban</label>
                <div class="custom-dropdown">
                  <div class="dropdown-header" id="departmentDropdownHeader">
                    <span class="dropdown-text">Chọn phòng ban...</span>
                    <i class="fas fa-chevron-down dropdown-arrow"></i>
                  </div>
                  <div class="dropdown-content" id="departmentDropdownContent">
                    @foreach($departments as $dept)
                      <div class="dropdown-item" data-value="{{ $dept->id }}" data-count="{{ $dept->users->count() }}">
                        <input type="checkbox" class="dept-checkbox" name="department_ids[]" value="{{ $dept->id }}" id="dept_{{ $dept->id }}">
                        <label for="dept_{{ $dept->id }}">
                          {{ $dept->name }} <span class="badge bg-secondary ms-1">{{ $dept->users->count() }}</span>
                        </label>
                      </div>
                    @endforeach
                  </div>
                </div>
                <button type="button" id="confirmDepartments" class="btn btn-primary btn-sm mt-2">
                  <i class="fas fa-check me-1"></i>Xác nhận phòng ban
                </button>
              </div>
              
              {{-- Multiple select users --}}
              <div id="userSelectSection" style="display: none;">
                <label class="form-label fw-bold text-dark">Chọn người nhận</label>
                <div class="custom-dropdown">
                  <div class="dropdown-header" id="userDropdownHeader">
                    <span class="dropdown-text">Chọn người nhận...</span>
                    <i class="fas fa-chevron-down dropdown-arrow"></i>
                  </div>
                  <div class="dropdown-content" id="userDropdownContent" style="max-height: 200px; overflow-y: auto;">
                    @foreach($departments as $dept)
                      <div class="dropdown-group" data-department="{{ $dept->id }}" style="display: none;">
                        <div class="dropdown-group-header">{{ $dept->name }}</div>
                        @foreach($dept->users as $user)
                          <div class="dropdown-item user-item" data-department="{{ $dept->id }}" data-name="{{ strtolower($user->name) }}" data-role="{{ strtolower($user->role) }}">
                            <input type="checkbox" class="user-checkbox" name="assignee_ids[]" value="{{ $user->id }}" id="user_{{ $user->id }}">
                            <label for="user_{{ $user->id }}">
                              {{ $user->name }} - <span class="text-muted">{{ ucfirst($user->role) }}</span>
                            </label>
                          </div>
                        @endforeach
                      </div>
                    @endforeach
                  </div>
                </div>
              </div>
              
              {{-- Selected users display --}}
              <div id="selectedUsers" class="mt-2" style="display: none;">
                <label class="form-label fw-bold text-dark">Đã chọn:</label>
                <div id="selectedUsersList" class="border rounded p-2 bg-light"></div>
              </div>
              
              @if(auth()->user()->isManager())
                <div class="form-text text-info">
                  <i class="fas fa-info-circle me-1"></i>
                  Bạn có thể giao việc cho employees của tất cả phòng ban. Task đa phòng ban phải bao gồm phòng ban của bạn.
                </div>
              @endif
            </div>

            <div class="mb-4">
              <label class="form-label fw-bold text-dark">Deadline</label>
              <input 
                type="datetime-local" 
                name="deadline" 
                class="form-control form-control-lg border-2" 
                value="{{ old('deadline') }}"
                style="z-index: 9999; position: relative; background-color: white; cursor: pointer;"
                min="{{ now()->format('Y-m-d\TH:i') }}"
              >
              <!-- <div class="form-text text-info">
                <i class="fas fa-info-circle me-1"></i>
                Deadline phải là thời gian trong tương lai
              </div> -->
              @error('deadline')
                <div class="text-danger mt-1">
                  <i class="bi bi-exclamation-triangle me-1"></i>
                  {{ $message }}
                </div>
              @enderror
            </div>

            <div class="mb-4">
              <label class="form-label fw-bold text-dark d-block mb-3">Độ ưu tiên</label>
              <div class="d-flex gap-3">
                <div class="form-check">
                  <input class="form-check-input" type="radio" name="priority" value="low" id="priorityLow" {{ old('priority') == 'low' ? 'checked' : '' }}>
                  <label class="form-check-label fw-medium" for="priorityLow">
                    <span class="badge bg-success px-3 py-2">Thấp</span>
                  </label>
                </div>
                <div class="form-check">
                  <input class="form-check-input" type="radio" name="priority" value="medium" id="priorityMedium" {{ old('priority') == 'medium' || !old('priority') ? 'checked' : '' }}>
                  <label class="form-check-label fw-medium" for="priorityMedium">
                    <span class="badge bg-warning px-3 py-2">Trung bình</span>
                  </label>
                </div>
                <div class="form-check">
                  <input class="form-check-input" type="radio" name="priority" value="high" id="priorityHigh" {{ old('priority') == 'high' ? 'checked' : '' }}>
                  <label class="form-check-label fw-medium" for="priorityHigh">
                    <span class="badge bg-danger px-3 py-2">Cao</span>
                  </label>
                </div>
              </div>
            </div>

            {{-- Lặp lại công việc --}}
            <div class="mb-4">
              <div class="form-check">
                <input 
                  class="form-check-input" 
                  type="checkbox" 
                  name="is_recurring" 
                  id="isRecurring" 
                  value="1"
                  {{ old('is_recurring') ? 'checked' : '' }}
                >
                <label class="form-check-label fw-bold text-dark" for="isRecurring">
                  <i class="fas fa-repeat me-2"></i>Lặp lại công việc
                </label>
                <div class="form-text text-info">
                  <i class="fas fa-info-circle me-1"></i>
                  Hệ thống sẽ tự động tính số ngày từ task gốc và cập nhật deadline định kỳ
                </div>
              </div>
              
              {{-- Thông tin lặp lại (hiển thị khi có deadline) --}}
              <div id="recurringInfo" class="mt-3 p-3 bg-light rounded" style="display: none;">
                <div class="row">
                  <div class="col-md-6">
                    <label class="form-label fw-bold text-dark">Ngày bắt đầu:</label>
                    <input 
                      type="date" 
                      name="recurring_start_date" 
                      id="recurringStartDate"
                      class="form-control border-2" 
                      value="{{ old('recurring_start_date', now()->format('Y-m-d')) }}"
                      min="{{ now()->format('Y-m-d') }}"
                    >
                  </div>
                  <div class="col-md-6">
                    <label class="form-label fw-bold text-dark">Thời gian lặp lại:</label>
                    <div class="input-group">
                      <input 
                        type="number" 
                        name="recurring_days" 
                        id="recurringDays"
                        class="form-control border-2" 
                        value="{{ old('recurring_days') }}"
                        min="1"
                        max="365"
                        readonly
                      >
                      <span class="input-group-text">ngày</span>
                    </div>
                    <small class="text-muted">Tự động tính từ deadline</small>
                  </div>
                </div>
                <div class="mt-2">
                  <small class="text-info">
                    <i class="fas fa-calendar-alt me-1"></i>
                    <span id="recurringPreview">Ví dụ: Nếu deadline là 22/8, công việc sẽ lặp lại mỗi 3 ngày</span>
                  </small>
                </div>
              </div>
            </div>
          </div>
        </div>

        {{-- Nút giao việc --}}
        <div class="row mt-5">
          <div class="col-12 text-center">
            <button type="submit" class="btn btn-success btn-lg px-5 py-3 fw-bold shadow-sm" id="submitBtn">
              <i class="fas fa-rocket me-2"></i>
              🚀 Giao việc
            </button>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script>
// Fix datetime-local input
document.addEventListener('DOMContentLoaded', function() {
    const deadlineInput = document.querySelector('input[name="deadline"]');
    const descriptionTextarea = document.getElementById('descriptionTextarea');
    const descriptionError = document.getElementById('descriptionError');
    const submitBtn = document.getElementById('submitBtn');
    const form = document.querySelector('form');

    function checkWordLength(text) {
        const words = text.trim().split(/\s+/);
        return words.every(word => word.length <= 45);
    }

    function validateDescription() {
        const text = descriptionTextarea.value;
        const isValid = checkWordLength(text);
        
        if (!isValid) {
            descriptionError.style.display = 'block';
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-exclamation-triangle me-2"></i>Từ quá dài (>45 ký tự)';
            submitBtn.classList.remove('btn-success');
            submitBtn.classList.add('btn-danger');
        } else {
            descriptionError.style.display = 'none';
            submitBtn.disabled = false;
            submitBtn.innerHTML = '<i class="fas fa-rocket me-2"></i>🚀 Giao việc';
            submitBtn.classList.remove('btn-danger');
            submitBtn.classList.add('btn-success');
        }
    }

    descriptionTextarea.addEventListener('input', validateDescription);
    descriptionTextarea.addEventListener('paste', validateDescription);

    form.addEventListener('submit', function(e) {
        const text = descriptionTextarea.value;
        if (text && !checkWordLength(text)) {
            e.preventDefault();
            alert('Không được phép nhập từ dài hơn 45 ký tự!');
            return false;
        }
    });

    if (deadlineInput) {
        console.log('Create: Deadline input found');
        deadlineInput.addEventListener('click', function() {
            console.log('Create: Deadline input clicked');
            this.showPicker && this.showPicker();
        });
        deadlineInput.addEventListener('focus', function() {
            console.log('Create: Deadline input focused');
        });
    }

    

          // Validation deadline không được trong quá khứ
          if (deadlineInput) {
              deadlineInput.addEventListener('change', function() {
                  const selectedDate = new Date(this.value);
                  const now = new Date();
                  
                  if (selectedDate <= now) {
                      this.setCustomValidity('Deadline không được đặt trong quá khứ');
                      this.classList.add('is-invalid');
                  } else {
                      this.setCustomValidity('');
                      this.classList.remove('is-invalid');
                  }
                  
                  // Cập nhật thông tin lặp lại
                  updateRecurringInfo();
              });
          }

          // Xử lý checkbox lặp lại
          const isRecurringCheckbox = document.getElementById('isRecurring');
          const recurringInfo = document.getElementById('recurringInfo');
          
          if (isRecurringCheckbox && recurringInfo) {
              isRecurringCheckbox.addEventListener('change', function() {
                  if (this.checked) {
                      recurringInfo.style.display = 'block';
                      updateRecurringInfo();
                  } else {
                      recurringInfo.style.display = 'none';
                  }
              });
          }

          // Cập nhật thông tin lặp lại
          function updateRecurringInfo() {
              const recurringDaysInput = document.getElementById('recurringDays');
              const recurringPreview = document.getElementById('recurringPreview');
              const recurringStartDate = document.getElementById('recurringStartDate');
              
              if (deadlineInput && deadlineInput.value && isRecurringCheckbox.checked) {
                  const startDate = new Date();
                  const deadline = new Date(deadlineInput.value);
                  const daysDiff = Math.ceil((deadline - startDate) / (1000 * 60 * 60 * 24));
                  
                  if (daysDiff > 0) {
                      recurringDaysInput.value = daysDiff;
                      
                      // Cập nhật preview
                      const deadlineFormatted = deadline.toLocaleDateString('vi-VN');
                      recurringPreview.textContent = `Công việc sẽ lặp lại mỗi ${daysDiff} ngày từ ngày bắt đầu`;
                      
                      // Cập nhật ngày bắt đầu lặp lại
                      if (recurringStartDate) {
                          recurringStartDate.value = startDate.toISOString().split('T')[0];
                      }
                  }
              }
          }
    
    // File upload handling
    const dropZone = document.getElementById('fileDropZone');
    const fileInput = document.getElementById('fileInput');
    const fileList = document.getElementById('fileList');

    dropZone.addEventListener('click', function() {
        fileInput.click();
    });

    dropZone.addEventListener('dragover', function(e) {
        e.preventDefault();
        dropZone.style.background = '#e9ecef';
        dropZone.style.borderColor = '#007bff';
    });

    dropZone.addEventListener('dragleave', function(e) {
        e.preventDefault();
        dropZone.style.background = '#f8f9fa';
        dropZone.style.borderColor = '#dee2e6';
    });

    dropZone.addEventListener('drop', function(e) {
        e.preventDefault();
        dropZone.style.background = '#f8f9fa';
        dropZone.style.borderColor = '#dee2e6';
        fileInput.files = e.dataTransfer.files;
        showFiles();
    });

    fileInput.addEventListener('change', showFiles);

    function showFiles() {
        fileList.innerHTML = '';
        if (fileInput.files.length > 0) {
            for (let i = 0; i < fileInput.files.length; i++) {
                const file = fileInput.files[i];
                const fileDiv = document.createElement('div');
                fileDiv.className = 'alert alert-info d-flex align-items-center justify-content-between mb-2';
                fileDiv.innerHTML = `
                    <div class="d-flex align-items-center">
                        <i class="fas fa-file me-2"></i>
                        <span class="fw-medium">${file.name}</span>
                        <small class="text-muted ms-2">(${formatFileSize(file.size)})</small>
                    </div>
                    <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeFile(${i})">
                        <i class="fas fa-times"></i>
                    </button>
                `;
                fileList.appendChild(fileDiv);
            }
        }
    }

    function formatFileSize(bytes) {
        if (bytes === 0) return '0 Bytes';
        const k = 1024;
        const sizes = ['Bytes', 'KB', 'MB', 'GB'];
        const i = Math.floor(Math.log(bytes) / Math.log(k));
        return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
    }

    window.removeFile = function(index) {
        const dt = new DataTransfer();
        const { files } = fileInput;
        
        for (let i = 0; i < files.length; i++) {
            if (i !== index) {
                dt.items.add(files[i]);
            }
        }
        
        fileInput.files = dt.files;
        showFiles();
    };

    // Department and user selection handling
    const departmentDropdownHeader = document.getElementById('departmentDropdownHeader');
    const departmentDropdownContent = document.getElementById('departmentDropdownContent');
    const userDropdownHeader = document.getElementById('userDropdownHeader');
    const userDropdownContent = document.getElementById('userDropdownContent');
    const confirmDepartmentsBtn = document.getElementById('confirmDepartments');
    const searchSection = document.getElementById('searchSection');
    const userSelectSection = document.getElementById('userSelectSection');
    const userSearch = document.getElementById('userSearch');
    const clearSearchBtn = document.getElementById('clearSearch');
    const selectedUsers = document.getElementById('selectedUsers');
    const selectedUsersList = document.getElementById('selectedUsersList');



    // Department dropdown toggle
    departmentDropdownHeader.addEventListener('click', function() {
        departmentDropdownContent.classList.toggle('show');
        this.classList.toggle('active');
    });

    // User dropdown toggle
    if (userDropdownHeader) {
        userDropdownHeader.addEventListener('click', function() {
            userDropdownContent.classList.toggle('show');
            this.classList.toggle('active');
        });
    }

    // Close dropdowns when clicking outside
    document.addEventListener('click', function(e) {
        if (!departmentDropdownHeader.contains(e.target) && !departmentDropdownContent.contains(e.target)) {
            departmentDropdownContent.classList.remove('show');
            departmentDropdownHeader.classList.remove('active');
        }
        if (userDropdownHeader && !userDropdownHeader.contains(e.target) && !userDropdownContent.contains(e.target)) {
            userDropdownContent.classList.remove('show');
            userDropdownHeader.classList.remove('active');
        }
    });

    // Update department dropdown text
    function updateDepartmentDropdownText() {
        const checkedDepts = document.querySelectorAll('.dept-checkbox:checked');
        const dropdownText = departmentDropdownHeader.querySelector('.dropdown-text');
        
        if (checkedDepts.length === 0) {
            dropdownText.textContent = 'Chọn phòng ban...';
        } else if (checkedDepts.length === 1) {
            const label = checkedDepts[0].nextElementSibling;
            // Lấy chỉ tên phòng ban, bỏ qua badge số lượng
            const deptName = label.childNodes[0].textContent.trim();
            dropdownText.textContent = deptName;
        } else {
            dropdownText.textContent = `Đã chọn ${checkedDepts.length} phòng ban`;
        }
    }

    // Department checkbox change handler
    document.querySelectorAll('.dept-checkbox').forEach(checkbox => {
        checkbox.addEventListener('change', updateDepartmentDropdownText);
        checkbox.addEventListener('change', updateMultiDepartmentFlag);
    });

    // Update multi-department flag
    function updateMultiDepartmentFlag() {
        const checkedDepts = document.querySelectorAll('.dept-checkbox:checked');
        const isMultiDept = checkedDepts.length > 1;
        document.getElementById('is_multi_department').value = isMultiDept ? '1' : '0';
    }

    // Update multi-user flag
    function updateMultiUserFlag() {
        const checkedUsers = document.querySelectorAll('.user-checkbox:checked');
        const isMultiUser = checkedUsers.length > 1;
        document.getElementById('is_multi_user').value = isMultiUser ? '1' : '0';
    }

    // Confirm departments selection
    confirmDepartmentsBtn.addEventListener('click', function() {
        const selectedDepts = Array.from(document.querySelectorAll('.dept-checkbox:checked'))
            .map(checkbox => checkbox.value);
        
        console.log('Selected departments:', selectedDepts);
        
        if (selectedDepts.length === 0) {
            alert('Vui lòng chọn ít nhất một phòng ban!');
            return;
        }
        
        // Show user selection section
        if (searchSection) {
            searchSection.style.display = 'block';
        }
        if (userSelectSection) {
            userSelectSection.style.display = 'block';
        }
        
        // Filter users by selected departments
        const userGroups = document.querySelectorAll('.dropdown-group');
        console.log('Total user groups:', userGroups.length);
        
        // Hide all groups first
        userGroups.forEach(group => {
            group.style.display = 'none';
            console.log('Hiding group:', group.dataset.department);
        });
        
        // Show only selected departments
        selectedDepts.forEach(deptId => {
            const group = document.querySelector(`[data-department="${deptId}"]`);
            console.log('Looking for department:', deptId, 'Found group:', group);
            if (group) {
                group.style.display = 'block';
                console.log('Showing group for department:', deptId);
            }
        });
        
        // Update button text
        this.innerHTML = '<i class="fas fa-edit me-1"></i>Thay đổi phòng ban';
        this.className = 'btn btn-warning btn-sm mt-2';
    });

    // Search functionality
    if (userSearch) {
        userSearch.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            const userItems = document.querySelectorAll('.user-item');
            
            userItems.forEach(item => {
                const userName = item.dataset.name;
                const userRole = item.dataset.role;
                const searchText = userName + ' ' + userRole;
                
                if (searchText.includes(searchTerm)) {
                    item.style.display = 'flex';
                } else {
                    item.style.display = 'none';
                }
            });
        });
    }

    // Clear search
    if (clearSearchBtn) {
        clearSearchBtn.addEventListener('click', function() {
            userSearch.value = '';
            userSearch.dispatchEvent(new Event('input'));
        });
    }

    // Update user dropdown text
    function updateUserDropdownText() {
        const checkedUsers = document.querySelectorAll('.user-checkbox:checked');
        const dropdownText = userDropdownHeader.querySelector('.dropdown-text');
        
        if (checkedUsers.length === 0) {
            dropdownText.textContent = 'Chọn người nhận...';
            selectedUsers.style.display = 'none';
        } else if (checkedUsers.length === 1) {
            const label = checkedUsers[0].nextElementSibling.textContent.trim();
            dropdownText.textContent = label;
            updateSelectedUsersList();
        } else {
            dropdownText.textContent = `Đã chọn ${checkedUsers.length} người`;
            updateSelectedUsersList();
        }
    }

    // Update selected users list
    function updateSelectedUsersList() {
        const checkedUsers = document.querySelectorAll('.user-checkbox:checked');
        
        if (checkedUsers.length > 0) {
            selectedUsers.style.display = 'block';
            selectedUsersList.innerHTML = '';
            
            checkedUsers.forEach(checkbox => {
                const label = checkbox.nextElementSibling.textContent.trim();
                const userDiv = document.createElement('div');
                userDiv.className = 'd-flex align-items-center justify-content-between mb-1';
                userDiv.innerHTML = `
                    <span class="badge bg-primary me-2">${label}</span>
                    <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeSelectedUser('${checkbox.value}')">
                        <i class="fas fa-times"></i>
                    </button>
                `;
                selectedUsersList.appendChild(userDiv);
            });
        } else {
            selectedUsers.style.display = 'none';
        }
    }

    // User checkbox change handler
    document.addEventListener('change', function(e) {
        if (e.target.classList.contains('user-checkbox')) {
            updateUserDropdownText();
            updateMultiUserFlag();
        }
    });

    // Remove selected user
    window.removeSelectedUser = function(userId) {
        const checkbox = document.querySelector(`.user-checkbox[value="${userId}"]`);
        if (checkbox) {
            checkbox.checked = false;
            updateUserDropdownText();
        }
    };

    // Debug form submission
    document.getElementById('createTaskForm').addEventListener('submit', function(e) {
        const checkedUsers = document.querySelectorAll('.user-checkbox:checked');
        console.log('Form submitting...');
        console.log('Checked users:', checkedUsers.length);
        
        if (checkedUsers.length === 0) {
            alert('Vui lòng chọn ít nhất một người nhận!');
            e.preventDefault();
            return false;
        }
        
        checkedUsers.forEach(user => {
            console.log('Selected user:', user.value, user.nextElementSibling.textContent.trim());
        });
    });


});
</script>
@endpush
