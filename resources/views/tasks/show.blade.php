@extends('layouts.master')
@section('title', $task->title)

@section('content')
<style>
.task-header-gradient {
    background: linear-gradient(90deg, #6a11cb 0%, #2575fc 100%);
    color: #fff;
    border-radius: 18px;
    padding: 32px 32px 24px 32px;
    margin-bottom: 24px;
    position: relative;
    box-shadow: 0 4px 24px rgba(106,17,203,0.08);
}
.task-header-gradient h2 {
    font-size: 2.2rem;
    font-weight: 700;
    margin-bottom: 12px;
}
.badge-priority {
    font-size: 1rem;
    padding: 6px 16px;
    border-radius: 12px;
    margin-right: 8px;
}
.card-custom {
    border-radius: 16px;
    box-shadow: 0 2px 12px rgba(0,0,0,0.06);
    margin-bottom: 24px;
}
.file-attachment {
    background: #f8f9fa;
    border-radius: 8px;
    padding: 8px 12px;
    margin-bottom: 8px;
    display: flex;
    align-items: center;
    font-size: 1rem;
}
.file-attachment i {
    color: #e83e8c;
    margin-right: 8px;
}
.action-btn {
    font-size: 1rem;
    font-weight: 500;
    margin-bottom: 10px;
    border-radius: 8px;
    padding: 10px 0;
}
.action-btn-green { background: #22c55e; color: #fff; }
.action-btn-blue { background: #2563eb; color: #fff; }
.action-btn-yellow { background: #facc15; color: #fff; }
.action-btn-outline { border: 1px solid #2563eb; color: #2563eb; background: #fff; }
.action-btn:hover { opacity: 0.9; }
.comment-section {
    background: #fff;
    border-radius: 16px;
    box-shadow: 0 2px 12px rgba(0,0,0,0.04);
    padding: 24px;
}
.comment-item {
    border-left: 4px solid #6a11cb;
    margin-bottom: 18px;
    padding-left: 12px;
}
.comment-item strong { color: #2563eb; }
</style>

<div class="task-header-gradient d-flex flex-column flex-md-row align-items-md-center justify-content-between">
    <div>
        <h2 class="mb-2">{{ $task->title }}</h2>
        <span class="badge badge-priority bg-success me-2" style="background:#22c55e;">{{ __("statuses.$task->status") ?? strtoupper($task->status) }}</span>
        <span class="badge badge-priority bg-warning text-dark" style="background:#facc15; color:#333;">Độ ưu tiên: {{ ucfirst($task->priority ?? 'Không rõ') }}</span>
    </div>
    <a href="{{ route('dashboard') }}" class="btn btn-light" style="position:absolute;top:24px;right:32px;">&larr; Quay lại</a>
</div>

<div class="row g-4">
    <div class="col-lg-8">
        <div class="card card-custom p-4 mb-4">
            <h5 class="mb-3"><i class="bi bi-info-circle me-2"></i>Thông tin chung</h5>
            <div class="row mb-2">
                <div class="col-md-6 mb-2"><i class="bi bi-person-badge me-1"></i> <strong>Người giao:</strong> {{ $task->creator->name }}</div>
                <div class="col-md-6 mb-2"><i class="bi bi-calendar-date me-1"></i> <strong>Ngày giao:</strong> {{ $task->created_at->format('d/m/Y') }}</div>
                <div class="col-md-6 mb-2"><i class="bi bi-person me-1"></i> <strong>Người nhận:</strong> {{ $task->assignee?->name ?? '—' }}</div>
                <div class="col-md-6 mb-2"><i class="bi bi-calendar2-week me-1"></i> <strong>Deadline:</strong> {{ $task->deadline? $task->deadline->format('d/m/Y'):'—' }}</div>
                <div class="col-md-6 mb-2"><i class="bi bi-exclamation-triangle me-1"></i> <strong>Độ ưu tiên:</strong> <span class="text-danger">{{ ucfirst($task->priority ?? 'Không rõ') }}</span></div>
                <div class="col-md-6 mb-2"><i class="bi bi-check2-circle me-1"></i> <strong>Trạng thái:</strong> <span class="text-success">{{ __("statuses.$task->status") ?? strtoupper($task->status) }}</span></div>
            </div>
        </div>
        <div class="comment-section mb-4">
            <h5 class="mb-3"><i class="bi bi-chat-dots me-2"></i>Thảo luận</h5>
            <form class="mb-4" action="{{ route('tasks.comment',$task) }}" method="POST">
                @csrf
                <textarea name="content" class="form-control mb-2" rows="3" placeholder="Viết bình luận..."></textarea>
                <button class="btn btn-primary btn-sm">Gửi bình luận</button>
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
        <div class="card card-custom p-4 mb-4">
            <h5 class="mb-3"><i class="bi bi-paperclip me-2"></i>File đính kèm</h5>
            @forelse($task->attachments ?? [] as $file)
                <div class="file-attachment">
                    <i class="bi bi-file-earmark me-2"></i>
                    <a href="{{ $file['url'] }}" target="_blank">{{ $file['name'] }}</a>
                </div>
            @empty
                <div class="text-muted">Chưa có tệp.</div>
            @endforelse
        </div>
        <div class="card card-custom p-4">
            <h5 class="mb-3"><i class="bi bi-lightning me-2"></i>Hành động</h5>
            <a href="{{ route('tasks.updateStatus',[$task,'status'=>'done']) }}" class="btn action-btn action-btn-green w-100 mb-2">✔ Hoàn thành</a>
            <a href="{{ route('tasks.updateStatus',[$task,'status'=>'in_progress']) }}" class="btn action-btn action-btn-blue w-100 mb-2">🔄 Cập nhật trạng thái</a>
            <a href="#" class="btn action-btn action-btn-yellow w-100 mb-2">⚡ Yêu cầu chỉnh sửa</a>
            <a href="{{ route('tasks.history',$task) }}" class="btn action-btn action-btn-outline w-100">👁 Xem lịch sử</a>
        </div>
    </div>
</div>
@endsection
