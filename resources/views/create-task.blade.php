@extends('layouts.master')
@section('title','Tạo công việc')

@section('content')
<div class="container py-3">
  <h3 class="mb-3">Tạo công việc</h3>

  <form method="POST" action="{{ route('tasks.store') }}">
    @csrf

    <div class="mb-3">
      <label class="form-label">Tiêu đề</label>
      <input type="text" name="title" class="form-control" value="{{ old('title') }}" required>
      @error('title') <div class="text-danger small">{{ $message }}</div> @enderror
    </div>

    <div class="mb-3">
      <label class="form-label">Mô tả</label>
      <textarea name="description" class="form-control" rows="4">{{ old('description') }}</textarea>
    </div>

    <div class="row g-3">
      <div class="col-md-4">
        <label class="form-label">Trạng thái</label>
        <select name="status" class="form-select">
          <option value="todo"        @selected(old('status')==='todo')>Chưa bắt đầu</option>
          <option value="in_progress" @selected(old('status')==='in_progress')>Đang làm</option>
          <option value="done"        @selected(old('status')==='done')>Hoàn thành</option>
        </select>
      </div>

      <div class="col-md-4">
        <label class="form-label">Phòng ban</label>
        <select name="department_id" class="form-select">
          @foreach($departments as $d)
            <option value="{{ $d->id }}" @selected(old('department_id')==$d->id)>{{ $d->name }}</option>
          @endforeach
        </select>
      </div>

      <div class="col-md-4">
        <label class="form-label">Người phụ trách</label>
        <select name="assignee_id" class="form-select">
          <option value="">— Chưa gán —</option>
          @foreach($assignees as $u)
            <option value="{{ $u->id }}" @selected(old('assignee_id')==$u->id)>{{ $u->name }}</option>
          @endforeach
        </select>
      </div>
    </div>

    <div class="row g-3 mt-1">
      <div class="col-md-4">
        <label class="form-label">Deadline</label>
        <input type="date" name="deadline" class="form-control" value="{{ old('deadline') }}">
      </div>
    </div>

    <div class="mt-3">
      <button class="btn btn-primary">Lưu</button>
      <a href="{{ route('dashboard') }}" class="btn btn-secondary">Hủy</a>
    </div>
  </form>
</div>
@endsection
