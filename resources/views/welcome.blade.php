@extends('layouts.master')
@section('title','Danh sách công việc')

@section('content')
<div class="row g-3 mb-3">
  <div class="col-md-3">
    <div class="card card-stat p-3 text-center">
      <h5 class="text-primary">{{ $stats['doing'] }}</h5>
      <p>Công việc đang làm</p>
    </div>
  </div>
  <div class="col-md-3">
    <div class="card card-stat p-3 text-center">
      <h5 class="text-success">{{ $stats['done'] }}</h5>
      <p>Hoàn thành</p>
    </div>
  </div>
  <div class="col-md-3">
    <div class="card card-stat p-3 text-center">
      <h5 class="text-danger">{{ $stats['overdue'] }}</h5>
      <p>Trễ hạn</p>
    </div>
  </div>
  <div class="col-md-3">
    <div class="card card-stat p-3 text-center">
      <h5 class="text-warning">{{ $stats['todo'] }}</h5>
      <p>Chưa bắt đầu</p>
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
    <input type="hidden" name="status" value="done">
    <button type="submit" class="btn btn-sm btn-outline-success{{ request('status')=='done' ? ' active' : '' }}">Hoàn thành</button>
  </form>
  <form method="GET" action="{{ route('dashboard') }}" class="d-inline">
    <input type="hidden" name="status" value="overdue">
    <button type="submit" class="btn btn-sm btn-outline-danger{{ request('status')=='overdue' ? ' active' : '' }}">Trễ</button>
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
  <div class="card">
    <div class="table-responsive">
      <table class="table align-middle mb-0">
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
        @forelse($tasks as $task)
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
              @canany(['admin','manager'])
                <a href="{{ route('tasks.edit',$task) }}" class="btn btn-sm btn-outline-warning">✏️ Sửa</a>
                <form action="{{ route('tasks.destroy',$task) }}" method="POST" class="d-inline" data-confirm="Xoá công việc này?">
                  @csrf @method('DELETE')
                  <button class="btn btn-sm btn-outline-danger">✖ Xoá</button>
                </form>
              @endcanany
            </td>
          </tr>
        @empty
          <tr><td colspan="6" class="text-center py-4">Chưa có công việc.</td></tr>
        @endforelse
        </tbody>
      </table>
    </div>
    <div class="card-footer">
      {{ $tasks->links() }}
    </div>
  </div>
@endif
@endsection
