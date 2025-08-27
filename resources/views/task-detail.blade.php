@extends('layouts.master')
@section('title',$task->title)

@section('content')
<div class="task-header" style="background: linear-gradient(135deg, #007bff 0%, #28a745 100%); padding: 20px; border-radius: 10px; margin-bottom: 20px; position: relative;">
  <div class="d-flex justify-content-between align-items-center">
    <div>
      <h3 class="mb-2 text-white">{{ $task->title }}</h3>
      <div class="d-flex gap-2">
        <span class="badge bg-warning text-dark">
          @if($task->status == 'in_progress')
            Đang tiến hành
          @elseif($task->status == 'completed')
            Chờ duyệt
          @elseif($task->status == 'rejected')
            Từ chối
          @elseif($task->status == 'overdue')
            Trễ hạn
          @elseif($task->status == 'finished')
            Kết thúc
          @else
            {{ strtoupper($task->status) }}
          @endif
        </span>
        <span class="badge bg-warning text-dark">
          Độ ưu tiên: {{ ucfirst($task->priority ?? 'Medium') }}
        </span>
      </div>
    </div>
    <a href="{{ route('dashboard') }}" class="btn btn-primary">
      ← Quay lại
    </a>
  </div>
</div>

<div class="row g-3">
  <div class="col-lg-8">
    <div class="progress-section" style="background: white; border-radius: 10px; padding: 20px; margin-bottom: 20px; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
      <h6 class="mb-3">
        <i class="bi bi-info-circle me-2"></i>Thông tin chung
      </h6>
      <div class="row">
        <div class="col-6">Người giao: <strong>{{ $task->creator->name }}</strong></div>
        <div class="col-6">Người nhận: 
          <strong>
            @if($task->assignees->count() > 0)
              @if($task->assignees->count() == 1)
                {{ $task->assignees->first()->name }}
              @else
                {{ $task->assignees->count() }} người: {{ $task->assignees->pluck('name')->join(', ') }}
              @endif
            @elseif($task->assignee)
              {{ $task->assignee->name }}
            @else
              —
            @endif
          </strong>
        </div>
        <div class="col-6">Độ ưu tiên: <strong>{{ ucfirst($task->priority ?? 'Medium') }}</strong></div>
        <div class="col-6">Ngày giao: <strong>{{ $task->created_at? $task->created_at->format('d/m/Y'):'—' }}</strong></div>
        <div class="col-6">Deadline:
          <strong>{{ $task->deadline? $task->deadline->format('d/m/Y'):'—' }}</strong>
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
        <div class="col-6">Trạng thái: <strong>
          @if($task->status == 'in_progress')
            Đang tiến hành
          @elseif($task->status == 'completed')
            Hoàn thành
          @elseif($task->status == 'rejected')
            Từ chối
          @elseif($task->status == 'overdue')
            Trễ hạn
          @elseif($task->status == 'finished')
            Kết thúc
          @else
            {{ strtoupper($task->status) }}
          @endif
        </strong></div>
        <div class="col-12">Task Followers: 
          <strong>
            @if($task->followers->count() > 0)
              {{ $task->followers->count() }} người: {{ $task->followers->pluck('name')->join(', ') }}
            @else
              Chưa có người theo dõi
            @endif
          </strong>
        </div>
        
        {{-- Thông tin task recurring --}}
        @if($task->is_recurring && $task->recurring_days)
          <div class="col-12 mt-3">
            <div class="alert alert-info" style="background: #e3f2fd; border: 1px solid #2196f3; color: #1976d2;">
              <i class="bi bi-arrow-repeat me-2"></i>
              <strong>Lặp lại:</strong> Công việc sẽ lặp lại mỗi {{ $task->recurring_days }} ngày từ ngày {{ $task->recurring_start_date ? $task->recurring_start_date->format('d/m/Y') : $task->created_at->format('d/m/Y') }}
            </div>
          </div>
        @endif
      </div>
    </div>

    <div class="comment-section" style="background: white; border-radius: 10px; padding: 20px; margin-bottom: 20px; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
      <h6 class="mb-3">
        <i class="bi bi-chat-dots me-2"></i>Thảo luận
      </h6>
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
          <div>
            @if($act->action === 'comment' && $act->meta)
              @php
                $meta = json_decode($act->meta, true);
              @endphp
              @if($meta && isset($meta['content']))
                {{ $meta['content'] }}
                @if(isset($meta['attachments']) && !empty($meta['attachments']))
                  <div class="mt-2">
                    @foreach($meta['attachments'] as $attachment)
                      <div class="file-attachment">
                        📎 <a href="{{ $attachment['url'] }}" target="_blank">{{ $attachment['name'] }}</a>
                      </div>
                    @endforeach
                  </div>
                @endif
              @else
                {{ $act->meta }}
              @endif
            @else
              {{ $act->meta }}
            @endif
          </div>
        </div>
      @empty
        <div class="text-muted">Chưa có bình luận.</div>
      @endforelse
    </div>
  </div>

  <div class="col-lg-4">
    <div class="report-card" style="background: white; border-radius: 10px; padding: 20px; margin-bottom: 20px; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
      <h6 class="mb-3">
        <i class="bi bi-paperclip me-2"></i>File đính kèm
      </h6>
      @forelse($task->attachments ?? [] as $file)
        <div class="file-attachment">
          📎 <a href="{{ $file['url'] }}" target="_blank">{{ $file['name'] }}</a>
        </div>
      @empty
        <div class="text-muted">Chưa có tệp.</div>
      @endforelse
    </div>

    {{-- Task Followers Management (chỉ Admin và Manager) --}}
    @if(auth()->user()->isAdmin() || auth()->user()->isManager())
    <div class="report-card" style="background: white; border-radius: 10px; padding: 20px; margin-bottom: 20px; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
      <h6 class="mb-3">
        <i class="bi bi-people me-2"></i>Quản lý Task Followers
      </h6>
      
      {{-- Danh sách followers hiện tại --}}
      <div class="mb-3">
        <strong>Người đang theo dõi:</strong>
        <div id="currentFollowers">
          @if($task->followers->count() > 0)
            @foreach($task->followers as $follower)
              <div class="d-flex justify-content-between align-items-center mb-1">
                <span>{{ $follower->name }} ({{ $follower->role }})</span>
                <button class="btn btn-sm btn-outline-danger" onclick="removeFollower({{ $follower->id }})">
                  <i class="bi bi-x"></i>
                </button>
              </div>
            @endforeach
          @else
            <div class="text-muted">Chưa có người theo dõi</div>
          @endif
        </div>
      </div>
      
      {{-- Thêm follower mới --}}
      <div class="mb-3">
        <label for="newFollower" class="form-label">Thêm người theo dõi:</label>
        <select class="form-select" id="newFollower">
          <option value="">Chọn người dùng...</option>
        </select>
        <button class="btn btn-primary btn-sm mt-2" onclick="addFollower()">Thêm</button>
      </div>
    </div>
    @endif

    <div class="report-card" style="background: white; border-radius: 10px; padding: 20px; margin-bottom: 20px; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
      <h6 class="mb-3">
        <i class="bi bi-lightning me-2"></i>Hành động
      </h6>
      
      {{-- Nút cho Admin và Manager --}}
      @if(auth()->user()->isAdmin() || auth()->user()->isManager())
        {{-- Nút sửa --}}
        <a href="{{ route('tasks.edit',$task) }}" class="btn btn-warning w-100 mb-2">✏️ Chỉnh sửa</a>
        
        {{-- Nút xóa --}}
        <form action="{{ route('tasks.destroy',$task) }}" method="POST" class="mb-2" data-confirm="Xoá công việc này?">
          @csrf @method('DELETE')
          <button type="submit" class="btn btn-danger w-100">✖ Xoá</button>
        </form>
        
        <hr class="my-3">
      @endif
      
      {{-- Nút cho Assignee/Employee --}}
      @php
        $isAssignee = $task->assignee_id === auth()->id() || 
                     $task->assignees->contains('id', auth()->id()) ||
                     $task->creator_id === auth()->id();
      @endphp
      
      {{-- Nút hoàn thành & gửi duyệt (cho assignee khi task in_progress) --}}
      @if($task->status === 'in_progress' && $isAssignee && !auth()->user()->isAdmin() && !auth()->user()->isManager())
        <a href="{{ route('tasks.updateStatus',[$task,'status'=>'completed']) }}" class="btn btn-success w-100 mb-2">✅ Hoàn thành & gửi duyệt</a>
      @endif
      
      {{-- Nút hoàn thành & gửi duyệt lại (cho assignee khi task bị từ chối) --}}
      @if($task->status === 'rejected' && $isAssignee && !auth()->user()->isAdmin() && !auth()->user()->isManager())
        <a href="{{ route('tasks.updateStatus',[$task,'status'=>'completed']) }}" class="btn btn-success w-100 mb-2">✅ Hoàn thành & gửi duyệt lại</a>
      @endif
      
      {{-- Nút từ chối (chỉ Admin/Manager khi task in_progress) --}}
      @if($task->status === 'in_progress' && (auth()->user()->isAdmin() || auth()->user()->isManager()))
        <a href="{{ route('tasks.updateStatus',[$task,'status'=>'rejected']) }}" class="btn btn-danger w-100 mb-2">✖ Từ chối</a>
      @endif
      
      {{-- Nút kết thúc và từ chối cho Admin/Manager khi task completed --}}
      @if($task->status === 'completed' && (auth()->user()->isAdmin() || auth()->user()->isManager()))
        <a href="{{ route('tasks.updateStatus',[$task,'status'=>'finished']) }}" class="btn btn-success w-100 mb-2">✅ Kết thúc</a>
        <a href="{{ route('tasks.updateStatus',[$task,'status'=>'rejected']) }}" class="btn btn-danger w-100 mb-2">✖ Từ chối</a>
      @endif
      
      {{-- Nút hoàn tác (chỉ Employee, không phải Admin/Manager) --}}
      @if($task->status === 'completed' && $task->canUndo() && !auth()->user()->isAdmin() && !auth()->user()->isManager())
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

    // Load available followers khi trang được load
    loadAvailableFollowers();
});

// Task Follower functions
function loadAvailableFollowers() {
    fetch(`{{ route('tasks.followers.available', $task) }}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const select = document.getElementById('newFollower');
                select.innerHTML = '<option value="">Chọn người dùng...</option>';
                
                data.users.forEach(user => {
                    const option = document.createElement('option');
                    option.value = user.id;
                    option.textContent = `${user.name} (${user.role}) - ${user.department ? user.department.name : 'N/A'}`;
                    select.appendChild(option);
                });
            }
        })
        .catch(error => {
            console.error('Error loading available followers:', error);
        });
}

function addFollower() {
    const select = document.getElementById('newFollower');
    const userId = select.value;
    
    if (!userId) {
        alert('Vui lòng chọn người dùng để thêm.');
        return;
    }
    
    fetch(`{{ route('tasks.followers.add', $task) }}`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({ user_id: userId })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Đã thêm Task Follower thành công!');
            location.reload(); // Reload trang để cập nhật danh sách
        } else {
            alert('Lỗi: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error adding follower:', error);
        alert('Có lỗi xảy ra khi thêm Task Follower.');
    });
}

function removeFollower(userId) {
    if (!confirm('Bạn có chắc muốn xóa người này khỏi danh sách Task Follower?')) {
        return;
    }
    
    fetch(`{{ route('tasks.followers.remove', $task) }}`, {
        method: 'DELETE',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({ user_id: userId })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Đã xóa Task Follower thành công!');
            location.reload(); // Reload trang để cập nhật danh sách
        } else {
            alert('Lỗi: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error removing follower:', error);
        alert('Có lỗi xảy ra khi xóa Task Follower.');
    });
}
</script>
@endsection
