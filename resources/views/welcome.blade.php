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
  <a href="{{ route('tasks.index') }}" class="btn btn-sm btn-outline-secondary">Tất cả</a>
  <a href="{{ route('tasks.index',['status'=>'in_progress']) }}" class="btn btn-sm btn-outline-primary">Đang làm</a>
  <a href="{{ route('tasks.index',['status'=>'done']) }}" class="btn btn-sm btn-outline-success">Hoàn thành</a>
  <a href="{{ route('tasks.index',['status'=>'overdue']) }}" class="btn btn-sm btn-outline-danger">Trễ</a>
</div>

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
@endsection
