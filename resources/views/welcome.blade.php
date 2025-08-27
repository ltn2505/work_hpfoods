@extends('layouts.master')
@section('title','Quản lý chung')

@push('styles')
<style>
/* CSS cho drag & drop */
.department-card {
  transition: all 0.2s ease;
  cursor: default;
}

.department-card.dragging {
  opacity: 0.8;
  transform: rotate(2deg) scale(1.02);
  z-index: 1000;
  box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
  pointer-events: none;
}

.department-card.drag-over {
    border-top: 3px solid #558EC1;
    margin-top: 15px;
    transform: translateY(5px);
}

.drag-handle {
  font-size: 1.2rem;
  transition: all 0.3s ease;
}

.drag-handle:hover {
  opacity: 1 !important;
  transform: scale(1.1);
  color: #fff !important;
}

.drag-indicator {
  opacity: 0.5;
  transition: opacity 0.3s ease;
}

.department-card:hover .drag-indicator {
  opacity: 1;
}

/* Làm nổi bật drag handle */
.drag-handle {
  background: rgba(255, 255, 255, 0.1);
  padding: 6px 8px;
  border-radius: 6px;
  margin-right: 8px !important;
  cursor: grab;
  transition: all 0.2s ease;
  display: inline-flex;
  align-items: center;
  justify-content: center;
}

.drag-handle:hover {
  background: rgba(255, 255, 255, 0.25);
  transform: scale(1.05);
}

.drag-handle:active {
  cursor: grabbing;
  transform: scale(0.95);
}

/* CSS cho giao diện Manager/Employee - Fixed hover conflicts */
.table-hover tbody tr {
    transition: all 0.15s ease;
    position: relative;
    border-radius: 8px;
    margin: 2px 0;
}

.table-hover tbody tr:hover {
    background-color: #f8f9fa;
    transform: translateY(-1px);
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    border-radius: 8px;
    z-index: 10;
}

/* Prevent hover conflicts by adding visual separation */
.table tbody tr {
    border-bottom: 2px solid transparent;
    transition: all 0.15s ease;
    position: relative;
}

.table tbody tr:hover {
    border-bottom: 2px solid transparent;
    isolation: isolate;
}

/* Add proper spacing between rows */
.table tbody tr td {
    padding: 12px 8px;
    border-bottom: none;
}

.table tbody tr + tr td {
    border-top: 1px solid #f1f3f5;
}

.badge {
    font-size: 0.8rem;
    font-weight: 500;
}

.btn-group .btn {
    transition: all 0.2s ease;
}

.btn-group .btn:hover {
    transform: translateY(-1px);
    box-shadow: 0 2px 4px rgba(0,0,0,0.15);
}

/* Task card improvements */
.card {
    transition: all 0.2s ease;
    margin-bottom: 8px;
    border-radius: 12px;
    overflow: hidden;
}

.card:hover {
    box-shadow: 0 4px 16px rgba(0,0,0,0.1) !important;
    transform: translateY(-1px);
}

/* Additional fixes for hover conflicts */
.table-responsive {
    border-spacing: 0 4px;
    border-collapse: separate;
}

.table tbody tr {
    background: white;
    border-radius: 8px;
    box-shadow: 0 1px 3px rgba(0,0,0,0.05);
    margin-bottom: 4px;
}

.table tbody tr:not(:hover) + tr:hover {
    margin-top: 4px;
}

/* Precise hover zones */
.table tbody tr td:first-child {
    border-radius: 8px 0 0 8px;
}

.table tbody tr td:last-child {
    border-radius: 0 8px 8px 0;
}

.table th {
    font-weight: 600;
    color: #495057;
    border-bottom: 2px solid #dee2e6;
    background: #f8f9fa;
    padding: 12px 8px;
}

.table td {
    vertical-align: middle;
    border: none;
}

/* Prevent cascading hover effects */
.table tbody tr:hover ~ tr {
    transform: none;
}

/* Smooth hover with debounce protection */
.table tbody tr {
    transition: all 0.15s cubic-bezier(0.4, 0, 0.2, 1);
}

.table tbody tr:hover {
    transition-delay: 0.05s;
}

.table tbody tr:not(:hover) {
    transition-delay: 0s;
}

/* Responsive cho mobile */
@media (max-width: 768px) {
    .btn-group {
        flex-direction: column;
    }
    
    .btn-group .btn {
        border-radius: 0.375rem !important;
        margin-bottom: 0.25rem;
    }
    
    .table-responsive {
        font-size: 0.875rem;
    }
    
    /* Filter responsive */
    .filter-section .row {
        margin: 0;
    }
    
    .filter-section .col-md-6 {
        margin-bottom: 1rem;
    }
    
    .filter-section .btn-group {
        width: 100%;
    }
    
    .filter-section .btn-group .btn {
        flex: 1;
    }
}

/* Filter styling */
.filter-section {
    background: #f8f9fa;
    border-radius: 8px;
    padding: 1rem;
    margin-bottom: 1.5rem;
}

.filter-section small {
    font-weight: 600;
    color: #495057;
}

.filter-section .form-control-sm {
    border-radius: 6px;
    border: 1px solid #ced4da;
}

.filter-section .form-control-sm:focus {
    border-color: #558EC1;
    box-shadow: 0 0 0 0.2rem rgba(85, 142, 193, 0.25);
}

.filter-section .btn-sm {
    border-radius: 6px;
    font-weight: 500;
}
</style>
@endpush

@section('content')
<div class="mb-4">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h3 class="mb-0">
      <i class="bi bi-list-task me-2"></i>Quản lý chung
    </h3>
    
  </div>
</div>

<div class="row g-3 mb-3">
  <div class="col-md-2">
    <div class="card card-stat p-3 text-center">
      <h5 class="text-primary">{{ $stats['doing'] }}</h5>
      <p>Đang làm</p>
    </div>
  </div>

  <div class="col-md-2">
    <div class="card card-stat p-3 text-center">
      <h5 class="text-warning">{{ $stats['completed'] ?? 0 }}</h5>
      <p>Chờ duyệt</p>
    </div>
  </div>

  <div class="col-md-2">
    <div class="card card-stat p-3 text-center">
      <h5 class="text-danger">{{ $stats['overdue'] }}</h5>
      <p>Trễ hạn</p>
    </div>
  </div>
  <div class="col-md-2">
    <div class="card card-stat p-3 text-center">
      <h5 class="text-danger">{{ $stats['rejected'] ?? 0 }}</h5>
      <p>Từ chối</p>
    </div>
  </div>
  <div class="col-md-2">
    <div class="card card-stat p-3 text-center">
      <h5 class="text-success">{{ $stats['finished'] ?? 0 }}</h5>
      <p>Kết thúc</p>
    </div>
  </div>
</div>

<div class="mb-3 filter-section">
  {{-- Filter theo trạng thái --}}
  <div class="mb-2">
    <small class="text-muted mb-2 d-block"><i class="bi bi-funnel me-1"></i>Lọc theo trạng thái:</small>
    <form method="GET" action="{{ route('dashboard') }}" class="d-inline me-2">
      <input type="hidden" name="sort" value="{{ request('sort') }}">
      <input type="hidden" name="date_from" value="{{ request('date_from') }}">
      <input type="hidden" name="date_to" value="{{ request('date_to') }}">
      <button type="submit" class="btn btn-sm btn-outline-secondary{{ !request('status') && !request('statuses') ? ' active' : '' }}">Tất cả</button>
    </form>

    {{-- Multi-select statuses --}}
    <form method="GET" action="{{ route('dashboard') }}" class="d-inline">
      <input type="hidden" name="sort" value="{{ request('sort') }}">
      <input type="hidden" name="date_from" value="{{ request('date_from') }}">
      <input type="hidden" name="date_to" value="{{ request('date_to') }}">
      <div class="btn-group me-2" role="group" aria-label="Statuses">
        @php
          $selected = collect(request('statuses', []));
        @endphp
        <input type="checkbox" class="btn-check" id="st_doing" autocomplete="off" name="statuses[]" value="in_progress" {{ $selected->contains('in_progress') ? 'checked' : '' }}>
        <label class="btn btn-sm btn-outline-primary" for="st_doing" style="border-color: #558EC1; color: #558EC1;">Đang làm</label>

        <input type="checkbox" class="btn-check" id="st_completed" autocomplete="off" name="statuses[]" value="completed" {{ $selected->contains('completed') ? 'checked' : '' }}>
        <label class="btn btn-sm btn-outline-warning" for="st_completed">Chờ duyệt</label>

        <input type="checkbox" class="btn-check" id="st_rejected" autocomplete="off" name="statuses[]" value="rejected" {{ $selected->contains('rejected') ? 'checked' : '' }}>
        <label class="btn btn-sm btn-outline-danger" for="st_rejected">Từ chối</label>

        <input type="checkbox" class="btn-check" id="st_overdue" autocomplete="off" name="statuses[]" value="overdue" {{ $selected->contains('overdue') ? 'checked' : '' }}>
        <label class="btn btn-sm btn-outline-danger" for="st_overdue">Trễ hạn</label>

        <input type="checkbox" class="btn-check" id="st_finished" autocomplete="off" name="statuses[]" value="finished" {{ $selected->contains('finished') ? 'checked' : '' }}>
        <label class="btn btn-sm btn-outline-success" for="st_finished">Kết thúc</label>
      </div>
      <button type="submit" class="btn btn-sm btn-primary" style="background-color: #558EC1; border-color: #558EC1;">Áp dụng</button>
    </form>
  </div>

  {{-- Filter theo thời gian và phòng ban --}}
  <div class="row g-2">
    <div class="col-md-4">
      <small class="text-muted mb-2 d-block"><i class="bi bi-sort-numeric-down me-1"></i>Sắp xếp theo thời gian:</small>
      <div class="btn-group" role="group">
        <form method="GET" action="{{ route('dashboard') }}" class="d-inline">
          <input type="hidden" name="sort" value="newest">
          <input type="hidden" name="date_from" value="{{ request('date_from') }}">
          <input type="hidden" name="date_to" value="{{ request('date_to') }}">
          @if(request('statuses'))
            @foreach(request('statuses') as $status)
              <input type="hidden" name="statuses[]" value="{{ $status }}">
            @endforeach
          @endif
          @if(request('department_filter'))
            @foreach(request('department_filter') as $deptId)
              <input type="hidden" name="department_filter[]" value="{{ $deptId }}">
            @endforeach
          @endif
          <button type="submit" class="btn btn-sm btn-outline-info{{ request('sort')=='newest' ? ' active' : '' }}" style="border-color: #558EC1; color: #558EC1;">
            <i class="bi bi-sort-down me-1"></i>Mới nhất
          </button>
        </form>
        <form method="GET" action="{{ route('dashboard') }}" class="d-inline">
          <input type="hidden" name="sort" value="oldest">
          <input type="hidden" name="date_from" value="{{ request('date_from') }}">
          <input type="hidden" name="date_to" value="{{ request('date_to') }}">
          @if(request('statuses'))
            @foreach(request('statuses') as $status)
              <input type="hidden" name="statuses[]" value="{{ $status }}">
            @endforeach
          @endif
          @if(request('department_filter'))
            @foreach(request('department_filter') as $deptId)
              <input type="hidden" name="department_filter[]" value="{{ $deptId }}">
            @endforeach
          @endif
          <button type="submit" class="btn btn-sm btn-outline-info{{ request('sort')=='oldest' ? ' active' : '' }}" style="border-color: #558EC1; color: #558EC1;">
            <i class="bi bi-sort-up me-1"></i>Cũ nhất
          </button>
        </form>
      </div>
    </div>
    
    <div class="col-md-4">
      <small class="text-muted mb-2 d-block"><i class="bi bi-calendar-range me-1"></i>Chọn khoảng thời gian:</small>
      <form method="GET" action="{{ route('dashboard') }}" class="row g-2">
        <input type="hidden" name="sort" value="{{ request('sort') }}">
        @if(request('statuses'))
          @foreach(request('statuses') as $status)
            <input type="hidden" name="statuses[]" value="{{ $status }}">
          @endforeach
        @endif
        @if(request('department_filter'))
          @foreach(request('department_filter') as $deptId)
            <input type="hidden" name="department_filter[]" value="{{ $deptId }}">
          @endforeach
        @endif
        <div class="col-5">
          <input type="date" name="date_from" value="{{ request('date_from') }}" 
                 class="form-control form-control-sm" placeholder="Từ ngày">
        </div>
        <div class="col-5">
          <input type="date" name="date_to" value="{{ request('date_to') }}" 
                 class="form-control form-control-sm" placeholder="Đến ngày">
        </div>
        <div class="col-2">
          <button type="submit" class="btn btn-sm btn-primary w-100" style="background-color: #558EC1; border-color: #558EC1;">
            <i class="bi bi-search"></i>
          </button>
        </div>
      </form>
    </div>
    
    <div class="col-md-4">
      <small class="text-muted mb-2 d-block"><i class="bi bi-building me-1"></i>Filter theo phòng ban:</small>
      <div class="dropdown">
        <button class="btn btn-outline-secondary dropdown-toggle w-100 text-start" type="button" 
                data-bs-toggle="dropdown" aria-expanded="false">
          <i class="bi bi-building me-1"></i>
          @php
            $selectedDepts = request('department_filter', []);
            if (empty($selectedDepts)) {
              echo 'Tất cả phòng ban';
            } elseif (count($selectedDepts) == 1) {
              if (auth()->user()->isAdmin() && isset($departments)) {
                $dept = $departments->find($selectedDepts[0]);
                echo $dept ? $dept->name : 'Phòng ban';
              } elseif (auth()->user()->isManager() && isset($managerDepartment)) {
                echo $managerDepartment->name;
              }
            } else {
              echo count($selectedDepts) . ' phòng ban được chọn';
            }
          @endphp
        </button>
        <form method="GET" action="{{ route('dashboard') }}" class="dropdown-menu p-3" style="width: 300px;">
          <input type="hidden" name="sort" value="{{ request('sort') }}">
          <input type="hidden" name="date_from" value="{{ request('date_from') }}">
          <input type="hidden" name="date_to" value="{{ request('date_to') }}">
          @if(request('statuses'))
            @foreach(request('statuses') as $status)
              <input type="hidden" name="statuses[]" value="{{ $status }}">
            @endforeach
          @endif
          
          <div class="mb-2">
            <small class="text-muted fw-semibold">Chọn phòng ban:</small>
          </div>
          
          <div class="mb-3" style="max-height: 200px; overflow-y: auto;">
            @if(auth()->user()->isAdmin() && isset($departments))
              @foreach($departments as $dept)
                <div class="form-check">
                  <input class="form-check-input" type="checkbox" name="department_filter[]" 
                         value="{{ $dept->id }}" id="dept_{{ $dept->id }}"
                         {{ in_array($dept->id, $selectedDepts) ? 'checked' : '' }}>
                  <label class="form-check-label" for="dept_{{ $dept->id }}">
                    {{ $dept->name }}
                  </label>
                </div>
              @endforeach
            @elseif(auth()->user()->isManager() && isset($managerDepartment))
              <div class="form-check">
                <input class="form-check-input" type="checkbox" name="department_filter[]" 
                       value="{{ $managerDepartment->id }}" id="dept_{{ $managerDepartment->id }}"
                       {{ in_array($managerDepartment->id, $selectedDepts) ? 'checked' : '' }}>
                <label class="form-check-label" for="dept_{{ $managerDepartment->id }}">
                  {{ $managerDepartment->name }}
                </label>
              </div>
            @endif
          </div>
          
          <div class="d-flex gap-2">
            <button type="button" class="btn btn-outline-secondary btn-sm flex-fill" onclick="clearDepartmentFilter()">
              <i class="bi bi-x-circle me-1"></i>Xóa
            </button>
            <button type="submit" class="btn btn-primary btn-sm flex-fill">
              <i class="bi bi-search me-1"></i>Áp dụng
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>

  {{-- Nút xóa filter --}}
  @if(request('statuses') || request('sort') || request('date_from') || request('date_to') || request('department_filter'))
    <div class="mt-2">
      <a href="{{ route('dashboard') }}" class="btn btn-sm btn-outline-secondary">
        <i class="bi bi-x-circle me-1"></i>Xóa bộ lọc
      </a>
    </div>
  @endif
</div>

{{-- Gộp tất cả công việc (đa phòng ban + đơn phòng ban) --}}
@if(auth()->user()->isAdmin() || auth()->user()->isManager())
  <div class="card shadow-sm border-0 mb-4">
    <div class="card-header text-white d-flex align-items-center justify-content-between" style="background: linear-gradient(135deg, #558EC1 0%, #5DA444 100%);">
      <h5 class="mb-0 fw-bold text-white">
        <i class="bi bi-list-ul me-2"></i>
        📋 Tất cả công việc 
        <span class="badge bg-light text-dark ms-2">
          @php
            $totalTasks = 0;
            if(auth()->user()->isAdmin()) {
              $totalTasks += (isset($multiDepartmentTasks) ? $multiDepartmentTasks->count() : 0);
              if(isset($departments) && isset($departmentTasks)) {
                foreach($departments as $dept) {
                  $totalTasks += isset($departmentTasks[$dept->id]) ? $departmentTasks[$dept->id]->count() : 0;
                }
              }
            } else {
              $totalTasks += (isset($managerMultiDepartmentTasks) ? $managerMultiDepartmentTasks->count() : 0);
              if(isset($managerDepartmentTasks)) {
                $totalTasks += $managerDepartmentTasks->count();
              }
            }
          @endphp
          {{ $totalTasks }} công việc
        </span>
      </h5>
      <div class="d-flex align-items-center">
        <span class="badge bg-info text-white me-2">
          <i class="bi bi-gear me-1"></i>
          Quản lý thống nhất
        </span>
      </div>
    </div>
    
    {{-- Filter đơn giản --}}
    <div class="card-body border-bottom">
      <div class="row g-3">
    </div>
    
    <div class="card-body p-0">
      @php
        // Gộp tất cả tasks
        $allTasks = collect();
        
        if(auth()->user()->isAdmin()) {
          // Admin: gộp multi-department + department tasks
          if(isset($multiDepartmentTasks)) {
            $allTasks = $allTasks->merge($multiDepartmentTasks);
          }
          if(isset($departments) && isset($departmentTasks)) {
            foreach($departments as $dept) {
              if(isset($departmentTasks[$dept->id])) {
                $allTasks = $allTasks->merge($departmentTasks[$dept->id]);
              }
            }
          }
        } else {
          // Manager: gộp multi-department + department tasks của manager
          if(isset($managerMultiDepartmentTasks)) {
            $allTasks = $allTasks->merge($managerMultiDepartmentTasks);
          }
          if(isset($managerDepartmentTasks)) {
            $allTasks = $allTasks->merge($managerDepartmentTasks);
          }
        }
        
        // Sắp xếp theo thời gian
        if(request('sort') == 'oldest') {
          $allTasks = $allTasks->sortBy('created_at');
        } else {
          $allTasks = $allTasks->sortByDesc('created_at');
        }
        
        // Filter theo trạng thái nếu có (hỗ trợ nhiều trạng thái)
        if(request('statuses') && is_array(request('statuses')) && count(request('statuses')) > 0) {
          $allTasks = $allTasks->filter(function($task) {
            return in_array($task->status, request('statuses'));
          });
        }
        
        // Filter theo phòng ban nếu có (hỗ trợ nhiều phòng ban)
        if(request('department_filter') && is_array(request('department_filter')) && count(request('department_filter')) > 0) {
          $allTasks = $allTasks->filter(function($task) {
            if($task->is_multi_department) {
              // Kiểm tra xem task có assignee thuộc phòng ban được chọn không
              return $task->assignees->whereIn('department_id', request('department_filter'))->count() > 0;
            } else {
              // Kiểm tra assignee đầu tiên
                              return $task->assignees->first() && in_array($task->assignees->first()->department_id, request('department_filter'));
            }
          });
        }
      @endphp
      
      @if($allTasks->count() > 0)
        <div class="table-responsive">
          <table class="table table-hover mb-0">
            <thead class="table-light">
              <tr>
                <th class="px-4 py-3 fw-semibold">Tiêu đề</th>
                <th class="px-4 py-3 fw-semibold">Người phụ trách</th>
                <th class="px-4 py-3 fw-semibold">Phòng ban</th>
                <th class="px-4 py-3 fw-semibold">Ngày giao</th>
                <th class="px-4 py-3 fw-semibold">Deadline</th>
                <th class="px-4 py-3 fw-semibold">Trạng thái</th>
                <th class="px-4 py-3 fw-semibold">Loại</th>
                <th class="px-4 py-3 fw-semibold text-end">Hành động</th>
              </tr>
            </thead>
            <tbody>
              @foreach($allTasks as $task)
                @php
                  $st = $task->status;
                  $badge = [
                    'in_progress' => 'primary',
                    'completed'   => 'warning',
                    'rejected'    => 'danger',
                    'overdue'     => 'danger',
                    'finished'    => 'success',
                  ][$st] ?? 'secondary';
                  
                  // Xác định phòng ban của task
                  $taskDepartment = null;
                  if($task->is_multi_department) {
                    $taskDepartment = 'Đa phòng ban';
                  } else {
                    if($task->assignees && $task->assignees->count() > 0) {
                      $taskDepartment = $task->assignees->first()->department->name ?? 'Không xác định';
                    }
                  }
                @endphp
                <tr class="border-bottom">
                  <td class="px-4 py-3">
                    <div class="fw-medium text-dark">{{ $task->title }}</div>
                    @if($task->description)
                      <small class="text-muted">{{ Str::limit($task->description, 50) }}</small>
                    @endif
                  </td>
                  <td class="px-4 py-3">
                    @if($task->assignees && $task->assignees->count() > 0)
                      @if($task->assignees->count() == 1)
                        <span class="badge bg-dark bg-opacity-10 text-dark border border-dark">
                          <i class="fas fa-user me-1"></i>
                          {{ $task->assignees->first()->name }}
                        </span>
                      @else
                        <span class="badge bg-dark bg-opacity-10 text-dark border border-dark cursor-pointer" 
                              data-bs-toggle="tooltip" 
                              data-bs-html="true"
                              title="@foreach($task->assignees as $assignee){{ $assignee->name }}<br>@endforeach"
                              style="cursor: pointer;">
                          <i class="fas fa-users me-1"></i>
                          {{ $task->assignees->count() }} người
                        </span>
                      @endif
                    @else
                      <span class="text-muted">—</span>
                    @endif
                  </td>
                  <td class="px-4 py-3">
                    @if($task->is_multi_department)
                      <span class="badge bg-warning bg-opacity-10 text-dark border border-warning cursor-pointer" 
                            data-bs-toggle="tooltip" 
                            data-bs-html="true"
                            title="@php
                              $deptNames = [];
                              foreach($task->assignees->groupBy('department_id') as $deptId => $users) {
                                $dept = $users->first()->department;
                                if ($dept) {
                                  $deptNames[] = $dept->name;
                                }
                              }
                              echo implode('<br>', $deptNames);
                            @endphp"
                            style="cursor: pointer;">
                        <i class="fas fa-diagram-3 me-1"></i>
                        {{ $taskDepartment }}
                      </span>
                    @else
                      <span class="badge bg-success bg-opacity-10 text-dark border border-success">
                        <i class="fas fa-building me-1"></i>
                        {{ $taskDepartment }}
                      </span>
                    @endif
                  </td>
                  <td class="px-4 py-3">
                    <span class="text-muted">{{ $task->created_at?->format('d/m/Y') }}</span>
                  </td>
                  <td class="px-4 py-3">
                    @if($task->deadline)
                      @php
                        $isOverdue = $task->deadline < now() && !in_array($task->status, ['finished', 'completed']);
                      @endphp
                      <span class="{{ $isOverdue ? 'text-danger fw-bold' : '' }}">
                        {{ $task->deadline->format('d/m/Y') }}
                      </span>
                      @if($isOverdue)
                        <br><small class="text-danger">Trễ hạn</small>
                      @endif
                    @else
                      <span class="text-muted">—</span>
                    @endif
                  </td>
                  <td class="px-4 py-3">
                    <span class="badge rounded-pill px-3 py-2 fw-medium bg-{{ $badge }} bg-opacity-10 text-dark border border-{{ $badge }}">
                      @if($st == 'in_progress')
                        <i class="fas fa-play me-1"></i>Đang làm
                      @elseif($st == 'completed')
                        <i class="fas fa-hourglass-half me-1"></i>Chờ duyệt
                      @elseif($st == 'overdue')
                        <i class="fas fa-exclamation-triangle me-1"></i>Trễ hạn
                      @elseif($st == 'rejected')
                        <i class="fas fa-times me-1"></i>Từ chối
                      @elseif($st == 'finished')
                        <i class="fas fa-flag-checkered me-1"></i>Kết thúc
                      @else
                        {{ strtoupper($st) }}
                      @endif
                    </span>
                  </td>
                  <td class="px-4 py-3">
                    @if($task->is_multi_department)
                      <span class="badge bg-warning bg-opacity-10 text-dark border border-warning cursor-pointer" 
                            data-bs-toggle="tooltip" 
                            data-bs-html="true"
                            title="@php
                              $deptNames = [];
                              foreach($task->assignees->groupBy('department_id') as $deptId => $users) {
                                $dept = $users->first()->department;
                                if ($dept) {
                                  $deptNames[] = $dept->name;
                                }
                              }
                              echo implode('<br>', $deptNames);
                            @endphp"
                            style="cursor: pointer;">
                        <i class="fas fa-diagram-3 me-1"></i>Đa phòng ban
                      </span>
                    @else
                      <span class="badge bg-success bg-opacity-10 text-dark border border-success">
                        <i class="fas fa-building me-1"></i>Đơn phòng ban
                      </span>
                    @endif
                  </td>
                  <td class="px-4 py-3 text-end">
                    <a href="{{ route('task-detail',$task) }}" class="btn btn-sm btn-outline-info">👁 Xem</a>
                    @if(auth()->user()->isAdmin() || auth()->user()->isManager())
                      <a href="{{ route('tasks.edit',$task) }}" class="btn btn-sm btn-outline-warning">✏️ Sửa</a>
                      <form action="{{ route('tasks.destroy',$task) }}" method="POST" class="d-inline" data-confirm="Xoá công việc này?">
                        @csrf @method('DELETE')
                        <button class="btn btn-sm btn-outline-danger">✖ Xoá</button>
                      </form>
                    @endif
                  </td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      @else
        <div class="text-center py-4 text-muted">
          <p class="mb-0">Chưa có công việc nào.</p>
        </div>
      @endif
    </div>
  </div>
@endif

{{-- Hiển thị công việc cho Employee --}}
@if(auth()->user()->isEmployee())
  <div class="card shadow-sm border-0 mb-4">
    <div class="card-header text-white d-flex align-items-center justify-content-between" style="background: linear-gradient(135deg, #6c757d 0%, #495057 100%);">
      <h5 class="mb-0 fw-bold text-white">
        <i class="bi bi-person-check me-2"></i>
        📋 Công việc của tôi
        <span class="badge bg-light text-dark ms-2">
          {{ isset($tasks) ? $tasks->count() : 0 }} công việc
        </span>
      </h5>
      <div class="d-flex align-items-center">
        <span class="badge bg-secondary text-white me-2">
          <i class="bi bi-person me-1"></i>
          Nhân viên
        </span>
      </div>
    </div>
    
    <div class="card-body p-0">
      @if(isset($tasks) && $tasks->count() > 0)
        <div class="table-responsive">
          <table class="table table-hover mb-0">
            <thead class="table-light">
              <tr>
                <th class="px-4 py-3 fw-semibold">Tiêu đề</th>
                <th class="px-4 py-3 fw-semibold">Người giao việc</th>
                <th class="px-4 py-3 fw-semibold">Phòng ban</th>
                <th class="px-4 py-3 fw-semibold">Ngày giao</th>
                <th class="px-4 py-3 fw-semibold">Deadline</th>
                <th class="px-4 py-3 fw-semibold">Trạng thái</th>
                <th class="px-4 py-3 fw-semibold">Loại</th>
                <th class="px-4 py-3 fw-semibold text-end">Hành động</th>
              </tr>
            </thead>
            <tbody>
              @foreach($tasks as $task)
                @php
                  $st = $task->status;
                  $badge = [
                    'in_progress' => 'primary',
                    'completed'   => 'warning',
                    'rejected'    => 'danger',
                    'overdue'     => 'danger',
                    'finished'    => 'success',
                  ][$st] ?? 'secondary';
                  
                  // Xác định phòng ban của task
                  $taskDepartment = null;
                  if($task->is_multi_department) {
                    $taskDepartment = 'Đa phòng ban';
                  } else {
                    if($task->assignees && $task->assignees->count() > 0) {
                      $taskDepartment = $task->assignees->first()->department->name ?? 'Không xác định';
                    }
                  }
                @endphp
                <tr class="border-bottom">
                  <td class="px-4 py-3">
                    <div class="fw-medium text-dark">{{ $task->title }}</div>
                    @if($task->description)
                      <small class="text-muted">{{ Str::limit($task->description, 50) }}</small>
                    @endif
                  </td>
                  <td class="px-4 py-3">
                    @if($task->creator)
                      <span class="badge bg-dark bg-opacity-10 text-dark border border-dark">
                        <i class="fas fa-user me-1"></i>
                        {{ $task->creator->name }}
                      </span>
                    @else
                      <span class="text-muted">—</span>
                    @endif
                  </td>
                  <td class="px-4 py-3">
                    @if($task->is_multi_department)
                      <span class="badge bg-warning bg-opacity-10 text-dark border border-warning cursor-pointer" 
                            data-bs-toggle="tooltip" 
                            data-bs-html="true"
                            title="@php
                              $deptNames = [];
                              foreach($task->assignees->groupBy('department_id') as $deptId => $users) {
                                $dept = $users->first()->department;
                                if ($dept) {
                                  $deptNames[] = $dept->name;
                                }
                              }
                              echo implode('<br>', $deptNames);
                            @endphp"
                            style="cursor: pointer;">
                        <i class="fas fa-diagram-3 me-1"></i>
                        {{ $taskDepartment }}
                      </span>
                    @else
                      <span class="badge bg-success bg-opacity-10 text-dark border border-success">
                        <i class="fas fa-building me-1"></i>
                        {{ $taskDepartment }}
                      </span>
                    @endif
                  </td>
                  <td class="px-4 py-3">
                    <span class="text-muted">{{ $task->created_at?->format('d/m/Y') }}</span>
                  </td>
                  <td class="px-4 py-3">
                    @if($task->deadline)
                      @php
                        $isOverdue = $task->deadline < now() && !in_array($task->status, ['finished', 'completed']);
                      @endphp
                      <span class="{{ $isOverdue ? 'text-danger fw-bold' : '' }}">
                        {{ $task->deadline->format('d/m/Y') }}
                      </span>
                      @if($isOverdue)
                        <br><small class="text-danger">Trễ hạn</small>
                      @endif
                    @else
                      <span class="text-muted">—</span>
                    @endif
                  </td>
                  <td class="px-4 py-3">
                    <span class="badge rounded-pill px-3 py-2 fw-medium bg-{{ $badge }} bg-opacity-10 text-dark border border-{{ $badge }}">
                      @if($st == 'in_progress')
                        <i class="fas fa-play me-1"></i>Đang làm
                      @elseif($st == 'completed')
                        <i class="fas fa-hourglass-half me-1"></i>Chờ duyệt
                      @elseif($st == 'overdue')
                        <i class="fas fa-exclamation-triangle me-1"></i>Trễ hạn
                      @elseif($st == 'rejected')
                        <i class="fas fa-times me-1"></i>Từ chối
                      @elseif($st == 'finished')
                        <i class="fas fa-flag-checkered me-1"></i>Kết thúc
                      @else
                        {{ strtoupper($st) }}
                      @endif
                    </span>
                  </td>
                  <td class="px-4 py-3">
                    @if($task->is_multi_department)
                      <span class="badge bg-warning bg-opacity-10 text-dark border border-warning cursor-pointer" 
                            data-bs-toggle="tooltip" 
                            data-bs-html="true"
                            title="@php
                              $deptNames = [];
                              foreach($task->assignees->groupBy('department_id') as $deptId => $users) {
                                $dept = $users->first()->department;
                                if ($dept) {
                                  $deptNames[] = $dept->name;
                                }
                              }
                              echo implode('<br>', $deptNames);
                            @endphp"
                            style="cursor: pointer;">
                        <i class="fas fa-diagram-3 me-1"></i>Đa phòng ban
                      </span>
                    @else
                      <span class="badge bg-success bg-opacity-10 text-dark border border-success">
                        <i class="fas fa-building me-1"></i>Đơn phòng ban
                      </span>
                    @endif
                  </td>
                  <td class="px-4 py-3 text-end">
                    <a href="{{ route('task-detail',$task) }}" class="btn btn-sm btn-outline-info">👁 Xem</a>
                    <a href="{{ route('tasks.updateStatus',$task) }}" class="btn btn-sm btn-outline-primary">🔄 Cập nhật</a>
                  </td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>
        
        {{-- Pagination cho Employee --}}
        @if($tasks->count() > 0)
          <div class="card-footer">
                          {{-- Pagination removed - using Collection instead of Paginator --}}
          </div>
        @endif
      @else
        <div class="text-center py-4 text-muted">
          <p class="mb-0">Bạn chưa có công việc nào được giao.</p>
        </div>
      @endif
    </div>
  </div>
@endif
        <div class="col-md-6">
        </div>
      </div>
      
      
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Fix hover conflicts for task rows
    function initializeTaskRowHoverFix() {
        const tableRows = document.querySelectorAll('.table tbody tr');
        let activeRow = null;
        let hoverTimeout = null;

        tableRows.forEach(row => {
            row.addEventListener('mouseenter', function(e) {
                // Clear any pending hover timeout
                if (hoverTimeout) {
                    clearTimeout(hoverTimeout);
                }

                // Remove active state from other rows immediately
                if (activeRow && activeRow !== this) {
                    activeRow.classList.remove('row-hover-active');
                }

                // Set this row as active with small delay to prevent conflicts
                hoverTimeout = setTimeout(() => {
                    this.classList.add('row-hover-active');
                    activeRow = this;
                }, 30);
            });

            row.addEventListener('mouseleave', function(e) {
                // Clear timeout
                if (hoverTimeout) {
                    clearTimeout(hoverTimeout);
                    hoverTimeout = null;
                }

                // Remove active state
                this.classList.remove('row-hover-active');
                if (activeRow === this) {
                    activeRow = null;
                }
            });
        });
    }

    // Initialize hover fix
    initializeTaskRowHoverFix();

    // Fix hover conflicts for task rows
    function initializeTaskRowHoverFix() {
        const tableRows = document.querySelectorAll('.table tbody tr');
        let activeRow = null;
        let hoverTimeout = null;

        tableRows.forEach(row => {
            row.addEventListener('mouseenter', function(e) {
                // Clear any pending hover timeout
                if (hoverTimeout) {
                    clearTimeout(hoverTimeout);
                }

                // Remove active state from other rows immediately
                if (activeRow && activeRow !== this) {
                    activeRow.classList.remove('row-hover-active');
                }

                // Set this row as active with small delay to prevent conflicts
                hoverTimeout = setTimeout(() => {
                    this.classList.add('row-hover-active');
                    activeRow = this;
                }, 30);
            });

            row.addEventListener('mouseleave', function(e) {
                // Clear timeout
                if (hoverTimeout) {
                    clearTimeout(hoverTimeout);
                    hoverTimeout = null;
                }

                // Remove active state
                this.classList.remove('row-hover-active');
                if (activeRow === this) {
                    activeRow = null;
                }
            });
        });
    }

    // Initialize hover fix
    initializeTaskRowHoverFix();
    
    // Initialize tooltips for assignee badges
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl, {
            placement: 'top',
            trigger: 'hover click'
        });
    });
    
    // Function to clear department filter
    window.clearDepartmentFilter = function() {
        const checkboxes = document.querySelectorAll('input[name="department_filter[]"]');
        checkboxes.forEach(checkbox => {
            checkbox.checked = false;
        });
    };
});
</script>

<style>
/* Enhanced hover styles with JavaScript control */
.table tbody tr.row-hover-active {
    background-color: #f8f9fa !important;
    transform: translateY(-1px) !important;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1) !important;
    z-index: 10 !important;
}

.table tbody tr:not(.row-hover-active):hover {
    /* Disable default hover when JS is controlling */
    transform: none !important;
    background-color: transparent !important;
    box-shadow: 0 1px 3px rgba(0,0,0,0.05) !important;
}

/* Assignee badge styling */
.badge[data-bs-toggle="tooltip"] {
    transition: all 0.2s ease;
    cursor: pointer;
}

.badge[data-bs-toggle="tooltip"]:hover {
    transform: scale(1.05);
    box-shadow: 0 2px 8px rgba(0,0,0,0.15);
}

.badge[data-bs-toggle="tooltip"]:active {
    transform: scale(0.95);
}

/* Thống kê cards styling */
.card.text-center {
    transition: all 0.3s ease;
    border: none;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.card.text-center:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 16px rgba(0,0,0,0.15);
}

/* Bảng thống nhất styling */
.table th {
    background-color: #f8f9fa;
    border-bottom: 2px solid #dee2e6;
    font-weight: 600;
    color: #495057;
}

.table td {
    vertical-align: middle;
    border-bottom: 1px solid #f1f3f5;
}

.table tbody tr:hover {
    background-color: #f8f9fa;
    transition: all 0.15s ease;
}
</style>
@endpush
