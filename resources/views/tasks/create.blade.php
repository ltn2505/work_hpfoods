@extends('layouts.master')
@section('title','Tạo công việc')

@section('content')
<div class="card form-container">
  <form action="{{ route('tasks.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="row g-3">
      <div class="col-lg-6">
        <label class="form-label">Tiêu đề *</label>
        <input type="text" name="title" class="form-control" required value="{{ old('title') }}">
      </div>

      <div class="col-lg-6">
        <label class="form-label">Người nhận</label>
        <select name="assignee_id" class="form-select">
          <option value="">Chọn người nhận</option>
          @foreach($users as $u)
            <option value="{{ $u->id }}" @selected(old('assignee_id')==$u->id)>
              {{ $u->name }} 
              @if($u->department_id)
                <small class="text-muted">({{ \App\Models\Department::find($u->department_id)->name ?? 'N/A' }})</small>
              @endif
            </option>
          @endforeach
        </select>
        @if(auth()->user()->isManager())
          <small class="text-muted">Bạn chỉ có thể giao việc cho nhân viên cùng phòng ban</small>
        @endif
      </div>

      <div class="col-lg-6">
        <label class="form-label">Mô tả</label>
        <textarea name="description" rows="5" class="form-control">{{ old('description') }}</textarea>
      </div>

      <div class="col-lg-6">
        <label class="form-label">Deadline</label>
        <input type="datetime-local" name="deadline" class="form-control" value="{{ old('deadline') }}">
        <div class="mt-3">
          <label class="form-label d-block mb-2">Độ ưu tiên</label>
          <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="priority" value="low" checked>
            <label class="form-check-label">Thấp</label>
          </div>
          <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="priority" value="medium">
            <label class="form-check-label">Trung bình</label>
          </div>
          <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="priority" value="high">
            <label class="form-check-label">Cao</label>
          </div>
        </div>
      </div>

      <div class="col-12">
        <label class="form-label">File đính kèm</label>
        <div class="file-drop-zone" id="fileDropZone" style="border: 2px dashed #ccc; padding: 20px; text-align: center; cursor: pointer;">
            Kéo & thả file vào đây<br><small>hoặc click để chọn file</small>
            <div id="fileList" style="margin-top:10px;"></div>
        </div>
        <input type="file" name="attachments[]" id="fileInput" class="d-none" multiple>
      </div>

      <div class="col-12 mt-3">
        <button class="btn btn-success w-100">🚀 Giao việc</button>
      </div>
    </div>
  </form>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const dropZone = document.getElementById('fileDropZone');
    const fileInput = document.getElementById('fileInput');
    const fileList = document.getElementById('fileList');

    dropZone.addEventListener('click', function() {
        fileInput.click();
    });

    dropZone.addEventListener('dragover', function(e) {
        e.preventDefault();
        dropZone.style.background = '#f0f0f0';
    });

    dropZone.addEventListener('dragleave', function(e) {
        e.preventDefault();
        dropZone.style.background = '';
    });

    dropZone.addEventListener('drop', function(e) {
        e.preventDefault();
        dropZone.style.background = '';
        fileInput.files = e.dataTransfer.files;
        showFiles();
    });

    fileInput.addEventListener('change', showFiles);

    function showFiles() {
        fileList.innerHTML = '';
        for (let i = 0; i < fileInput.files.length; i++) {
            fileList.innerHTML += '<div>' + fileInput.files[i].name + '</div>';
        }
    }
});
</script>
@endpush
