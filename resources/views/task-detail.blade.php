@extends('layouts.master')
@section('title','Chi tiết công việc')

@section('content')
<div class="container py-3">
  <h3 class="mb-3">{{ $task->title }}</h3>

  <div class="row g-3">
    <div class="col-md-6">
      <div class="card p-3">
        <div><strong>Phòng ban:</strong> {{ $task->department->name ?? '—' }}</div>
        <div><strong>Người phụ trách:</strong> {{ $task->assignee->name ?? '—' }}</div>
        <div><strong>Người tạo:</strong> {{ $task->creator->name ?? '—' }}</div>
        <div><strong>Ngày giao:</strong> {{ optional($task->assigned_at ?? $task->created_at)->format('d/m/Y') }}</div>
        <div><strong>Deadline:</strong> {{ optional($task->deadline)->format('d/m/Y') ?? '—' }}</div>
        @php                                                                                                                
          $map   = ['todo'=>'Chưa bắt đầu','in_progress'=>'Đang làm','done'=>'Hoàn thành','overdue'=>'Trễ'];
          $badge = ['todo'=>'bg-warning','in_progress'=>'bg-primary','done'=>'bg-success','overdue'=>'bg-danger'];
          $st    = $task->status;
        @endphp
        <div><strong>Trạng thái:</strong> <span class="badge {{ $badge[$st] ?? 'bg-secondary' }}">{{ $map[$st] ?? strtoupper($st) }}</span></div>
      </div>
    </div>

    <div class="col-md-6">
      <div class="card p-3">
        <strong>Mô tả</strong>
        <p class="mb-0">{{ $task->description ?? '—' }}</p>
      </div>
    </div>
  </div>

  <div class="card mt-3 p-3">
    <h6 class="mb-2">Nhật ký hoạt động</h6>
    <ul class="mb-0">
      @forelse($task->activities as $act)
        <li>[{{ $act->created_at->format('d/m/Y H:i') }}] {{ $act->user->name ?? '—' }} — {{ $act->action }}</li>
      @empty
        <li>Chưa có hoạt động.</li>
      @endforelse
    </ul>
  </div>

  <div class="mt-3">
    <a href="{{ route('dashboard') }}" class="btn btn-secondary">← Quay lại</a>
    @if(in_array(auth()->user()->role, ['admin','manager']))
      <a href="{{ route('tasks.edit',$task->id) }}" class="btn btn-warning">Sửa</a>
    @endif
  </div>
</div>
@endsection
