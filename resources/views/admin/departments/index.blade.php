@extends('layouts.master')
@section('title', 'Quản lý phòng ban')
@section('content')
<style>
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

<div class="card mb-4">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Danh sách phòng ban</h5>
        <a href="{{ route('departments.create') }}" class="btn btn-primary">Thêm phòng ban</a>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-striped mb-0">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Tên phòng ban</th>
                        <th>Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($departments as $dept)
                        <tr>
                            <td data-label="#">{{ $loop->iteration }}</td>
                            <td data-label="Tên phòng ban">{{ $dept->name }}</td>
                            <td data-label="Hành động" class="actions">
                                <a href="{{ route('departments.edit', $dept) }}" class="btn btn-sm btn-warning">Sửa</a>
                                <form action="{{ route('departments.destroy', $dept) }}" method="POST" class="d-inline" onsubmit="return confirm('Xóa phòng ban này?');">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-danger">Xóa</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="3" class="text-center">Chưa có phòng ban nào.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="card-footer">
        {{ $departments->links() }}
    </div>
</div>
@endsection
