@extends('layouts.master')
@section('title','Danh sách công việc')

@push('styles')
<style>
/* CSS cho giao diện Manager/Employee */
.table-hover tbody tr:hover {
    background-color: #f8f9fa;
    transform: scale(1.01);
    transition: all 0.2s ease;
}

.badge {
    font-size: 0.8rem;
    font-weight: 500;
}

.btn-group .btn {
    transition: all 0.3s ease;
}

.btn-group .btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.15);
}

.card {
    transition: all 0.3s ease;
}

.card:hover {
    box-shadow: 0 8px 25px rgba(0,0,0,0.1) !important;
}

.table th {
    font-weight: 600;
    color: #495057;
    border-bottom: 2px solid #dee2e6;
}

.table td {
    vertical-align: middle;
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
}
</style>
@endpush

@section('content')
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

<div class="mb-2">
  <form method="GET" action="{{ route('dashboard') }}" class="d-inline">
    <input type="hidden" name="status" value="">
    <button type="submit" class="btn btn-sm btn-outline-secondary{{ !request('status') ? ' active' : '' }}">Tất cả</button>
  </form>

  <form method="GET" action="{{ route('dashboard') }}" class="d-inline">
    <input type="hidden" name="status" value="in_progress">
    <button type="submit" class="btn btn-sm btn-outline-primary{{ request('status')=='in_progress' ? ' active' : '' }}">Đang làm</button>
  </form>
  <form method="GET" action="{{ route('dashboard') }}" class="d-inline">
    <input type="hidden" name="status" value="completed">
    <button type="submit" class="btn btn-sm btn-outline-warning{{ request('status')=='completed' ? ' active' : '' }}">Chờ duyệt</button>
  </form>

  <form method="GET" action="{{ route('dashboard') }}" class="d-inline">
    <input type="hidden" name="status" value="rejected">
    <button type="submit" class="btn btn-sm btn-outline-danger{{ request('status')=='rejected' ? ' active' : '' }}">Từ chối</button>
  </form>
  <form method="GET" action="{{ route('dashboard') }}" class="d-inline">
    <input type="hidden" name="status" value="overdue">
    <button type="submit" class="btn btn-sm btn-outline-danger{{ request('status')=='overdue' ? ' active' : '' }}">Trễ hạn</button>
  </form>
  <form method="GET" action="{{ route('dashboard') }}" class="d-inline">
    <input type="hidden" name="status" value="finished">
    <button type="submit" class="btn btn-sm btn-outline-success{{ request('status')=='finished' ? ' active' : '' }}">Kết thúc</button>
  </form>
</div>

@if(auth()->user()->isAdmin() && isset($departments))
  {{-- Giao diện Admin: Hiển thị theo từng phòng ban --}}
  <div class="row g-4">
    @foreach($departments as $department)
      <div class="col-12">
        <div class="card">
          <div class="card-header bg-primary text-white">
            <h5 class="mb-0">
              🏢 {{ $department->name }}
              <span class="badge bg-light text-dark ms-2">
                {{ $departmentTasks[$department->id]->count() }} công việc
              </span>
            </h5>
          </div>
          <div class="card-body">
            @if($departmentTasks[$department->id]->count() > 0)
              <div class="table-responsive">
                <table class="table table-sm align-middle mb-0">
                  <thead>
                    <tr>
                      <th>Tiêu đề</th>
                      <th>Người phụ trách</th>
                      <th>Ngày giao</th>
                      <th>Deadline</th>
                      <th>Trạng thái</th>
                      <th class="text-end">Hành động</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach($departmentTasks[$department->id] as $task)
                      @php
                        $st = $task->status;
                        $badge = ['todo'=>'warning','in_progress'=>'primary','done'=>'success'][$st] ?? 'secondary';
                        if($task->deadline && $st!='done' && $task->deadline->isPast()) $badge = 'danger';
                      @endphp
                      <tr>
                        <td>{{ $task->title }}</td>
                        <td>{{ $task->assignee?->name ?? '—' }}</td>
                        <td>{{ $task->created_at?->format('d/m/Y') }}</td>
                        <td>{{ $task->deadline?->format('d/m/Y') ?? '—' }}</td>
                        <td><span class="badge bg-{{ $badge }}">{{ __("statuses.$st") ?? strtoupper($st) }}</span></td>
                        <td class="text-end">
                          <a href="{{ route('task-detail',$task) }}" class="btn btn-sm btn-outline-info">👁 Xem</a>
                          <a href="{{ route('tasks.edit',$task) }}" class="btn btn-sm btn-outline-warning">✏️ Sửa</a>
                          <form action="{{ route('tasks.destroy',$task) }}" method="POST" class="d-inline" data-confirm="Xoá công việc này?">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger">✖ Xoá</button>
                          </form>
                        </td>
                      </tr>
                    @endforeach
                  </tbody>
                </table>
              </div>
            @else
              <div class="text-center py-4 text-muted">
                <p class="mb-0">Phòng ban này chưa có công việc nào.</p>
              </div>
            @endif
          </div>
        </div>
      </div>
    @endforeach
  </div>
@else
  {{-- Giao diện Manager/Employee: Hiển thị dạng bảng đơn giản --}}
  <div class="card shadow-sm border-0">
    <div class="card-header bg-white border-0 py-3">
      <h5 class="mb-0 text-primary">
        <i class="fas fa-tasks me-2"></i>
        Danh sách công việc
      </h5>
    </div>
    <div class="card-body p-0">
      <div class="table-responsive">
        <table class="table table-hover mb-0">
          <thead class="table-light">
            <tr>
              <th class="px-4 py-3 fw-semibold">Tiêu đề</th>
              <th class="px-4 py-3 fw-semibold">Người phụ trách</th>
              <th class="px-4 py-3 fw-semibold">Ngày giao</th>
              <th class="px-4 py-3 fw-semibold">Deadline</th>
              <th class="px-4 py-3 fw-semibold">Trạng thái</th>
              <th class="px-4 py-3 fw-semibold text-end">Hành động</th>
            </tr>
          </thead>
          <tbody>
            @forelse($tasks as $task)
              @php
                $st = $task->status;
                $badge = [
                    'in_progress' => 'primary', 
                    'completed' => 'warning',
                    'rejected' => 'danger',
                    'overdue' => 'danger',
                    'finished' => 'success'
                ][$st] ?? 'secondary';
              @endphp
              <tr class="border-bottom">
                <td class="px-4 py-3">
                  <div class="fw-medium text-dark">{{ $task->title }}</div>
                  @if($task->description)
                    <small class="text-muted">{{ Str::limit($task->description, 50) }}</small>
                  @endif
                </td>
                <td class="px-4 py-3">
                  <span class="badge bg-light text-dark border">
                    <i class="fas fa-user me-1"></i>
                    {{ $task->assignee?->name ?? '—' }}
                  </span>
                </td>
                <td class="px-4 py-3">
                  <span class="text-muted">{{ $task->created_at?->format('d/m/Y') }}</span>
                </td>
                <td class="px-4 py-3">
                  @if($task->deadline)
                    <span class="badge bg-info bg-opacity-10 text-info border border-info">
                      <i class="fas fa-calendar me-1"></i>
                      {{ $task->deadline->format('d/m/Y') }}
                    </span>
                  @else
                    <span class="text-muted">—</span>
                  @endif
                </td>
                <td class="px-4 py-3">
                  <span class="badge rounded-pill px-3 py-2 fw-medium bg-{{ $badge }} bg-opacity-10 text-{{ $badge }} border border-{{ $badge }}">
                    @if($st == 'in_progress')
                      <i class="fas fa-play me-1"></i>Đang làm
                    @elseif($st == 'completed')
                      <i class="fas fa-hourglass-half me-1"></i>Chờ duyệt
                    @elseif($st == 'rejected')
                      <i class="fas fa-times me-1"></i>Từ chối
                    @elseif($st == 'overdue')
                      <i class="fas fa-exclamation-triangle me-1"></i>Trễ hạn
                    @elseif($st == 'finished')
                      <i class="fas fa-flag-checkered me-1"></i>Kết thúc
                    @else
                      {{ strtoupper($st) }}
                    @endif
                  </span>
                </td>
                <td class="px-4 py-3 text-end">
                  <div class="btn-group" role="group">
                    <a href="{{ route('task-detail',$task) }}" class="btn btn-sm btn-outline-primary border-0 rounded-start">
                      <i class="fas fa-eye me-1"></i>Xem
                    </a>
                    @if(auth()->user()->isAdmin() || auth()->user()->isManager())
                      <a href="{{ route('tasks.edit',$task) }}" class="btn btn-sm btn-outline-warning border-0">
                        <i class="fas fa-edit me-1"></i>Sửa
                      </a>
                      <form action="{{ route('tasks.destroy',$task) }}" method="POST" class="d-inline" data-confirm="Xoá công việc này?">
                        @csrf @method('DELETE')
                        <button class="btn btn-sm btn-outline-danger border-0 rounded-end">
                          <i class="fas fa-trash me-1"></i>Xoá
                        </button>
                      </form>
                    @endif
                  </div>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="6" class="text-center py-5">
                  <div class="text-muted">
                    <i class="fas fa-inbox fa-3x mb-3 opacity-50"></i>
                    <h6 class="mb-2">Chưa có công việc nào</h6>
                    <p class="mb-0">Hãy tạo công việc mới để bắt đầu</p>
                  </div>
                </td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
    @if($tasks->hasPages())
      <div class="card-footer bg-light border-0">
        <div class="d-flex justify-content-center">
          {{ $tasks->links() }}
        </div>
      </div>
    @endif
  </div>
@endif
@endsection
