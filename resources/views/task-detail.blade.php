@extends('layouts.master')
@section('title',$task->title)

@push('styles')
<style>
/* Hiệu ứng đẹp cho trang task detail */
.task-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
    padding: 25px !important;
    border-radius: 15px !important;
    margin-bottom: 25px !important;
    position: relative !important;
    box-shadow: 0 8px 32px rgba(102, 126, 234, 0.3) !important;
    overflow: hidden !important;
}

.task-header::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(45deg, rgba(255,255,255,0.1) 0%, rgba(255,255,255,0) 100%);
    pointer-events: none;
}

.task-header h3 {
    font-weight: 700 !important;
    font-size: 1.8rem !important;
    text-shadow: 0 2px 4px rgba(0,0,0,0.1) !important;
}

.info-card {
    background: white !important;
    border-radius: 15px !important;
    padding: 25px !important;
    margin-bottom: 25px !important;
    box-shadow: 0 4px 20px rgba(0,0,0,0.08) !important;
    border: 1px solid rgba(0,0,0,0.05) !important;
    transition: all 0.3s ease !important;
}

.info-card:hover {
    transform: translateY(-2px) !important;
    box-shadow: 0 8px 30px rgba(0,0,0,0.12) !important;
}

.info-card h6 {
    font-weight: 600 !important;
    color: #495057 !important;
    font-size: 1.1rem !important;
    margin-bottom: 20px !important;
    padding-bottom: 10px !important;
    border-bottom: 2px solid #f8f9fa !important;
}

.info-item {
    display: flex !important;
    align-items: center !important;
    margin-bottom: 15px !important;
    padding: 12px 15px !important;
    background: #f8f9fa !important;
    border-radius: 10px !important;
    transition: all 0.2s ease !important;
}

.info-item:hover {
    background: #e9ecef !important;
    transform: translateX(5px) !important;
}

.info-label {
    font-weight: 600 !important;
    color: #495057 !important;
    min-width: 120px !important;
    margin-right: 15px !important;
    display: flex !important;
    align-items: center !important;
}

.info-label i {
    margin-right: 8px !important;
    color: #6c757d !important;
    width: 16px !important;
}

.info-value {
    font-weight: 500 !important;
    color: #212529 !important;
    flex: 1 !important;
}

.priority-badge {
    padding: 6px 12px !important;
    border-radius: 20px !important;
    font-weight: 600 !important;
    font-size: 0.85rem !important;
    text-transform: uppercase !important;
    letter-spacing: 0.5px !important;
}

.priority-high {
    background: linear-gradient(135deg, #ff6b6b 0%, #ee5a52 100%) !important;
    color: white !important;
}

.priority-medium {
    background: linear-gradient(135deg, #feca57 0%, #ff9ff3 100%) !important;
    color: #2c3e50 !important;
}

.priority-low {
    background: linear-gradient(135deg, #48dbfb 0%, #0abde3 100%) !important;
    color: white !important;
}

.status-badge {
    padding: 8px 16px !important;
    border-radius: 25px !important;
    font-weight: 600 !important;
    font-size: 0.9rem !important;
    text-transform: uppercase !important;
    letter-spacing: 0.5px !important;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1) !important;
}

.status-in-progress {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
    color: white !important;
}

.status-completed {
    background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%) !important;
    color: white !important;
}

.status-finished {
    background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%) !important;
    color: white !important;
}

.status-rejected {
    background: linear-gradient(135deg, #fa709a 0%, #fee140 100%) !important;
    color: white !important;
}

.status-overdue {
    background: linear-gradient(135deg, #ff9a9e 0%, #fecfef 100%) !important;
    color: #2c3e50 !important;
}

.tag-container {
    display: flex !important;
    flex-wrap: wrap !important;
    gap: 8px !important;
    margin-top: 10px !important;
}

.tag {
    padding: 6px 12px !important;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
    color: white !important;
    border-radius: 20px !important;
    font-size: 0.8rem !important;
    font-weight: 500 !important;
    box-shadow: 0 2px 6px rgba(102, 126, 234, 0.3) !important;
    transition: all 0.2s ease !important;
}

.tag:hover {
    transform: translateY(-1px) !important;
    box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4) !important;
}

.action-card {
    background: white !important;
    border-radius: 15px !important;
    padding: 25px !important;
    margin-bottom: 25px !important;
    box-shadow: 0 4px 20px rgba(0,0,0,0.08) !important;
    border: 1px solid rgba(0,0,0,0.05) !important;
}

.action-btn {
    width: 100% !important;
    margin-bottom: 12px !important;
    padding: 12px 20px !important;
    border-radius: 10px !important;
    font-weight: 600 !important;
    font-size: 0.95rem !important;
    transition: all 0.3s ease !important;
    border: none !important;
    text-decoration: none !important;
    display: inline-block !important;
    text-align: center !important;
}

.action-btn:hover {
    transform: translateY(-2px) !important;
    box-shadow: 0 6px 20px rgba(0,0,0,0.15) !important;
    text-decoration: none !important;
}

.btn-edit {
    background: linear-gradient(135deg, #feca57 0%, #ff9ff3 100%) !important;
    color: #2c3e50 !important;
}

.btn-delete {
    background: linear-gradient(135deg, #ff6b6b 0%, #ee5a52 100%) !important;
    color: white !important;
}

.btn-complete {
    background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%) !important;
    color: white !important;
}

.btn-undo {
    background: linear-gradient(135deg, #fa709a 0%, #fee140 100%) !important;
    color: white !important;
}

.btn-reload {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
    color: white !important;
}

.btn-history {
    background: linear-gradient(135deg, #48dbfb 0%, #0abde3 100%) !important;
    color: white !important;
}

/* Nút Quay lại đẹp */
.back-btn {
    background: white !important;
    color: #495057 !important;
    padding: 12px 20px !important;
    border-radius: 12px !important;
    font-weight: 600 !important;
    font-size: 0.95rem !important;
    text-decoration: none !important;
    display: inline-flex !important;
    align-items: center !important;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1) !important;
    border: 2px solid rgba(255,255,255,0.2) !important;
    transition: all 0.3s ease !important;
    backdrop-filter: blur(10px) !important;
}

.back-btn:hover {
    transform: translateY(-2px) !important;
    box-shadow: 0 6px 20px rgba(0,0,0,0.15) !important;
    background: rgba(255,255,255,0.95) !important;
    color: #495057 !important;
    text-decoration: none !important;
}

.back-btn i {
    font-size: 1.1rem !important;
    margin-right: 8px !important;
}

/* Responsive */
@media (max-width: 768px) {
    .task-header {
        padding: 20px !important;
        border-radius: 12px !important;
        margin-bottom: 20px !important;
    }
    
    .task-header h3 {
        font-size: 1.5rem !important;
        margin-bottom: 15px !important;
    }
    
    .task-header .d-flex {
        flex-direction: column !important;
        align-items: flex-start !important;
        gap: 15px !important;
    }
    
    .task-header .d-flex .d-flex {
        flex-direction: column !important;
        gap: 10px !important;
        width: 100% !important;
    }
    
    .status-badge, .priority-badge {
        font-size: 0.8rem !important;
        padding: 6px 12px !important;
        border-radius: 20px !important;
    }
    
    .back-btn {
        padding: 10px 16px !important;
        font-size: 0.9rem !important;
        border-radius: 10px !important;
        align-self: flex-end !important;
        margin-top: 10px !important;
    }
    
    .back-btn i {
        font-size: 1rem !important;
        margin-right: 6px !important;
    }
    
    .info-card, .action-card {
        padding: 20px !important;
        border-radius: 12px !important;
        margin-bottom: 20px !important;
        box-shadow: 0 2px 15px rgba(0,0,0,0.08) !important;
    }
    
    .info-card h6, .action-card h6 {
        font-size: 1rem !important;
        margin-bottom: 15px !important;
        padding-bottom: 8px !important;
    }
    
    .info-item {
        flex-direction: column !important;
        align-items: flex-start !important;
        padding: 12px !important;
        margin-bottom: 10px !important;
        border-radius: 8px !important;
    }
    
    .info-label {
        min-width: auto !important;
        margin-right: 0 !important;
        margin-bottom: 6px !important;
        font-size: 0.9rem !important;
    }
    
    .info-value {
        font-size: 0.95rem !important;
        width: 100% !important;
    }
    
    .tag-container {
        gap: 6px !important;
        margin-top: 8px !important;
    }
    
    .tag {
        font-size: 0.75rem !important;
        padding: 4px 8px !important;
        border-radius: 15px !important;
    }
    
    .action-btn {
        padding: 12px 16px !important;
        font-size: 0.9rem !important;
        border-radius: 8px !important;
        margin-bottom: 10px !important;
    }
    
    /* Cải thiện layout cho mobile */
    .row.g-4 {
        margin: 0 !important;
    }
    
    .col-lg-8, .col-lg-4 {
        padding: 0 10px !important;
    }
    
    /* File upload section */
    .file-upload-section {
        background: #f8f9fa !important;
        padding: 15px !important;
        border-radius: 8px !important;
        margin-bottom: 15px !important;
        border: 2px dashed #dee2e6 !important;
    }
    
    .file-upload-section:hover {
        border-color: #007bff !important;
        background: #e3f2fd !important;
    }
    
    /* Comment section improvements */
    .comment-section {
        background: #f8f9fa !important;
        padding: 15px !important;
        border-radius: 8px !important;
        margin-bottom: 15px !important;
    }
    
    .comment-item {
        background: white !important;
        padding: 12px !important;
        border-radius: 8px !important;
        margin-bottom: 10px !important;
        border-left: 3px solid #007bff !important;
    }
}

@media (max-width: 576px) {
    .task-header {
        padding: 15px !important;
        border-radius: 10px !important;
        margin-bottom: 15px !important;
    }
    
    .task-header h3 {
        font-size: 1.3rem !important;
        margin-bottom: 12px !important;
        line-height: 1.3 !important;
    }
    
    .task-header .d-flex {
        gap: 12px !important;
    }
    
    .task-header .d-flex .d-flex {
        gap: 8px !important;
    }
    
    .status-badge, .priority-badge {
        font-size: 0.75rem !important;
        padding: 5px 10px !important;
        border-radius: 18px !important;
        white-space: nowrap !important;
    }
    
    .back-btn {
        padding: 8px 14px !important;
        font-size: 0.85rem !important;
        border-radius: 8px !important;
        align-self: flex-end !important;
        margin-top: 8px !important;
    }
    
    .back-btn i {
        font-size: 0.9rem !important;
        margin-right: 4px !important;
    }
    
    .info-card, .action-card {
        padding: 15px !important;
        border-radius: 10px !important;
        margin-bottom: 15px !important;
        box-shadow: 0 1px 10px rgba(0,0,0,0.06) !important;
    }
    
    .info-card h6, .action-card h6 {
        font-size: 0.95rem !important;
        margin-bottom: 12px !important;
        padding-bottom: 6px !important;
    }
    
    .info-item {
        padding: 10px !important;
        margin-bottom: 8px !important;
        border-radius: 6px !important;
    }
    
    .info-label {
        font-size: 0.85rem !important;
        margin-bottom: 4px !important;
    }
    
    .info-label i {
        font-size: 0.9rem !important;
        margin-right: 6px !important;
    }
    
    .info-value {
        font-size: 0.9rem !important;
    }
    
    .tag-container {
        gap: 4px !important;
        margin-top: 6px !important;
    }
    
    .tag {
        font-size: 0.7rem !important;
        padding: 3px 6px !important;
        border-radius: 12px !important;
    }
    
    .action-btn {
        padding: 10px 14px !important;
        font-size: 0.85rem !important;
        border-radius: 6px !important;
        margin-bottom: 8px !important;
    }
    
    /* Layout improvements for small mobile */
    .col-lg-8, .col-lg-4 {
        padding: 0 5px !important;
    }
    
    .row.g-4 {
        margin: 0 -5px !important;
    }
    
    /* Alert improvements */
    .alert {
        padding: 10px 12px !important;
        border-radius: 6px !important;
        font-size: 0.85rem !important;
        margin-bottom: 10px !important;
    }
    
    /* Form improvements */
    .form-control {
        border-radius: 6px !important;
        font-size: 0.9rem !important;
        padding: 8px 12px !important;
    }
    
    .btn {
        border-radius: 6px !important;
        font-size: 0.85rem !important;
        padding: 8px 16px !important;
    }
    
    /* Dropdown improvements */
    .dropdown-menu {
        border-radius: 8px !important;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1) !important;
    }
    
    .dropdown-item {
        padding: 8px 16px !important;
        font-size: 0.9rem !important;
    }
}

/* Tablet specific improvements */
@media (min-width: 769px) and (max-width: 991px) {
    .task-header {
        padding: 22px !important;
        border-radius: 13px !important;
    }
    
    .task-header h3 {
        font-size: 1.6rem !important;
    }
    
    .info-card, .action-card {
        padding: 22px !important;
        border-radius: 13px !important;
    }
    
    .info-item {
        padding: 14px 16px !important;
    }
    
    .action-btn {
        padding: 14px 18px !important;
        font-size: 0.95rem !important;
    }
}

/* Large screen improvements */
@media (min-width: 992px) {
    .task-header {
        padding: 30px !important;
        border-radius: 18px !important;
    }
    
    .task-header h3 {
        font-size: 2rem !important;
    }
    
    .info-card, .action-card {
        padding: 30px !important;
        border-radius: 18px !important;
    }
    
    .info-item {
        padding: 16px 20px !important;
    }
    
    .action-btn {
        padding: 16px 24px !important;
        font-size: 1rem !important;
    }
}
</style>
@endpush

@section('content')
<div class="task-header">
  <div class="d-flex justify-content-between align-items-center">
    <div>
      <h3 class="mb-3 text-white">{{ $task->title }}</h3>
      <div class="d-flex gap-3">
        <span class="status-badge status-{{ $task->status }}">
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
        <span class="priority-badge priority-{{ $task->priority ?? 'medium' }}">
          Độ ưu tiên: 
          @if($task->priority == 'high')
            Cao
          @elseif($task->priority == 'medium')
            Trung bình
          @elseif($task->priority == 'low')
            Thấp
          @else
            Trung bình
          @endif
        </span>
      </div>
    </div>
    <a href="{{ route('dashboard') }}" class="back-btn">
      <i class="bi bi-arrow-left me-2"></i>Quay lại
    </a>
  </div>
</div>

<div class="row g-4">
  <div class="col-lg-8">
    <div class="info-card">
      <h6>
        <i class="bi bi-info-circle me-2"></i>Thông tin chung
      </h6>
      <div class="row">
        <div class="col-md-6">
          <div class="info-item">
            <div class="info-label">
              <i class="bi bi-telephone"></i>Người giao:
            </div>
            <div class="info-value">{{ $task->creator->name }}</div>
          </div>
        </div>
        <div class="col-md-6">
          <div class="info-item">
            <div class="info-label">
              <i class="bi bi-person"></i>Người nhận:
            </div>
            <div class="info-value">
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
            </div>
          </div>
        </div>
        <div class="col-md-6">
          <div class="info-item">
            <div class="info-label">
              <i class="bi bi-exclamation-triangle"></i>Độ ưu tiên:
            </div>
            <div class="info-value">
              @if($task->priority == 'high')
                Cao
              @elseif($task->priority == 'medium')
                Trung bình
              @elseif($task->priority == 'low')
                Thấp
              @else
                Trung bình
              @endif
            </div>
          </div>
        </div>
        <div class="col-md-6">
          <div class="info-item">
            <div class="info-label">
              <i class="bi bi-calendar"></i>Ngày giao:
            </div>
            <div class="info-value">{{ $task->created_at? $task->created_at->format('d/m/Y'):'—' }}</div>
          </div>
        </div>
        <div class="col-md-6">
          <div class="info-item">
            <div class="info-label">
              <i class="bi bi-calendar-check"></i>Deadline:
            </div>
            <div class="info-value">{{ $task->deadline? $task->deadline->format('d/m/Y'):'—' }}</div>
          </div>
        </div>
        <div class="col-md-6">
          <div class="info-item">
            <div class="info-label">
              <i class="bi bi-building"></i>Phòng ban:
            </div>
            <div class="info-value">
              @if($task->is_multi_department && $task->departments->count() > 0)
                {{ $task->departments->count() }} phòng ban: {{ $task->departments->pluck('name')->join(', ') }}
              @elseif($task->department)
                {{ $task->department->name }}
              @else
                —
              @endif
            </div>
          </div>
        </div>
        <div class="col-md-6">
          <div class="info-item">
            <div class="info-label">
              <i class="bi bi-check-circle"></i>Trạng thái:
            </div>
            <div class="info-value">
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
            </div>
          </div>
        </div>
        <div class="col-12">
          <div class="info-item">
            <div class="info-label">
              <i class="bi bi-people"></i>Task Followers:
            </div>
            <div class="info-value">
              @if($task->followers->count() > 0)
                {{ $task->followers->count() }} người: {{ $task->followers->pluck('name')->join(', ') }}
              @else
                Chưa có người theo dõi
              @endif
            </div>
          </div>
        </div>
        
        {{-- Tags/Categories --}}
        @if($task->tags && $task->tags->count() > 0)
        <div class="col-12">
          <div class="info-item">
            <div class="info-label">
              <i class="bi bi-tags"></i>Tags:
            </div>
            <div class="tag-container">
              @foreach($task->tags as $tag)
                <span class="tag">{{ $tag->name }}</span>
              @endforeach
            </div>
          </div>
        </div>
        @endif
        
        {{-- Thông tin task recurring --}}
        @if($task->is_recurring && $task->recurring_days)
          <div class="col-12">
            <div class="alert alert-info" style="background: linear-gradient(135deg, #e3f2fd 0%, #bbdefb 100%); border: 1px solid #2196f3; color: #1976d2; border-radius: 10px;">
              <i class="bi bi-arrow-repeat me-2"></i>
              <strong>Lặp lại:</strong> Công việc sẽ lặp lại mỗi {{ $task->recurring_days }} ngày từ ngày {{ $task->recurring_start_date ? $task->recurring_start_date->format('d/m/Y') : $task->created_at->format('d/m/Y') }}
            </div>
          </div>
        @endif
      </div>
    </div>

    <div class="info-card">
      <h6>
        <i class="bi bi-chat-dots me-2"></i>Thảo luận
      </h6>
      <form class="mb-4" action="{{ route('tasks.comment',$task) }}" method="POST" id="commentForm">
        @csrf
        <textarea name="content" class="form-control mb-3" rows="3" placeholder="Viết bình luận..." id="commentTextarea" style="border-radius: 10px; border: 1px solid #e9ecef;"></textarea>
        <div id="commentError" class="text-danger mb-2" style="display: none;">
          <i class="bi bi-exclamation-triangle me-1"></i>
          Không được phép nhập từ dài hơn 45 ký tự!
        </div>
        <button class="btn btn-primary btn-sm" id="commentSubmitBtn" style="border-radius: 8px; padding: 8px 20px;">Gửi bình luận</button>
      </form>

      @forelse($task->activities as $act)
        <div class="comment-item" style="background: #f8f9fa; padding: 15px; border-radius: 10px; margin-bottom: 15px;">
          <strong>{{ $act->user->name }}</strong>
          <small class="text-muted ms-2">{{ $act->created_at->diffForHumans() }}</small>
          <div class="mt-2">
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
        <div class="text-muted text-center py-4">Chưa có bình luận.</div>
      @endforelse
    </div>
  </div>

  <div class="col-lg-4">
    <div class="info-card">
      <h6>
        <i class="bi bi-paperclip me-2"></i>File đính kèm
      </h6>
      @forelse($task->attachments ?? [] as $file)
        <div class="file-attachment" style="background: #f8f9fa; padding: 10px; border-radius: 8px; margin-bottom: 10px;">
          📎 <a href="{{ $file['url'] }}" target="_blank">{{ $file['name'] }}</a>
        </div>
      @empty
        <div class="text-muted text-center py-4">Chưa có tệp.</div>
      @endforelse
    </div>

    {{-- Task Followers Management (chỉ Admin và Manager) --}}
    @if(auth()->user()->isAdmin() || auth()->user()->isManager())
    <div class="info-card">
      <h6>
        <i class="bi bi-people me-2"></i>Quản lý Task Followers
      </h6>
      
      {{-- Danh sách followers hiện tại --}}
      <div class="mb-3">
        <strong>Người đang theo dõi:</strong>
        <div id="currentFollowers">
          @if($task->followers->count() > 0)
            @foreach($task->followers as $follower)
              <div class="d-flex justify-content-between align-items-center mb-2 p-2" style="background: #f8f9fa; border-radius: 8px;">
                <span>{{ $follower->name }} ({{ $follower->role }})</span>
                <button class="btn btn-sm btn-outline-danger" onclick="removeFollower({{ $follower->id }})" style="border-radius: 6px;">
                  <i class="bi bi-x"></i>
                </button>
              </div>
            @endforeach
          @else
            <div class="text-muted text-center py-3">Chưa có người theo dõi</div>
          @endif
        </div>
      </div>
      
      {{-- Thêm follower mới --}}
      <div class="mb-3">
        <label for="newFollower" class="form-label">Thêm người theo dõi:</label>
        <select class="form-select mb-2" id="newFollower" style="border-radius: 8px;">
          <option value="">Chọn người dùng...</option>
        </select>
        <button class="btn btn-primary btn-sm" onclick="addFollower()" style="border-radius: 8px; padding: 8px 20px;">Thêm</button>
      </div>
    </div>
    @endif

    <div class="action-card">
      <h6>
        <i class="bi bi-lightning me-2"></i>Hành động
      </h6>
      
      {{-- Nút cho Admin và Manager --}}
      @if(auth()->user()->isAdmin() || auth()->user()->isManager())
        {{-- Nút sửa --}}
        <a href="{{ route('tasks.edit',$task) }}" class="action-btn btn-edit">✏️ Chỉnh sửa</a>
        
        {{-- Nút xóa --}}
        <form action="{{ route('tasks.destroy',$task) }}" method="POST" class="mb-2" data-confirm="Xoá công việc này?">
          @csrf @method('DELETE')
          <button type="submit" class="action-btn btn-delete">✖ Xoá</button>
        </form>
        
        <hr class="my-4">
      @endif
      
      {{-- Nút cho Assignee/Employee --}}
      @php
        $isAssignee = $task->assignee_id === auth()->id() || 
                     $task->assignees->contains('id', auth()->id()) ||
                     $task->creator_id === auth()->id();
      @endphp
      
      {{-- Nút hoàn thành & gửi duyệt (cho assignee khi task in_progress) --}}
      @if($task->status === 'in_progress' && $isAssignee && !auth()->user()->isAdmin() && !auth()->user()->isManager())
        <a href="{{ route('tasks.updateStatus',[$task,'status'=>'completed']) }}" class="action-btn btn-complete">✅ Hoàn thành & gửi duyệt</a>
      @endif
      
      {{-- Nút hoàn thành & gửi duyệt lại (cho assignee khi task bị từ chối) --}}
      @if($task->status === 'rejected' && $isAssignee && !auth()->user()->isAdmin() && !auth()->user()->isManager())
        <a href="{{ route('tasks.updateStatus',[$task,'status'=>'completed']) }}" class="action-btn btn-complete">✅ Hoàn thành & gửi duyệt lại</a>
      @endif
      
      {{-- Nút hoàn thành sớm (chỉ Admin/Manager khi task in_progress) --}}
      @if($task->status === 'in_progress' && (auth()->user()->isAdmin() || auth()->user()->isManager()))
        <a href="{{ route('tasks.updateStatus',[$task,'status'=>'finished']) }}" class="action-btn btn-complete">✅ Hoàn thành sớm</a>
      @endif
      
      {{-- Nút kết thúc cho Admin/Manager khi task completed --}}
      @if($task->status === 'completed' && (auth()->user()->isAdmin() || auth()->user()->isManager()))
        <a href="{{ route('tasks.updateStatus',[$task,'status'=>'finished']) }}" class="action-btn btn-complete">✅ Kết thúc</a>
      @endif
      
      {{-- Nút hoàn tác (chỉ Employee, không phải Admin/Manager) --}}
      @if($task->status === 'completed' && $task->canUndo() && !auth()->user()->isAdmin() && !auth()->user()->isManager())
        <form action="{{ route('tasks.undoCompletion', $task) }}" method="POST" class="mb-2">
          @csrf
          <button type="submit" class="action-btn btn-undo" onclick="return confirm('Bạn có chắc muốn hoàn tác công việc này?')">
            Hoàn tác ({{ 3 - $task->completed_at->diffInHours(now()) }}h còn lại)
          </button>
        </form>
      @endif
      
      {{-- Nút cập nhật trạng thái --}}
      @if($task->status === 'in_progress')
        <a href="{{ route('tasks.updateStatus',[$task,'status'=>'in_progress']) }}" class="action-btn btn-reload">🔄 Load lại</a>
      @endif
      
      {{-- Nút xem lịch sử --}}
      <a href="{{ route('tasks.history',$task) }}" class="action-btn btn-history">👁 Xem lịch sử</a>
      
      {{-- Thông báo không thể hoàn tác --}}
      @if($task->status === 'completed' && !$task->canUndo())
        <div class="alert alert-warning mt-3" style="border-radius: 10px;">
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
