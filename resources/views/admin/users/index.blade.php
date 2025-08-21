@extends('layouts.master')

@section('title', 'Quản lý nhân viên')

@section('content')
<style>
.card-header {
    background: linear-gradient(90deg, #558EC1 0%, #5DA444 100%);
    color: #fff;
    border-bottom: none;
}
.card-header h5 {
    color: #fff;
}

/* Table styling */
.table thead th {
    background: rgba(85, 142, 193, 0.1);
    border-bottom: 2px solid #558EC1;
    color: #374151;
    font-weight: 600;
}
.table tbody tr:hover {
    background: rgba(85, 142, 193, 0.05);
}

/* Responsive table */
@media (max-width: 768px) {
    .table-responsive {
        border: 0;
    }
    
    .table-responsive .table {
        margin-bottom: 0;
    }
    
    .table-responsive .table thead {
        display: none;
    }
    
    .table-responsive .table tbody tr {
        display: block;
        margin-bottom: 1rem;
        border: 1px solid #dee2e6;
        border-radius: 8px;
        background: #fff;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    
    .table-responsive .table tbody td {
        display: block;
        text-align: left;
        padding: 0.75rem;
        border: none;
        border-bottom: 1px solid #f8f9fa;
    }
    
    .table-responsive .table tbody td:last-child {
        border-bottom: none;
    }
    
    .table-responsive .table tbody td:before {
        content: attr(data-label) ": ";
        font-weight: 600;
        color: #558EC1;
        min-width: 100px;
        display: inline-block;
    }
    
    .table-responsive .table tbody td.actions {
        text-align: center;
        padding: 1rem;
    }
    
    .table-responsive .table tbody td.actions:before {
        display: none;
    }
    
    .table-responsive .table tbody td.actions .btn {
        margin: 0 0.25rem;
        min-width: 60px;
    }
}

/* Mobile header adjustments */
@media (max-width: 576px) {
    .card-header {
        flex-direction: column;
        gap: 1rem;
        text-align: center;
    }
    
    .card-header h5 {
        margin-bottom: 0;
    }
    
    .card-header .btn {
        width: 100%;
        max-width: 200px;
    }
}
</style>
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Danh sách nhân viên</h5>
        <a href="{{ route('users.create') }}" class="btn" style="background:#558EC1; color:#fff; border-color:#558EC1;">Thêm nhân viên</a>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-striped mb-0">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Tên</th>
                        <th>Email</th>
                        <th>Số điện thoại</th>
                        <th>Vai trò</th>
                        <th>Phòng ban</th>
                        <th>Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                        <tr>
                            <td data-label="#">{{ $loop->iteration }}</td>
                            <td data-label="Tên">{{ $user->name }}</td>
                            <td data-label="Email">{{ $user->email ?? '-' }}</td>
                            <td data-label="Số điện thoại">{{ $user->phone ?? '-' }}</td>
                            <td data-label="Vai trò">{{ $user->role }}</td>
                            <td data-label="Phòng ban">{{ $user->department->name ?? '-' }}</td>
                            <td data-label="Hành động" class="actions">
                                <a href="{{ route('users.edit', $user) }}" class="btn btn-sm" style="background:#facc15; color:#333; border-color:#facc15;">Sửa</a>
                                <form action="{{ route('users.destroy', $user) }}" method="POST" class="d-inline" onsubmit="return confirm('Bạn có chắc muốn xóa?');">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm" style="background:#dc2626; color:#fff; border-color:#dc2626;">Xóa</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center">Chưa có nhân viên nào.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="card-footer">
        {{ $users->links() }}
    </div>
</div>
@endsection
