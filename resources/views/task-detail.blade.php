@extends('layouts.master')
@section('title',$task->title)

@section('content')
<div class="task-header">
  <h3 class="mb-2">{{ $task->title }}</h3>
  <span class="badge priority-badge bg-{{ $task->status=='done'?'success':($task->status=='in_progress'?'primary': 'warning') }}">
    {{ __("statuses.$task->status") ?? strtoupper($task->status) }}
  </span>
</div>

<div class="row g-3">
  <div class="col-lg-8">
    <div class="progress-section">
      <h6 class="mb-3">Thông tin chung</h6>
      <div class="row">
        <div class="col-6">Người giao: <strong>{{ $task->creator->name }}</strong></div>
        <div class="col-6">Deadline:
          <strong>{{ $task->deadline? $task->deadline->format('d/m/Y'):'—' }}</strong>
        </div>
        <div class="col-6">Người nhận: 
          <strong>
            @if($task->is_multi_user && $task->assignees->count() > 0)
              {{ $task->assignees->count() }} người: {{ $task->assignees->pluck('name')->join(', ') }}
            @elseif($task->assignee)
              {{ $task->assignee->name }}
            @else
              —
            @endif
          </strong>
        </div>
        <div class="col-6">Phòng ban: 
          <strong>
            @if($task->is_multi_department && $task->departments->count() > 0)
              {{ $task->departments->count() }} phòng ban: {{ $task->departments->pluck('name')->join(', ') }}
            @elseif($task->department)
              {{ $task->department->name }}
            @else
              —
            @endif
          </strong>
        </div>
        <div class="col-6">Trạng thái: <strong>{{ __("statuses.$task->status") ?? strtoupper($task->status) }}</strong></div>
      </div>
    </div>

    <div class="comment-section">
      <h6 class="mb-3">Thảo luận</h6>
      <form class="mb-3" action="{{ route('tasks.comment',$task) }}" method="POST" id="commentForm">
        @csrf
        <textarea name="content" class="form-control mb-2" rows="3" placeholder="Viết bình luận..." id="commentTextarea"></textarea>
        <div id="commentError" class="text-danger mb-2" style="display: none;">
          <i class="bi bi-exclamation-triangle me-1"></i>
          Không được phép nhập từ dài hơn 45 ký tự!
        </div>
        <button class="btn btn-primary btn-sm" id="commentSubmitBtn">Gửi bình luận</button>
      </form>

      @forelse($task->activities as $act)
        <div class="comment-item">
          <strong>{{ $act->user->name }}</strong>
          <small class="text-muted ms-2">{{ $act->created_at->diffForHumans() }}</small>
          <div>{{ $act->meta }}</div>
        </div>
      @empty
        <div class="text-muted">Chưa có bình luận.</div>
      @endforelse
    </div>
  </div>

  <div class="col-lg-4">
    <div class="report-card">
      <h6 class="mb-3">File đính kèm</h6>
      @forelse($task->attachments ?? [] as $file)
        <div class="file-attachment">
          📎 <a href="{{ $file['url'] }}" target="_blank">{{ $file['name'] }}</a>
        </div>
      @empty
        <div class="text-muted">Chưa có tệp.</div>
      @endforelse
    </div>

    <div class="report-card">
      <h6 class="mb-3">Hành động</h6>
      
      {{-- Nút hoàn thành --}}
      @if($task->status === 'in_progress')
        <a href="{{ route('tasks.updateStatus',[$task,'status'=>'completed']) }}" class="btn btn-success w-100 mb-2">✅ Hoàn thành</a>
      @endif
      
      {{-- Nút hoàn tác (chỉ hiển thị khi status = completed và trong vòng 3 tiếng) --}}
      @if($task->status === 'completed' && $task->canUndo())
        <form action="{{ route('tasks.undoCompletion', $task) }}" method="POST" class="mb-2">
          @csrf
          <button type="submit" class="btn btn-warning w-100" onclick="return confirm('Bạn có chắc muốn hoàn tác công việc này?')">
            Hoàn tác ({{ 3 - $task->completed_at->diffInHours(now()) }}h còn lại)
          </button>
        </form>
      @endif
      
      {{-- Nút cập nhật trạng thái --}}
      @if($task->status === 'in_progress')
        <a href="{{ route('tasks.updateStatus',[$task,'status'=>'in_progress']) }}" class="btn btn-primary w-100 mb-2">🔄 Cập nhật trạng thái</a>
      @endif
      
      {{-- Nút xem lịch sử --}}
      <a href="{{ route('tasks.history',$task) }}" class="btn btn-outline-info w-100">👁 Xem lịch sử</a>
      
      {{-- Thông báo không thể hoàn tác --}}
      @if($task->status === 'completed' && !$task->canUndo())
        <div class="alert alert-warning mt-2">
          <small>⚠️ Không thể hoàn tác sau 3 tiếng kể từ khi hoàn thành</small>
        </div>
      @endif
    </div>
  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const commentTextarea = document.getElementById('commentTextarea');
    const commentError = document.getElementById('commentError');
    const commentSubmitBtn = document.getElementById('commentSubmitBtn');
    const commentForm = document.getElementById('commentForm');

    function checkWordLength(text) {
        const words = text.trim().split(/\s+/);
        return words.every(word => word.length <= 45);
    }

    function validateComment() {
        const text = commentTextarea.value;
        const isValid = checkWordLength(text);
        
        if (!isValid) {
            commentError.style.display = 'block';
            commentSubmitBtn.disabled = true;
            commentSubmitBtn.innerHTML = 'Từ quá dài (>45 ký tự)';
            commentSubmitBtn.classList.remove('btn-primary');
            commentSubmitBtn.classList.add('btn-danger');
        } else {
            commentError.style.display = 'none';
            commentSubmitBtn.disabled = false;
            commentSubmitBtn.innerHTML = 'Gửi bình luận';
            commentSubmitBtn.classList.remove('btn-danger');
            commentSubmitBtn.classList.add('btn-primary');
        }
    }

    commentTextarea.addEventListener('input', validateComment);
    commentTextarea.addEventListener('paste', validateComment);

    commentForm.addEventListener('submit', function(e) {
        const text = commentTextarea.value;
        if (!checkWordLength(text)) {
            e.preventDefault();
            alert('Không được phép nhập từ dài hơn 45 ký tự!');
            return false;
        }
    });
});
</script>
@endsection
