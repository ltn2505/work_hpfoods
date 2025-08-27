@extends('layouts.edit')
@section('title','Cập nhật công việc')

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

/* Custom dropdown styling */
.custom-dropdown {
    position: relative;
    width: 100%;
}

/* Inactive user styling */
.inactive-user {
    opacity: 0.6;
    background-color: #f8f9fa;
    border-left: 3px solid #6c757d;
    pointer-events: none;
}

.inactive-user .form-check-input:disabled {
    opacity: 0.4;
    cursor: not-allowed !important;
    pointer-events: none;
}

.inactive-user .form-check-label {
    color: #6c757d !important;
    cursor: not-allowed !important;
    pointer-events: none;
}

.inactive-user .form-check {
    pointer-events: none;
}
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
}

.dropdown-toggle:hover {
    border-color: #558EC1;
    box-shadow: 0 0 0 0.2rem rgba(85, 142, 193, 0.25);
}

.dropdown-toggle.active {
    border-color: #558EC1;
    box-shadow: 0 0 0 0.2rem rgba(85, 142, 193, 0.25);
}

.dropdown-toggle.active i {
    transform: rotate(180deg);
}

.dropdown-toggle i {
    transition: transform 0.3s ease;
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
    border-bottom: 1px solid #f0f0f0;
    transition: background-color 0.2s ease;
}

.dropdown-item:last-child {
    border-bottom: none;
}

.dropdown-item:hover {
    background-color: #f8f9fa;
}

.dropdown-item .form-check {
    margin: 0;
    width: 100%;
}

.dropdown-item .form-check-input {
    margin-right: 8px;
}

.dropdown-item .form-check-label {
    font-size: 0.9rem;
    cursor: pointer;
    display: flex;
    align-items: center;
    gap: 8px;
    width: 100%;
}

.dropdown-item .badge {
    font-size: 0.7rem;
    padding: 2px 6px;
}

/* Submit button */
.btn-submit {
    background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
    border: none;
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

/* Đảm bảo menu dropdown luôn nằm trên các input khác */
.custom-dropdown .dropdown-menu {
    z-index: 2000 !important;  /* cao hơn deadline input */
    position: absolute;
}

/* Nếu từng chỉnh datetime-local lên z-index cao, hãy reset lại */
input[type="datetime-local"] {
    z-index: auto !important;   /* hoặc 1, miễn thấp hơn 2000 */
    position: relative !important;
}


input[type="datetime-local"]::-webkit-inner-spin-button,
input[type="datetime-local"]::-webkit-outer-spin-button {
    -webkit-appearance: none;
    margin: 0;
}

/* Ensure dropdown doesn't overlap datetime input */
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

/* Higher z-index for datetime input container */
.form-group:has(input[type="datetime-local"]) {
    position: relative;
    z-index: 1001;
}

/* Ensure datetime input is always on top */
.form-group:has(input[type="datetime-local"]) input[type="datetime-local"] {
    z-index: 1002 !important;
}

/* Ensure dropdowns are clickable */
.dropdown-toggle {
    z-index: 1003 !important;
    position: relative;
}

.dropdown-menu {
    z-index: 1004 !important;
}

/* Fix for disabled dropdowns */
.dropdown-toggle:not([disabled]) {
    cursor: pointer !important;
    pointer-events: auto !important;
}

.dropdown-toggle[disabled] {
    cursor: not-allowed !important;
    opacity: 0.6;
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
                    Cập nhật công việc
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

                {{-- Title --}}
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

                {{-- Description --}}
                <div class="form-group">
                    <label for="description" class="form-label">
                        <i class="bi bi-text-paragraph me-1"></i>Mô tả
                    </label>
                    <textarea name="description" id="description" rows="4" class="form-control @error('description') is-invalid @enderror"
                              placeholder="Mô tả chi tiết công việc..." maxlength="1000">{{ old('description', $task->description) }}</textarea>
                    <div class="d-flex justify-content-between align-items-center mt-1">
                        <small class="text-muted">Tối đa 1000 ký tự</small>
                        <small class="text-muted" id="descriptionCounter">0/1000</small>
                    </div>
                    @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- File Upload --}}
                <div class="form-group">
                    <label class="form-label">
                        <i class="bi bi-paperclip me-1"></i>File đính kèm
                    </label>
                    <div class="file-drop-zone" onclick="document.getElementById('files').click()">
                        <i class="bi bi-cloud-upload display-4 text-muted mb-3"></i>
                        <p class="mb-2 fw-semibold">Kéo thả file vào đây hoặc click để chọn</p>
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

                {{-- Recurring Task --}}
                <div class="form-group">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="is_recurring" id="is_recurring" value="1" 
                               {{ old('is_recurring', $task->is_recurring) ? 'checked' : '' }}>
                        <label class="form-check-label" for="is_recurring">
                            <i class="bi bi-arrow-repeat me-1"></i>Lặp lại công việc
                        </label>
                    </div>
                    <small class="text-muted">
                        <i class="bi bi-info-circle me-1"></i>
                        Công việc sẽ được tự động tạo lại với deadline mới mỗi khi hoàn thành
                    </small>
                    @if($task->is_recurring)
                        <div class="alert alert-info mt-2">
                            <i class="bi bi-info-circle me-1"></i>
                            <strong>Công việc hiện tại:</strong> Lặp lại mỗi {{ $task->recurring_days }} ngày
                            @if($task->recurring_start_date)
                                <br><small>Bắt đầu từ: {{ $task->recurring_start_date->format('d/m/Y') }}</small>
                            @endif
                        </div>
                    @endif
                </div>
                {{-- Multi-Department Assignment --}}
                <div class="form-group">
                    <label class="form-label">
                        <i class="bi bi-building me-1"></i>Phòng ban
                    </label>
                    <div class="form-check mb-2">
                        <input class="form-check-input" type="checkbox" name="is_multi_department" id="is_multi_department" value="1" 
                               {{ old('is_multi_department', $task->is_multi_department) ? 'checked' : '' }}>
                        <label class="form-check-label" for="is_multi_department">
                            <i class="bi bi-diagram-3 me-1"></i>Giao việc cho nhiều phòng ban
                        </label>
                    </div>
                    
                    {{-- Single Department --}}
                    <div id="single_department_section" class="{{ old('is_multi_department', $task->is_multi_department) ? 'd-none' : '' }}">
                        <select name="department_id" id="department_id" class="form-select @error('department_id') is-invalid @enderror">
                            <option value="">Chọn phòng ban</option>
                            @foreach($departments as $department)
                                <option value="{{ $department->id }}" {{ old('department_id', $task->department_id) == $department->id ? 'selected' : '' }}>
                                    {{ $department->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    
                    {{-- Multi-Department --}}
                    <div id="multi_department_section" class="{{ old('is_multi_department', $task->is_multi_department) ? '' : 'd-none' }}">
                        <div class="custom-dropdown">
                            <div class="dropdown-toggle" id="department_dropdown_toggle">
                                <span class="selected-text">Chọn phòng ban...</span>
                                <i class="bi bi-chevron-down"></i>
                            </div>
                            <div class="dropdown-menu" id="department_dropdown_menu">
                                @foreach($departments as $department)
                                    <div class="dropdown-item">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="department_ids[]" 
                                                   value="{{ $department->id }}" id="dept_{{ $department->id }}"
                                                   {{ in_array($department->id, old('department_ids', $task->departments->pluck('id')->toArray())) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="dept_{{ $department->id }}">
                                                {{ $department->name }}
                                            </label>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        </div>
                    @error('department_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    @error('department_ids')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    </div>

                {{-- Multi-User Assignment --}}
                <div class="form-group">
                    <label class="form-label">
                        <i class="bi bi-people me-1"></i>Người phụ trách
                        </label>
                    <div class="form-check mb-2">
                        <input class="form-check-input" type="checkbox" name="is_multi_user" id="is_multi_user" value="1" 
                               {{ old('is_multi_user', $task->assignees->count() > 0) ? 'checked' : '' }}>
                        <label class="form-check-label" for="is_multi_user">
                            <i class="bi bi-people-fill me-1"></i>Giao việc cho nhiều người
                        </label>
                            </div>
                    
                    {{-- Single User --}}
                    <div id="single_user_section" class="{{ old('is_multi_user', $task->assignees->count() > 0) ? 'd-none' : '' }}">
                        <select name="assignee_id" id="assignee_id" class="form-select @error('assignee_id') is-invalid @enderror">
                            <option value="">Chọn người phụ trách</option>
                            @foreach($users as $user)
                                @if($user)
                                    <option value="{{ $user->id }}" {{ old('assignee_id', $task->assignee_id) == $user->id ? 'selected' : '' }}>
                                        {{ $user->name ?? 'Không có tên' }} @if($user->department) ({{ $user->department->name }}) @endif
                                    </option>
                                @endif
                            @endforeach
                        </select>
                    </div>

                    {{-- Multi-User --}}
                    <div id="multi_user_section" class="{{ old('is_multi_user', $task->assignees->count() > 0) ? '' : 'd-none' }}">
                        <div class="custom-dropdown">
                            <div class="dropdown-toggle" id="user_dropdown_toggle">
                                <span class="selected-text">Chọn người phụ trách...</span>
                                <i class="bi bi-chevron-down"></i>
                            </div>
                            <div class="dropdown-menu" id="user_dropdown_menu">
                                <div id="user_list_container">
                                    @foreach($users as $user)
                                        @if($user)
                                            @php
                                                $isCurrentAssignee = in_array($user->id, $task->assignees->pluck('id')->toArray());
                                                $isManager = $user->role === 'manager';
                                                $isCurrentManager = $isCurrentAssignee && $isManager;
                                                // Debug info
                                                // echo "User: {$user->name}, Role: {$user->role}, Current: " . ($isCurrentAssignee ? 'Yes' : 'No') . ", Manager: " . ($isManager ? 'Yes' : 'No') . ", CurrentManager: " . ($isCurrentManager ? 'Yes' : 'No') . "<br>";
                                            @endphp
                                            <div class="dropdown-item user-item {{ $isCurrentManager && auth()->user()->isManager() ? 'inactive-user' : '' }}" 
                                                 data-user-id="{{ $user->id }}" 
                                                 data-department-id="{{ $user->department_id ?? '' }}"
                                                 data-role="{{ $user->role }}">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="assignee_ids[]" 
                                                           value="{{ $user->id }}" id="user_{{ $user->id }}"
                                                           {{ in_array($user->id, old('assignee_ids', $task->assignees->pluck('id')->toArray())) ? 'checked' : '' }}
                                                           {{ $isCurrentManager && auth()->user()->isManager() ? 'disabled' : '' }}>
                                                    <label class="form-check-label {{ $isCurrentManager && auth()->user()->isManager() ? 'text-muted' : '' }}" for="user_{{ $user->id }}">
                                                        {{ $user->name ?? 'Không có tên' }} 
                                                        @if($user->department) 
                                                            <span class="badge bg-secondary">{{ $user->department->name }}</span>
                                                        @endif
                                                        @if($isManager)
                                                            <span class="badge bg-warning">Manager</span>
                                                        @endif
                                                        @if($isCurrentManager)
                                                            <span class="badge bg-info">Hiện tại</span>
                                                        @endif
                                                    </label>
                                                </div>
                                            </div>
                                        @endif
                                    @endforeach
                        </div>
                        </div>
                    </div>
                    </div>
                    @error('assignee_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    @error('assignee_ids')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    
                    <div class="form-text">
                        <i class="bi bi-info-circle me-1"></i>
                        <strong>Lưu ý:</strong> 
                        @if(auth()->user()->isAdmin())
                            Bạn có thể thay đổi tất cả thông tin và assignees (bao gồm cả Managers).
                        @elseif(auth()->user()->isManager())
                            Những Manager hiện tại sẽ được giữ nguyên và không thể thay đổi. 
                            Bạn chỉ có thể thêm/bớt Employees.
                        @else
                            Bạn chỉ có thể xem thông tin, không thể thay đổi assignees.
                        @endif
                    </div>
                </div>

                {{-- Task Followers --}}
                <div class="form-group">
                    <label class="form-label">
                        <i class="bi bi-eye me-1"></i>Task Followers
                    </label>
                    <div class="form-text text-info mb-2">
                        <i class="bi bi-info-circle me-1"></i>
                        Những người này sẽ theo dõi tiến trình công việc và có thể tham gia thảo luận
                    </div>
                    
                    <div class="custom-dropdown">
                        <div class="dropdown-toggle" id="follower_dropdown_toggle">
                            <span class="selected-text">Chọn Task Followers...</span>
                            <i class="bi bi-chevron-down"></i>
                        </div>
                        <div class="dropdown-menu" id="follower_dropdown_menu">
                            @foreach($users as $user)
                                @if($user && ($user->role === 'manager' || $user->role === 'employee'))
                                    @php
                                        $isCurrentFollower = $task->followers->contains('id', $user->id);
                                        $isCurrentAssignee = $task->assignee_id === $user->id || 
                                                           $task->creator_id === $user->id ||
                                                           $task->assignees->contains('id', $user->id);
                                        $isManager = $user->role === 'manager';
                                        $canSelect = !$isCurrentAssignee && (!$isManager || auth()->user()->isAdmin());
                                    @endphp
                                    <div class="dropdown-item follower-item {{ !$canSelect ? 'inactive-user' : '' }}" 
                                         data-user-id="{{ $user->id }}" 
                                         data-department-id="{{ $user->department_id ?? '' }}"
                                         data-role="{{ $user->role }}">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="follower_ids[]" 
                                                   value="{{ $user->id }}" id="follower_{{ $user->id }}"
                                                   {{ $isCurrentFollower ? 'checked' : '' }}
                                                   {{ !$canSelect ? 'disabled' : '' }}>
                                            <label class="form-check-label {{ !$canSelect ? 'text-muted' : '' }}" for="follower_{{ $user->id }}" data-original-text="{{ $user->name ?? 'Không có tên' }} @if($user->department) <span class=&quot;badge bg-secondary&quot;>{{ $user->department->name }}</span> @endif @if($isManager) <span class=&quot;badge bg-warning&quot;>Manager</span> @endif @if($isCurrentFollower) <span class=&quot;badge bg-info&quot;>Đang theo dõi</span> @endif @if($isCurrentAssignee) <span class=&quot;badge bg-primary&quot;>Đã giao việc</span> @endif">
                                                {{ $user->name ?? 'Không có tên' }} 
                                                @if($user->department) 
                                                    <span class="badge bg-secondary">{{ $user->department->name }}</span>
                                                @endif
                                                @if($isManager)
                                                    <span class="badge bg-warning">Manager</span>
                                                @endif
                                                @if($isCurrentFollower)
                                                    <span class="badge bg-info">Đang theo dõi</span>
                                                @endif
                                                @if($isCurrentAssignee)
                                                    <span class="badge bg-primary">Đã giao việc</span>
                                                @endif
                                            </label>
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    </div>
                    
                    <div class="form-text text-warning">
                        <i class="bi bi-exclamation-triangle me-1"></i>
                        <strong>Lưu ý:</strong> 
                        @if(auth()->user()->isManager())
                            Bạn không thể thêm Manager khác làm Task Follower. Chỉ có thể thêm Employee.
                        @else
                            Có thể thêm cả Manager và Employee làm Task Follower.
                        @endif
                        Không thể thêm người đã được giao việc hoặc người tạo việc làm Task Follower.
                    </div>
                </div>

                {{-- Deadline --}}
                <div class="form-group">
                    <label for="deadline" class="form-label">
                        <i class="bi bi-calendar-event me-1"></i>Deadline
                    </label>
                    <input type="datetime-local" name="deadline" id="deadline"
                           class="form-control @error('deadline') is-invalid @enderror"
                           value="{{ old('deadline', $task->deadline ? $task->deadline->format('Y-m-d\TH:i') : '') }}"
                           placeholder="dd/mm/yyyy --:--">
                    @error('deadline')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Priority --}}
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

                {{-- Status --}}
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
                              placeholder="Nhập lý do từ chối công việc..." maxlength="500">{{ old('rejection_reason', $task->rejection_reason) }}</textarea>
                    <div class="d-flex justify-content-between align-items-center mt-1">
                        <small class="text-muted">Tối đa 500 ký tự</small>
                        <small class="text-muted" id="rejectionReasonCounter">0/500</small>
                    </div>
                    @error('rejection_reason')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Submit Button --}}
                <div class="text-center mt-4">
                    <button type="submit" class="btn btn-submit">
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
        if (files.length > 0) {
            fileInput.files = files;
            handleFileSelect(fileInput);
        }
    });

    // Status change handler
    const statusSelect = document.getElementById('status');
    const rejectionReasonGroup = document.getElementById('rejection_reason_group');

    // Show/hide rejection reason based on current status
    if (statusSelect.value === 'rejected') {
        rejectionReasonGroup.style.display = 'block';
    }

    statusSelect.addEventListener('change', function() {
        if (this.value === 'rejected') {
            rejectionReasonGroup.style.display = 'block';
            document.getElementById('rejection_reason').required = true;
        } else {
            rejectionReasonGroup.style.display = 'none';
            document.getElementById('rejection_reason').required = false;
            document.getElementById('rejection_reason').value = '';
        }
    });

    // Validation for long words
    validateTextarea('description', 'descriptionCounter', 1000);
    validateTextarea('rejection_reason', 'rejectionReasonCounter', 500);

    // Initialize dropdowns
    initDropdown('department_dropdown_toggle', 'department_dropdown_menu');
    initDropdown('user_dropdown_toggle', 'user_dropdown_menu');
    initDropdown('follower_dropdown_toggle', 'follower_dropdown_menu');
    
    // Set initial selected text
    updateSelectedText('department');
    updateSelectedText('user');
    updateSelectedText('follower');
    
    // Handle initial department change
    handleDepartmentChange();

    // Multi-user and multi-department toggle
    const multiUserCheckbox = document.getElementById('is_multi_user');
    const singleUserSection = document.getElementById('single_user_section');
    const multiUserSection = document.getElementById('multi_user_section');

    const multiDepartmentCheckbox = document.getElementById('is_multi_department');
    const singleDepartmentSection = document.getElementById('single_department_section');
    const multiDepartmentSection = document.getElementById('multi_department_section');

    // Multi-user toggle
    if (multiUserCheckbox) {
        multiUserCheckbox.addEventListener('change', function(e) {
            e.stopPropagation();
            
            if (this.checked) {
                singleUserSection.classList.add('d-none');
                multiUserSection.classList.remove('d-none');
                const singleUserSelect = document.getElementById('assignee_id');
                if (singleUserSelect) {
                    singleUserSelect.value = '';
                }
            } else {
                singleUserSection.classList.remove('d-none');
                multiUserSection.classList.add('d-none');
                const checkboxes = multiUserSection.querySelectorAll('input[type="checkbox"]');
                checkboxes.forEach(cb => cb.checked = false);
                updateSelectedText('user');
            }
        });
    }

    // Multi-department toggle
    if (multiDepartmentCheckbox) {
        multiDepartmentCheckbox.addEventListener('change', function(e) {
            e.stopPropagation();
            
            if (this.checked) {
                singleDepartmentSection.classList.add('d-none');
                multiDepartmentSection.classList.remove('d-none');
                const singleDeptSelect = document.getElementById('department_id');
                if (singleDeptSelect) {
                    singleDeptSelect.value = '';
                }
            } else {
                singleDepartmentSection.classList.remove('d-none');
                multiDepartmentSection.classList.add('d-none');
                const checkboxes = multiDepartmentSection.querySelectorAll('input[type="checkbox"]');
                checkboxes.forEach(cb => cb.checked = false);
                updateSelectedText('department');
            }
        });
    }
});

// Universal dropdown initialization function
function initDropdown(toggleId, menuId) {
    const toggle = document.getElementById(toggleId);
    const menu = document.getElementById(menuId);
    
    if (toggle && menu) {
        // Toggle dropdown on click
        toggle.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            // Close all other dropdowns first
            closeAllDropdowns();
            
            // Toggle current dropdown
            menu.classList.toggle('show');
        });
        
        // Handle checkbox changes
        const checkboxes = menu.querySelectorAll('input[type="checkbox"]');
        checkboxes.forEach(checkbox => {
            checkbox.addEventListener('change', function(e) {
                e.stopPropagation();
                
                // Update selected text based on dropdown type
                if (menuId === 'department_dropdown_menu') {
                    updateSelectedText('department');
                    handleDepartmentChange();
                } else if (menuId === 'user_dropdown_menu') {
                    updateSelectedText('user');
                    updateFollowerOptions();
                } else if (menuId === 'follower_dropdown_menu') {
                    updateSelectedText('follower');
                }
            });
            
            // Prevent dropdown from closing when clicking checkbox
            checkbox.addEventListener('click', function(e) {
                e.stopPropagation();
            });
        });
    }
}

// Close all dropdowns
function closeAllDropdowns() {
    const dropdowns = document.querySelectorAll('.dropdown-menu');
    dropdowns.forEach(dropdown => {
        dropdown.classList.remove('show');
    });
}

// Close dropdowns when clicking outside
document.addEventListener('click', function(e) {
    if (!e.target.closest('.custom-dropdown')) {
        closeAllDropdowns();
    }
});

// Department change handler
function handleDepartmentChange() {
    const selectedDepartments = getSelectedDepartments();
    const userItems = document.querySelectorAll('.user-item');
    
    userItems.forEach(item => {
        const userDepartmentId = item.dataset.departmentId;
        const userRole = item.dataset.role;
        const checkbox = item.querySelector('input[type="checkbox"]');
        
        const isInSelectedDepartment = selectedDepartments.includes(parseInt(userDepartmentId));
        
        if (isInSelectedDepartment) {
            checkbox.disabled = false;
            item.style.opacity = '1';
            
            // Disable other managers if current user is manager
            if (userRole === 'manager' && !isCurrentManager(item.dataset.userId)) {
                const currentUserRole = '{{ auth()->user()->role }}';
                if (currentUserRole === 'manager') {
                    checkbox.disabled = true;
                    item.style.opacity = '0.5';
                    if (checkbox.checked) {
                        checkbox.checked = false;
                    }
                }
            }
        } else {
            checkbox.disabled = true;
            item.style.opacity = '0.5';
            if (checkbox.checked) {
                checkbox.checked = false;
            }
        }
    });
    
    updateSelectedText('user');
    updateFollowerOptions();
}

// Update follower options based on selected users
function updateFollowerOptions() {
    const selectedUsers = getSelectedUsers();
    const followerItems = document.querySelectorAll('.follower-item');
    
    followerItems.forEach(item => {
        const userId = item.dataset.userId;
        const checkbox = item.querySelector('input[type="checkbox"]');
        
        if (selectedUsers.includes(parseInt(userId))) {
            checkbox.disabled = true;
            item.style.opacity = '0.5';
            if (checkbox.checked) {
                checkbox.checked = false;
            }
        } else {
            checkbox.disabled = false;
            item.style.opacity = '1';
        }
    });
    
    updateSelectedText('follower');
}

// Helper functions
function isCurrentManager(userId) {
    const currentUserId = '{{ auth()->id() }}';
    return userId === currentUserId;
}

function getSelectedDepartments() {
    const checkboxes = document.querySelectorAll('input[name="department_ids[]"]:checked');
    return Array.from(checkboxes).map(cb => parseInt(cb.value));
}

function getSelectedUsers() {
    const checkboxes = document.querySelectorAll('input[name="assignee_ids[]"]:checked');
    return Array.from(checkboxes).map(cb => parseInt(cb.value));
}

function updateSelectedText(type) {
    if (type === 'department') {
        const checkboxes = document.querySelectorAll('input[name="department_ids[]"]:checked');
        const selectedText = document.querySelector('#department_dropdown_toggle .selected-text');
        if (checkboxes.length === 0) {
            selectedText.textContent = 'Chọn phòng ban...';
        } else if (checkboxes.length === 1) {
            const label = checkboxes[0].closest('.dropdown-item').querySelector('.form-check-label').textContent.trim();
            selectedText.textContent = label;
        } else {
            selectedText.textContent = `Đã chọn ${checkboxes.length} phòng ban`;
        }
    } else if (type === 'user') {
        const checkboxes = document.querySelectorAll('input[name="assignee_ids[]"]:checked');
        const selectedText = document.querySelector('#user_dropdown_toggle .selected-text');
        if (checkboxes.length === 0) {
            selectedText.textContent = 'Chọn người phụ trách...';
        } else if (checkboxes.length === 1) {
            const label = checkboxes[0].closest('.dropdown-item').querySelector('.form-check-label').textContent.trim();
            selectedText.textContent = label;
        } else {
            selectedText.textContent = `Đã chọn ${checkboxes.length} người`;
        }
    } else if (type === 'follower') {
        const checkboxes = document.querySelectorAll('input[name="follower_ids[]"]:checked');
        const selectedText = document.querySelector('#follower_dropdown_toggle .selected-text');
        if (checkboxes.length === 0) {
            selectedText.textContent = 'Chọn Task Followers...';
        } else {
            selectedText.textContent = `Đã chọn ${checkboxes.length} người`;
        }
    }
}

// Function to validate textarea and prevent long words
function validateTextarea(textareaId, counterId, maxLength) {
    const textarea = document.getElementById(textareaId);
    const counter = document.getElementById(counterId);
    const submitBtn = document.querySelector('.btn-submit');
    
    if (textarea && counter) {
        // Update counter on input
        textarea.addEventListener('input', function() {
            const text = this.value;
            const words = text.split(/\s+/);
            let hasLongWord = false;
            
            // Check each word
            for (let word of words) {
                if (word.length > 45) {
                    hasLongWord = true;
                    break;
                }
            }
            
            // Update counter
            counter.textContent = `${text.length}/${maxLength}`;
            
            // Visual feedback for long words
            if (hasLongWord) {
                this.style.borderColor = '#dc3545';
                this.style.backgroundColor = '#fff5f5';
                if (submitBtn) {
                    submitBtn.disabled = true;
                    submitBtn.innerHTML = '<i class="bi bi-exclamation-triangle me-2"></i>Từ quá dài (>45 ký tự)';
                    submitBtn.classList.remove('btn-submit');
                    submitBtn.classList.add('btn-danger');
                }
            } else {
                this.style.borderColor = '';
                this.style.backgroundColor = '';
                if (submitBtn) {
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = '<i class="bi bi-check-circle me-2"></i>Cập nhật công việc';
                    submitBtn.classList.remove('btn-danger');
                    submitBtn.classList.add('btn-submit');
                }
            }
        });
        
        // Form validation
        textarea.closest('form').addEventListener('submit', function(e) {
            const text = textarea.value;
            const words = text.split(/\s+/);
            
            for (let word of words) {
                if (word.length > 45) {
                    e.preventDefault();
                    alert('Không được phép nhập từ dài hơn 45 ký tự!');
                    return false;
                }
            }
        });
        
        // Initialize counter
        counter.textContent = `${textarea.value.length}/${maxLength}`;
    }
}

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
