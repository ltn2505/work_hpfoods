@extends('layouts.edit')
@section('title','Chỉnh sửa công việc')

@push('styles')
<style>
/* Container and layout */
.edit-container {
    max-width: 800px;
    margin: 0 auto;
    padding: 20px;
}

.form-control, .form-select {
    transition: all 0.3s ease;
    border-radius: 8px;
    border: 1px solid #dee2e6;
    padding: 12px 16px;
    font-size: 16px;
}

.form-control:focus, .form-select:focus {
    border-color: #558EC1;
    box-shadow: 0 0 0 0.2rem rgba(85, 142, 193, 0.25);
}

/* File upload area */
.file-drop-zone {
    border: 2px dashed #dee2e6;
    border-radius: 12px;
    padding: 40px 20px;
    text-align: center;
    background: #f8f9fa;
    transition: all 0.3s ease;
    cursor: pointer;
    min-height: 150px;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
}

.file-drop-zone:hover {
    background-color: #e9ecef !important;
    border-color: #558EC1 !important;
    transform: translateY(-2px);
}

.file-drop-zone.dragover {
    background-color: #e3f2fd !important;
    border-color: #558EC1 !important;
}

/* Priority buttons */
.priority-buttons {
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
}

.priority-btn {
    flex: 1;
    min-width: 120px;
    padding: 12px 20px;
    border: 2px solid transparent;
    border-radius: 8px;
    font-weight: 600;
    transition: all 0.3s ease;
    cursor: pointer;
    text-align: center;
}

.priority-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
}

.priority-btn.active {
    border-color: currentColor;
    transform: scale(1.05);
}

.priority-low {
    background: #d4edda;
    color: #155724;
}

.priority-medium {
    background: #fff3cd;
    color: #856404;
}

.priority-high {
    background: #f8d7da;
    color: #721c24;
}

/* Submit button */
.btn-submit {
    background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
    border: none;

/* Recurring task styling */
#recurringInfo {
    border: 1px solid #e9ecef;
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
}

#recurringInfo .form-control {
    border: 2px solid #dee2e6;
    transition: all 0.3s ease;
}

#recurringInfo .form-control:focus {
    border-color: #558EC1;
    box-shadow: 0 0 0 0.2rem rgba(85, 142, 193, 0.25);
}

.form-check-input:checked {
    background-color: #558EC1;
    border-color: #558EC1;
}
    border-radius: 10px;
    padding: 15px 30px;
    font-size: 18px;
    font-weight: 600;
    transition: all 0.3s ease;
    box-shadow: 0 4px 15px rgba(40, 167, 69, 0.3);
}

.btn-submit:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 25px rgba(40, 167, 69, 0.4);
}

/* Rejection reason styling */
#rejection_reason_group {
    transition: all 0.3s ease;
    border-left: 4px solid #558EC1;
    padding-left: 15px;
    background: rgba(85, 142, 193, 0.05);
    border-radius: 8px;
    margin-top: 10px;
}

#rejection_reason_group label {
    color: #558EC1;
    font-weight: 600;
}

#rejection_reason_group textarea {
    border-color: #558EC1;
}

#rejection_reason_group textarea:focus {
    border-color: #558EC1;
    box-shadow: 0 0 0 0.2rem rgba(85, 142, 193, 0.25);
}

/* Card styling */
.card {
    border-radius: 15px;
    border: none;
    box-shadow: 0 8px 30px rgba(0,0,0,0.1);
    overflow: hidden;
}

.card-header {
    background: linear-gradient(135deg, #558EC1 0%, #5DA444 100%);
    color: white;
    border: none;
    padding: 20px 25px;
}

/* Form groups */
.form-group {
    margin-bottom: 25px;
}

.form-label {
    font-weight: 600;
    color: #495057;
    margin-bottom: 8px;
    display: block;
}

/* Responsive */
@media (max-width: 768px) {
    .edit-container {
        padding: 15px;
    }
    
    .priority-buttons {
        flex-direction: column;
    }
    
    .priority-btn {
        min-width: auto;
    }
    
    .card-body {
        padding: 20px;
    }
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

/* Department and User Selection Styles */
.department-dropdown {
    position: relative;
    display: inline-block;
}

.dropdown-toggle {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 12px 16px;
    border: 1px solid #dee2e6;
    border-radius: 8px;
    background: white;
    cursor: pointer;
    transition: all 0.3s ease;
    min-width: 200px;
}

.dropdown-toggle:hover {
    border-color: #558EC1;
    box-shadow: 0 0 0 0.2rem rgba(85, 142, 193, 0.25);
}

.dropdown-menu {
    position: absolute;
    top: 100%;
    left: 0;
    right: 0;
    background: white;
    border: 1px solid #dee2e6;
    border-radius: 8px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    z-index: 1000;
    max-height: 200px;
    overflow-y: auto;
    display: none;
}

.dropdown-menu.show {
    display: block;
}

.dropdown-item {
    padding: 8px 16px;
    cursor: pointer;
    transition: background-color 0.2s ease;
}

.dropdown-item:hover {
    background-color: #f8f9fa;
}

.dropdown-item input[type="checkbox"] {
    margin-right: 8px;
}

.user-selection-area {
    border: 1px solid #dee2e6;
    border-radius: 8px;
    padding: 16px;
    background: #f8f9fa;
}

.user-list {
    max-height: 200px;
    overflow-y: auto;
    border: 1px solid #dee2e6;
    border-radius: 6px;
    background: white;
}

.user-item {
    padding: 8px 12px;
    border-bottom: 1px solid #f1f3f4;
    cursor: pointer;
    transition: background-color 0.2s ease;
    display: flex;
    align-items: center;
    justify-content: space-between;
}

.user-item:hover {
    background-color: #e3f2fd;
}

.user-item.selected {
    background-color: #558EC1;
    color: white;
}

.user-item:last-child {
    border-bottom: none;
}

.selected-users-display {
    min-height: 50px;
    border: 1px solid #dee2e6;
    border-radius: 8px;
    padding: 12px;
    background: #f8f9fa;
}

.selected-user-badge {
    display: inline-flex;
    align-items: center;
    background: #558EC1;
    color: white;
    padding: 4px 8px;
    border-radius: 16px;
    margin: 2px;
    font-size: 0.875rem;
}

.selected-user-badge i {
    margin-left: 6px;
    cursor: pointer;
    font-size: 0.75rem;
}

.selected-user-badge i:hover {
    color: #ff6b6b;
}
</style>
@endpush

@section('content')
<div class="edit-container">
    {{-- Header --}}
    <div class="card mb-4">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <h2 class="mb-0">
                    <i class="bi bi-pencil-square me-2"></i>
                    Chỉnh sửa công việc
                </h2>
                <a href="{{ route('task-detail', $task) }}" class="btn btn-outline-light">
                    <i class="bi bi-arrow-left me-2"></i>
                    Quay lại
                </a>
            </div>
        </div>
    </div>

    {{-- Form --}}
    <div class="card">
        <div class="card-body p-4">
            <form action="{{ route('tasks.update', $task) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                {{-- Tiêu đề --}}
                <div class="form-group">
                    <label for="title" class="form-label">
                        <i class="bi bi-type me-1"></i>Tiêu đề <span class="text-danger">*</span>
                    </label>
                    <input type="text" name="title" id="title" class="form-control @error('title') is-invalid @enderror" 
                           value="{{ old('title', $task->title) }}" required>
                    @error('title')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Mô tả --}}
                <div class="form-group">
                    <label for="description" class="form-label">
                        <i class="bi bi-text-paragraph me-1"></i>Mô tả
                    </label>
                    <textarea name="description" id="description" rows="4" class="form-control @error('description') is-invalid @enderror" 
                              placeholder="Mô tả chi tiết công việc...">{{ old('description', $task->description) }}</textarea>
                    <div id="descriptionError" class="text-danger mt-1" style="display: none;">
                        <i class="bi bi-exclamation-triangle me-1"></i>
                        Không được phép nhập từ dài hơn 45 ký tự!
                    </div>
                    @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Tệp đính kèm --}}
                <div class="form-group">
                    <label class="form-label">
                        <i class="bi bi-paperclip me-1"></i>Tệp đính kèm
                    </label>
                    <div class="file-drop-zone" onclick="document.getElementById('files').click()">
                        <i class="bi bi-cloud-upload display-4 text-muted mb-3"></i>
                        <p class="mb-2 fw-semibold">Kéo thả tệp vào đây hoặc click để chọn</p>
                        <small class="text-muted">
                            Hỗ trợ: PDF, DOC, DOCX, XLS, XLSX, PPT, PPTX, JPG, PNG, GIF, WEBP, MP4, AVI, MOV, WMV, FLV, WEBM (Tối đa 50MB)
                        </small>
                    </div>
                    <input type="file" name="files[]" id="files" multiple accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.jpg,.jpeg,.png,.gif,.webp,.mp4,.avi,.mov,.wmv,.flv,.webm" 
                           class="d-none" onchange="handleFileSelect(this)">
                    @error('files.*')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Người phụ trách --}}
                <div class="form-group">
                    <label class="form-label">
                        <i class="bi bi-people me-1"></i>Người phụ trách
                    </label>
                    
                    {{-- Chọn phòng ban --}}
                    <div class="mb-3">
                        <label class="form-label fw-bold text-dark">
                            <i class="bi bi-building me-1"></i>Chọn phòng ban:
                        </label>
                        <div class="department-dropdown">
                            <div class="dropdown-toggle" onclick="toggleDepartmentDropdown()">
                                <span id="selectedDepartmentsText">Chọn phòng ban</span>
                                <i class="bi bi-chevron-down"></i>
                            </div>
                            <div class="dropdown-menu" id="departmentDropdown">
                                @foreach($departments as $department)
                                    <div class="dropdown-item">
                                        <input type="checkbox" id="dept_{{ $department->id }}" 
                                               value="{{ $department->id }}" 
                                               class="department-checkbox"
                                               onchange="filterUsersByDepartments()">
                                        <label for="dept_{{ $department->id }}">{{ $department->name }}</label>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        <button type="button" class="btn btn-sm btn-primary mt-2" onclick="confirmDepartmentSelection()">
                            <i class="bi bi-check me-1"></i>Xác nhận phòng ban
                        </button>
                    </div>

                    {{-- Chọn người phụ trách --}}
                    <div class="mb-3">
                        <label class="form-label fw-bold text-dark">
                            <i class="bi bi-person me-1"></i>Chọn người phụ trách:
                        </label>
                        <div class="user-selection-area">
                            <div class="search-box mb-2">
                                <input type="text" id="userSearch" class="form-control" 
                                       placeholder="Tìm kiếm theo tên..." 
                                       onkeyup="filterUsers()">
                            </div>
                            <div class="user-list" id="userList">
                                <!-- Users will be loaded here -->
                            </div>
                        </div>
                    </div>

                    {{-- Hiển thị người đã chọn --}}
                    <div class="selected-users mb-3">
                        <label class="form-label fw-bold text-dark">
                            <i class="bi bi-check-circle me-1"></i>Người đã chọn:
                        </label>
                        <div id="selectedUsersDisplay" class="selected-users-display">
                            @if($task->assignedUsers && $task->assignedUsers->count() > 0)
                                @foreach($task->assignedUsers as $user)
                                    <span class="selected-user-badge" data-user-id="{{ $user->id }}">
                                        {{ $user->name }} ({{ $user->department->name }})
                                        <i class="bi bi-x" onclick="removeUser({{ $user->id }})"></i>
                                    </span>
                                @endforeach
                            @else
                                <span class="text-muted">Chưa chọn người phụ trách</span>
                            @endif
                        </div>
                    </div>

                    {{-- Hidden inputs for form submission --}}
                    <div id="assigneeInputs">
                        @if($task->assignedUsers && $task->assignedUsers->count() > 0)
                            @foreach($task->assignedUsers as $user)
                                <input type="hidden" name="assignee_ids[]" value="{{ $user->id }}">
                            @endforeach
                        @endif
                    </div>

                    @error('assignee_ids')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Deadline --}}
                <div class="form-group">
                    <label for="deadline" class="form-label">
                        <i class="bi bi-calendar-event me-1"></i>Deadline
                    </label>
                    <input type="datetime-local" name="deadline" id="deadline" 
                           class="form-control @error('deadline') is-invalid @enderror"
                           value="{{ old('deadline', $task->deadline ? $task->deadline->format('Y-m-d\TH:i') : '') }}"
                           placeholder="dd/mm/yyyy --:--"
                           min="{{ now()->format('Y-m-d\TH:i') }}">
                    <!-- <div class="form-text text-info">
                        <i class="fas fa-info-circle me-1"></i>
                        Deadline phải là thời gian trong tương lai
                    </div> -->
                    @error('deadline')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Độ ưu tiên --}}
                <div class="form-group">
                    <label class="form-label">
                        <i class="bi bi-flag me-1"></i>Độ ưu tiên
                    </label>
                    <div class="priority-buttons">
                        <input type="radio" name="priority" value="low" id="priority_low" 
                               {{ old('priority', $task->priority) == 'low' ? 'checked' : '' }} class="d-none">
                        <label for="priority_low" class="priority-btn priority-low">
                            <i class="bi bi-flag me-1"></i>Thấp
                        </label>

                        <input type="radio" name="priority" value="medium" id="priority_medium" 
                               {{ old('priority', $task->priority) == 'medium' ? 'checked' : '' }} class="d-none">
                        <label for="priority_medium" class="priority-btn priority-medium">
                            <i class="bi bi-flag me-1"></i>Trung bình
                        </label>

                        <input type="radio" name="priority" value="high" id="priority_high" 
                               {{ old('priority', $task->priority) == 'high' ? 'checked' : '' }} class="d-none">
                        <label for="priority_high" class="priority-btn priority-high">
                            <i class="bi bi-flag me-1"></i>Cao
                        </label>
                    </div>
                    @error('priority')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Công việc đa phòng ban --}}
                <div class="form-group">
                    <div class="form-check">
                        <input
                            class="form-check-input"
                            type="checkbox"
                            name="is_multi_department"
                            id="isMultiDepartment"
                            value="1"
                            {{ old('is_multi_department', $task->is_multi_department) ? 'checked' : '' }}
                        >
                        <label class="form-check-label fw-bold text-dark" for="isMultiDepartment">
                            <i class="bi bi-diagram-3 me-2"></i>Công việc đa phòng ban
                        </label>
                        <div class="form-text text-info">
                            <i class="fas fa-info-circle me-1"></i>
                            Đánh dấu công việc này cần sự hợp tác giữa nhiều phòng ban
                        </div>
                    </div>
                </div>

                {{-- Lặp lại công việc --}}
                <div class="form-group">
                    <div class="form-check">
                        <input
                            class="form-check-input"
                            type="checkbox"
                            name="is_recurring"
                            id="isRecurring"
                            value="1"
                            {{ old('is_recurring', $task->is_recurring) ? 'checked' : '' }}
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
                    <div id="recurringInfo" class="mt-3 p-3 bg-light rounded" style="display: {{ old('is_recurring', $task->is_recurring) ? 'block' : 'none' }};">
                        <div class="row">
                            <div class="col-md-6">
                                <label class="form-label fw-bold text-dark">Ngày bắt đầu:</label>
                                <input
                                    type="date"
                                    name="recurring_start_date"
                                    id="recurringStartDate"
                                    class="form-control border-2"
                                    value="{{ old('recurring_start_date', $task->recurring_start_date ? $task->recurring_start_date->format('Y-m-d') : now()->format('Y-m-d')) }}"
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
                                        value="{{ old('recurring_days', $task->recurring_days) }}"
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
                                <span id="recurringPreview">
                                    @if($task->is_recurring && $task->recurring_days)
                                        Công việc sẽ lặp lại mỗi {{ $task->recurring_days }} ngày
                                    @else
                                        Ví dụ: Nếu deadline là 22/8, công việc sẽ lặp lại mỗi 3 ngày
                                    @endif
                                </span>
                            </small>
                        </div>
                    </div>
                </div>

                {{-- Trạng thái --}}
                <div class="form-group">
                    <label for="status" class="form-label">
                        <i class="bi bi-check2-circle me-1"></i>Trạng thái <span class="text-danger">*</span>
                    </label>
                    <select name="status" id="status" class="form-select @error('status') is-invalid @enderror" required>
                        <option value="in_progress" {{ old('status', $task->status) == 'in_progress' ? 'selected' : '' }}>Đang làm</option>
                        <option value="completed" {{ old('status', $task->status) == 'completed' ? 'selected' : '' }}>Chờ duyệt</option>
                        <option value="rejected" {{ old('status', $task->status) == 'rejected' ? 'selected' : '' }}>Từ chối</option>
                        <option value="overdue" {{ old('status', $task->status) == 'overdue' ? 'selected' : '' }}>Trễ hạn</option>
                        <option value="finished" {{ old('status', $task->status) == 'finished' ? 'selected' : '' }}>Kết thúc</option>
                    </select>
                    @error('status')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Rejection Reason (Conditional) --}}
                <div class="form-group" id="rejection_reason_group" style="display: none;">
                    <label for="rejection_reason" class="form-label">
                        <i class="bi bi-exclamation-triangle me-1"></i>Lý do từ chối <span class="text-danger">*</span>
                    </label>
                    <textarea name="rejection_reason" id="rejection_reason" rows="3" 
                              class="form-control @error('rejection_reason') is-invalid @enderror" 
                              placeholder="Nhập lý do từ chối công việc...">{{ old('rejection_reason', $task->rejection_reason) }}</textarea>
                    <div id="rejectionReasonError" class="text-danger mt-1" style="display: none;">
                        <i class="bi bi-exclamation-triangle me-1"></i>
                        Không được phép nhập từ dài hơn 45 ký tự!
                    </div>
                    @error('rejection_reason')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Submit Button --}}
                <div class="text-center mt-4">
                    <button type="submit" class="btn btn-submit" id="submitBtn">
                        <i class="bi bi-check-circle me-2"></i>
                        Cập nhật công việc
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Priority button selection
    const priorityBtns = document.querySelectorAll('.priority-btn');
    priorityBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            // Remove active class from all buttons
            priorityBtns.forEach(b => b.classList.remove('active'));
            // Add active class to clicked button
            this.classList.add('active');
        });
    });

    // File drop zone functionality
    const dropZone = document.querySelector('.file-drop-zone');
    const fileInput = document.getElementById('files');

    dropZone.addEventListener('dragover', function(e) {
        e.preventDefault();
        this.classList.add('dragover');
    });

    dropZone.addEventListener('dragleave', function(e) {
        e.preventDefault();
        this.classList.remove('dragover');
    });

    dropZone.addEventListener('drop', function(e) {
        e.preventDefault();
        this.classList.remove('dragover');
        const files = e.dataTransfer.files;
        fileInput.files = files;
        handleFileSelect(fileInput);
    });

    // Deadline input
    const deadlineInput = document.querySelector('input[name="deadline"]');
    if (deadlineInput) {
        deadlineInput.addEventListener('click', function() {
            this.showPicker && this.showPicker();
        });
    }

    const statusSelect = document.getElementById('status');
    const rejectionReasonGroup = document.getElementById('rejection_reason_group');
    const descriptionTextarea = document.getElementById('description');
    const rejectionReasonTextarea = document.getElementById('rejection_reason');
    const descriptionError = document.getElementById('descriptionError');
    const rejectionReasonError = document.getElementById('rejectionReasonError');
    const submitBtn = document.getElementById('submitBtn');
    const form = document.querySelector('form');

    function checkWordLength(text) {
        const words = text.trim().split(/\s+/);
        return words.every(word => word.length <= 45);
    }

    function validateTextarea(textarea, errorElement) {
        const text = textarea.value;
        const isValid = checkWordLength(text);
        
        if (!isValid) {
            errorElement.style.display = 'block';
            submitBtn.disabled = true;
        } else {
            errorElement.style.display = 'none';
            submitBtn.disabled = false;
        }
    }

    // Department and User Selection JavaScript
    let allUsers = [];
    let selectedUsers = new Set();

    // Initialize with existing assigned users
    @if($task->assignedUsers && $task->assignedUsers->count() > 0)
        @foreach($task->assignedUsers as $user)
            selectedUsers.add({{ $user->id }});
        @endforeach
    @endif

    function toggleDepartmentDropdown() {
        const dropdown = document.getElementById('departmentDropdown');
        dropdown.classList.toggle('show');
    }

    function confirmDepartmentSelection() {
        const selectedDepartments = Array.from(document.querySelectorAll('.department-checkbox:checked'))
            .map(cb => cb.value);
        
        if (selectedDepartments.length === 0) {
            alert('Vui lòng chọn ít nhất một phòng ban');
            return;
        }

        // Update display text
        const textElement = document.getElementById('selectedDepartmentsText');
        const departmentNames = Array.from(document.querySelectorAll('.department-checkbox:checked'))
            .map(cb => cb.nextElementSibling.textContent);
        textElement.textContent = departmentNames.join(', ');

        // Load users from selected departments
        loadUsersByDepartments(selectedDepartments);
        
        // Close dropdown
        document.getElementById('departmentDropdown').classList.remove('show');
    }

    function loadUsersByDepartments(departmentIds) {
        // Filter users by selected departments
        const filteredUsers = allUsers.filter(user => 
            departmentIds.includes(user.department_id.toString())
        );
        
        displayUsers(filteredUsers);
    }

    function displayUsers(users) {
        const userList = document.getElementById('userList');
        userList.innerHTML = '';

        users.forEach(user => {
            const userItem = document.createElement('div');
            userItem.className = 'user-item';
            userItem.dataset.userId = user.id;
            userItem.innerHTML = `
                <span>${user.name} (${user.department_name})</span>
                <input type="checkbox" ${selectedUsers.has(user.id) ? 'checked' : ''} 
                       onchange="toggleUser(${user.id}, '${user.name}', '${user.department_name}')">
            `;
            userList.appendChild(userItem);
        });
    }

    function toggleUser(userId, userName, departmentName) {
        if (selectedUsers.has(userId)) {
            selectedUsers.delete(userId);
            removeUserFromDisplay(userId);
        } else {
            selectedUsers.add(userId);
            addUserToDisplay(userId, userName, departmentName);
        }
        updateAssigneeInputs();
    }

    function addUserToDisplay(userId, userName, departmentName) {
        const display = document.getElementById('selectedUsersDisplay');
        const badge = document.createElement('span');
        badge.className = 'selected-user-badge';
        badge.dataset.userId = userId;
        badge.innerHTML = `${userName} (${departmentName}) <i class="bi bi-x" onclick="removeUser(${userId})"></i>`;
        display.appendChild(badge);
    }

    function removeUserFromDisplay(userId) {
        const badge = document.querySelector(`.selected-user-badge[data-user-id="${userId}"]`);
        if (badge) {
            badge.remove();
        }
    }

    function removeUser(userId) {
        selectedUsers.delete(userId);
        removeUserFromDisplay(userId);
        
        // Uncheck checkbox
        const checkbox = document.querySelector(`.user-item[data-user-id="${userId}"] input[type="checkbox"]`);
        if (checkbox) {
            checkbox.checked = false;
        }
        
        updateAssigneeInputs();
    }

    function updateAssigneeInputs() {
        const container = document.getElementById('assigneeInputs');
        container.innerHTML = '';
        
        selectedUsers.forEach(userId => {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'assignee_ids[]';
            input.value = userId;
            container.appendChild(input);
        });
    }

    function filterUsers() {
        const searchTerm = document.getElementById('userSearch').value.toLowerCase();
        const userItems = document.querySelectorAll('.user-item');
        
        userItems.forEach(item => {
            const userName = item.querySelector('span').textContent.toLowerCase();
            if (userName.includes(searchTerm)) {
                item.style.display = 'flex';
            } else {
                item.style.display = 'none';
            }
        });
    }

    // Load all users on page load
    @if(isset($users))
        allUsers = @json($users->map(function($user) {
            return [
                'id' => $user->id,
                'name' => $user->name,
                'department_id' => $user->department_id,
                'department_name' => $user->department ? $user->department->name : 'N/A'
            ];
        }));
    @endif

    // Initialize user display if there are existing assignees
    @if($task->assignedUsers && $task->assignedUsers->count() > 0)
        const selectedDepartments = new Set();
        @foreach($task->assignedUsers as $user)
            selectedDepartments.add({{ $user->department_id }});
        @endforeach
        
        // Check department checkboxes
        selectedDepartments.forEach(deptId => {
            const checkbox = document.getElementById(`dept_${deptId}`);
            if (checkbox) checkbox.checked = true;
        });
        
        // Update display text
        const deptNames = Array.from(selectedDepartments).map(id => {
            const checkbox = document.getElementById(`dept_${id}`);
            return checkbox ? checkbox.nextElementSibling.textContent : '';
        }).filter(name => name);
        
        document.getElementById('selectedDepartmentsText').textContent = deptNames.join(', ');
        
        // Load users from selected departments
        loadUsersByDepartments(Array.from(selectedDepartments));
    @endif
        if (!isValid) {
            errorElement.style.display = 'block';
            return false;
        } else {
            errorElement.style.display = 'none';
            return true;
        }
    }

    function updateSubmitButton() {
        const descriptionValid = validateTextarea(descriptionTextarea, descriptionError);
        const rejectionReasonValid = rejectionReasonTextarea.style.display !== 'none' ? 
            validateTextarea(rejectionReasonTextarea, rejectionReasonError) : true;
        
        if (!descriptionValid || !rejectionReasonValid) {
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="bi bi-exclamation-triangle me-2"></i>Từ quá dài (>45 ký tự)';
            submitBtn.classList.remove('btn-submit');
            submitBtn.classList.add('btn-danger');
        } else {
            submitBtn.disabled = false;
            submitBtn.innerHTML = '<i class="bi bi-check-circle me-2"></i>Cập nhật công việc';
            submitBtn.classList.remove('btn-danger');
            submitBtn.classList.add('btn-submit');
        }
    }

    descriptionTextarea.addEventListener('input', updateSubmitButton);
    descriptionTextarea.addEventListener('paste', updateSubmitButton);
    rejectionReasonTextarea.addEventListener('input', updateSubmitButton);
    rejectionReasonTextarea.addEventListener('paste', updateSubmitButton);

    // Show/hide rejection reason based on status
    if (statusSelect.value === 'rejected') {
        rejectionReasonGroup.style.display = 'block';
    }

    statusSelect.addEventListener('change', function() {
        if (this.value === 'rejected') {
            rejectionReasonGroup.style.display = 'block';
            // Make rejection reason required when status is rejected
            document.getElementById('rejection_reason').required = true;
        } else {
            rejectionReasonGroup.style.display = 'none';
            // Remove required when status is not rejected
            document.getElementById('rejection_reason').required = false;
            // Clear rejection reason when status is not rejected
            document.getElementById('rejection_reason').value = '';
        }
        updateSubmitButton();
    });

    form.addEventListener('submit', function(e) {
        const descriptionValid = checkWordLength(descriptionTextarea.value);
        const rejectionReasonValid = rejectionReasonTextarea.style.display !== 'none' ? 
            checkWordLength(rejectionReasonTextarea.value) : true;
        
        if (!descriptionValid || !rejectionReasonValid) {
            e.preventDefault();
            alert('Không được phép nhập từ dài hơn 45 ký tự!');
            return false;
        }
    });

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
        const deadlineInput = document.querySelector('input[name="deadline"]');
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

    // Cập nhật thông tin lặp lại khi deadline thay đổi
    const deadlineInput = document.querySelector('input[name="deadline"]');
    if (deadlineInput) {
        deadlineInput.addEventListener('change', function() {
            if (isRecurringCheckbox && isRecurringCheckbox.checked) {
                updateRecurringInfo();
            }
        });
    }

    // Khởi tạo thông tin lặp lại nếu đã có sẵn
    if (isRecurringCheckbox && isRecurringCheckbox.checked) {
        updateRecurringInfo();
    }
});

function handleFileSelect(input) {
    const files = input.files;
    if (files.length > 0) {
        const dropZone = document.querySelector('.file-drop-zone');
        dropZone.innerHTML = `
            <i class="bi bi-check-circle text-success display-6 mb-2"></i>
            <p class="mb-0 fw-semibold text-success">Đã chọn ${files.length} file</p>
            <small class="text-muted">Click để thay đổi</small>
        `;
    }
}
</script>
@endsection
