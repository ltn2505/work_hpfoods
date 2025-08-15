@extends('layouts.master')
@section('title','Quản lý công việc')

@section('content')
<div class="row g-3 mb-4">
  <div class="col-md-3 col-sm-6">
    <div class="card card-stat text-center p-3">
      <h5 class="text-primary">{{ $stats['doing'] }}</h5>
      <p class="mb-0">Công việc đang làm</p>
    </div>
  </div>
  <div class="col-md-3 col-sm-6">
    <div class="card card-stat text-center p-3">
      <h5 class="text-success">{{ $stats['done'] }}</h5>
      <p class="mb-0">Hoàn thành</p>
    </div>
  </div>
  <div class="col-md-3 col-sm-6">
    <div class="card card-stat text-center p-3">
      <h5 class="text-danger">{{ $stats['overdue'] }}</h5>
      <p class="mb-0">Trễ hạn</p>
    </div>
  </div>
  <div class="col-md-3 col-sm-6">
    <div class="card card-stat text-center p-3">
      <h5 class="text-warning">{{ $stats['todo'] }}</h5>
      <p class="mb-0">Chưa bắt đầu</p>
    </div>
  </div>
</div>

<div class="card">
  <div class="card-header d-flex justify-content-between align-items-center">
    <h5 class="mb-0">Danh sách công việc</h5>
    @if(in_array(auth()->user()->role, ['admin','manager']))
      <a href="{{ route('create-task') }}" class="btn btn-primary btn-sm">+ Tạo công việc</a>
    @endif
  </div>

  <div class="card-body p-0">
    <div class="table-responsive">
      <table class="table mb-0 align-middle">
        <thead class="table-light">
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
              $map   = ['todo'=>'Chưa bắt đầu','in_progress'=>'Đang làm','done'=>'Hoàn thành','overdue'=>'Trễ'];
              $badge = ['todo'=>'bg-warning','in_progress'=>'bg-primary','done'=>'bg-success','overdue'=>'bg-danger'];
              $st    = $task->status;
            @endphp
            <tr>
              <td>{{ $task->title }}</td>
              <td>{{ $task->assignee->name ?? '—' }}</td>
              <td>{{ optional($task->assigned_at ?? $task->created_at)->format('d/m/Y') }}</td>
              <td>{{ optional($task->deadline)->format('d/m/Y') ?? '—' }}</td>
              <td><span class="badge {{ $badge[$st] ?? 'bg-secondary' }}">{{ $map[$st] ?? strtoupper($st) }}</span></td>
              <td class="text-end">
                <a href="{{ route('task-detail', $task->id) }}" class="btn btn-sm btn-outline-info">👁 Xem</a>
                @if(in_array(auth()->user()->role, ['admin','manager']))
                  <a href="{{ route('tasks.edit', $task->id) }}" class="btn btn-sm btn-outline-warning">✎ Sửa</a>
                  <form action="{{ route('tasks.destroy', $task->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Xóa công việc này?')">
                    @csrf @method('DELETE')
                    <button class="btn btn-sm btn-outline-danger">✖ Xoá</button>
                  </form>
                @endif
              </td>
            </tr>
          @empty
            <tr><td colspan="6" class="text-center py-4">Chưa có công việc.</td></tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>

  <div class="card-footer">
    {{ $tasks->links() }}
  </div>
</div>
@endsection
