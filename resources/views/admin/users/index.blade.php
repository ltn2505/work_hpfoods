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

/* Stats cards */
.stats-container {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1rem;
    margin-bottom: 1.5rem;
}

.stat-card {
    background: white;
    border-radius: 12px;
    padding: 1.5rem;
    text-align: center;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    border: 1px solid #e9ecef;
}

.stat-card .stat-number {
    font-size: 2rem;
    font-weight: bold;
    margin-bottom: 0.5rem;
}

.stat-card .stat-label {
    color: #6c757d;
    font-size: 0.875rem;
}

.stat-card.admin { border-left: 4px solid #dc3545; }
.stat-card.manager { border-left: 4px solid #fd7e14; }
.stat-card.employee { border-left: 4px solid #198754; }
.stat-card.total { border-left: 4px solid #0d6efd; }

/* Search and filter form */
.search-filter-form {
    background: white;
    border-radius: 12px;
    padding: 1.5rem;
    margin-bottom: 1.5rem;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    border: 1px solid #e9ecef;
}

.search-filter-form .row {
    align-items: end;
}

.search-filter-form .form-group {
    margin-bottom: 1rem;
}

.search-filter-form .form-label {
    font-weight: 600;
    color: #495057;
    margin-bottom: 0.5rem;
}

.search-filter-form .form-control,
.search-filter-form .form-select {
    border-radius: 8px;
    border: 1px solid #ced4da;
    padding: 0.5rem 0.75rem;
}

.search-filter-form .form-control:focus,
.search-filter-form .form-select:focus {
    border-color: #558EC1;
    box-shadow: 0 0 0 0.2rem rgba(85, 142, 193, 0.25);
}

/* Table styling */
.table thead th {
    background: rgba(85, 142, 193, 0.1);
    border-bottom: 2px solid #558EC1;
    color: #374151;
    font-weight: 600;
    white-space: nowrap;
    min-width: 120px;
    cursor: pointer;
    transition: background-color 0.2s;
}

.table thead th:hover {
    background: rgba(85, 142, 193, 0.2);
}

.table thead th.sortable::after {
    content: '↕';
    margin-left: 0.5rem;
    opacity: 0.5;
}

.table thead th.sort-asc::after {
    content: '↑';
    opacity: 1;
    color: #558EC1;
}

.table thead th.sort-desc::after {
    content: '↓';
    opacity: 1;
    color: #558EC1;
}

.table tbody tr:hover {
    background: rgba(85, 142, 193, 0.05);
}

/* Fix table overflow issues */
.table-responsive {
    overflow-x: auto;
    -webkit-overflow-scrolling: touch;
}

.table {
    min-width: 800px;
    margin-bottom: 0;
}

/* Ensure proper spacing for action buttons */
.table tbody td.actions {
    white-space: nowrap;
    min-width: 150px;
}

.table tbody td.actions .btn {
    margin: 0 2px;
    white-space: nowrap;
}

/* Pagination info */
.pagination-info {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1rem;
    flex-wrap: wrap;
    gap: 1rem;
}

.pagination-info .per-page-selector {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.pagination-info .per-page-selector select {
    border-radius: 6px;
    border: 1px solid #ced4da;
    padding: 0.25rem 0.5rem;
}

/* Responsive table for mobile */
@media (max-width: 768px) {
    .stats-container {
        grid-template-columns: repeat(2, 1fr);
        gap: 0.75rem;
    }
    
    .stat-card {
        padding: 1rem;
    }
    
    .stat-card .stat-number {
        font-size: 1.5rem;
    }
    
    .search-filter-form {
        padding: 1rem;
    }
    
    .search-filter-form .row > div {
        margin-bottom: 1rem;
    }
    
    .pagination-info {
        flex-direction: column;
        align-items: stretch;
        text-align: center;
    }
    
    .table-responsive {
        border: 0;
        margin: 0 -15px;
    }
    
    .table-responsive .table {
        margin-bottom: 0;
        min-width: auto;
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
        padding: 1rem;
    }
    
    .table-responsive .table tbody td {
        display: block;
        text-align: left;
        padding: 0.5rem 0;
        border: none;
        border-bottom: 1px solid #f8f9fa;
        position: relative;
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
        margin-right: 0.5rem;
    }
    
    .table-responsive .table tbody td.actions {
        text-align: center;
        padding: 1rem 0 0 0;
        display: flex;
        gap: 0.5rem;
        justify-content: center;
        flex-wrap: wrap;
    }
    
    .table-responsive .table tbody td.actions:before {
        display: none;
    }
    
    .table-responsive .table tbody td.actions .btn {
        margin: 0;
        min-width: 80px;
        flex: 1;
        max-width: 120px;
    }
    
    /* Fix card header on mobile */
    .card-header {
        flex-direction: column;
        gap: 1rem;
        text-align: center;
        padding: 1rem;
    }
    
    .card-header h5 {
        margin-bottom: 0;
        font-size: 1.1rem;
    }
    
    .card-header .btn {
        width: 100%;
        max-width: 200px;
        margin: 0 auto;
    }
}

/* Extra small devices */
@media (max-width: 576px) {
    .stats-container {
        grid-template-columns: 1fr;
    }
    
    .table-responsive .table tbody td.actions {
        flex-direction: column;
        align-items: stretch;
    }
    
    .table-responsive .table tbody td.actions .btn {
        max-width: none;
        width: 100%;
    }
    
    .card-body {
        padding: 0.5rem;
    }
    
    .table-responsive {
        margin: 0 -0.5rem;
    }
}

/* Fix pagination on mobile */
@media (max-width: 768px) {
    .card-footer {
        padding: 1rem;
        text-align: center;
    }
    
    .pagination {
        justify-content: center;
        flex-wrap: wrap;
        gap: 0.25rem;
    }
    
    .pagination .page-link {
        padding: 0.5rem 0.75rem;
        font-size: 0.875rem;
    }
}

/* Ensure proper table cell alignment */
.table td, .table th {
    vertical-align: middle;
    padding: 0.75rem;
}

/* Fix long text in table cells */
.table td {
    max-width: 200px;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}

.table td.name-cell {
    max-width: 150px;
}

.table td.email-cell {
    max-width: 250px;
}

.table td.phone-cell {
    max-width: 120px;
}

.table td.role-cell {
    max-width: 100px;
}

.table td.department-cell {
    max-width: 120px;
}
</style>

<!-- Stats Cards -->
<div class="stats-container">
    <div class="stat-card total">
        <div class="stat-number">{{ $stats['total'] }}</div>
        <div class="stat-label">Tổng nhân viên</div>
    </div>
    <div class="stat-card admin">
        <div class="stat-number">{{ $stats['admin'] }}</div>
        <div class="stat-label">Quản trị viên</div>
    </div>
    <div class="stat-card manager">
        <div class="stat-number">{{ $stats['manager'] }}</div>
        <div class="stat-label">Quản lý</div>
    </div>
    <div class="stat-card employee">
        <div class="stat-number">{{ $stats['employee'] }}</div>
        <div class="stat-label">Nhân viên</div>
    </div>
</div>

<!-- Search and Filter Form -->
<div class="search-filter-form">
    <form method="GET" action="{{ route('users.index') }}" id="searchForm">
        <div class="row">
            <div class="col-md-3">
                <div class="form-group">
                    <label for="search" class="form-label">Tìm kiếm</label>
                    <input type="text" class="form-control" id="search" name="search" 
                           value="{{ $search }}" placeholder="Tên, email, số điện thoại...">
                </div>
            </div>
            @if(auth()->user()->isAdmin())
            <div class="col-md-2">
                <div class="form-group">
                    <label for="department" class="form-label">Phòng ban</label>
                    <select class="form-select" id="department" name="department">
                        <option value="">Tất cả</option>
                        @foreach($departments as $dept)
                            <option value="{{ $dept->id }}" {{ $department == $dept->id ? 'selected' : '' }}>
                                {{ $dept->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
            @endif
            <div class="col-md-2">
                <div class="form-group">
                    <label for="role" class="form-label">Vai trò</label>
                    <select class="form-select" id="role" name="role">
                        <option value="">Tất cả</option>
                        <option value="admin" {{ $role == 'admin' ? 'selected' : '' }}>Quản trị viên</option>
                        <option value="manager" {{ $role == 'manager' ? 'selected' : '' }}>Quản lý</option>
                        <option value="employee" {{ $role == 'employee' ? 'selected' : '' }}>Nhân viên</option>
                    </select>
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-group">
                    <label for="per_page" class="form-label">Hiển thị</label>
                    <select class="form-select" id="per_page" name="per_page">
                        <option value="10" {{ $perPage == 10 ? 'selected' : '' }}>10</option>
                        <option value="15" {{ $perPage == 15 ? 'selected' : '' }}>15</option>
                        <option value="25" {{ $perPage == 25 ? 'selected' : '' }}>25</option>
                        <option value="50" {{ $perPage == 50 ? 'selected' : '' }}>50</option>
                    </select>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label class="form-label">&nbsp;</label>
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary flex-fill">
                            <i class="bi bi-search"></i> Tìm kiếm
                        </button>
                        <a href="{{ route('users.index') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-clockwise"></i> Làm mới
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Danh sách nhân viên</h5>
        <a href="{{ route('users.create') }}" class="btn" style="background:#558EC1; color:#fff; border-color:#558EC1;">
            <i class="bi bi-plus-circle"></i> Thêm nhân viên
        </a>
    </div>
    <div class="card-body p-0">
        <!-- Pagination Info -->
        <div class="pagination-info">
            <div>
                Hiển thị {{ $users->firstItem() ?? 0 }} đến {{ $users->lastItem() ?? 0 }} 
                trong tổng số {{ $users->total() }} kết quả
            </div>
            <div class="per-page-selector">
                <label for="per_page_selector">Hiển thị:</label>
                <select id="per_page_selector" onchange="changePerPage(this.value)">
                    <option value="10" {{ $perPage == 10 ? 'selected' : '' }}>10</option>
                    <option value="15" {{ $perPage == 15 ? 'selected' : '' }}>15</option>
                    <option value="25" {{ $perPage == 25 ? 'selected' : '' }}>25</option>
                    <option value="50" {{ $perPage == 50 ? 'selected' : '' }}>50</option>
                </select>
            </div>
        </div>
        
        <div class="table-responsive">
            <table class="table table-striped mb-0">
                <thead>
                    <tr>
                        <th class="sortable {{ $sortBy == 'created_at' ? ($sortOrder == 'asc' ? 'sort-asc' : 'sort-desc') : '' }}" 
                            onclick="sortTable('created_at')">#</th>
                        <th class="sortable {{ $sortBy == 'name' ? ($sortOrder == 'asc' ? 'sort-asc' : 'sort-desc') : '' }}" 
                            onclick="sortTable('name')">Tên</th>
                        <th class="sortable {{ $sortBy == 'email' ? ($sortOrder == 'asc' ? 'sort-asc' : 'sort-desc') : '' }}" 
                            onclick="sortTable('email')">Email</th>
                        <th>Số điện thoại</th>
                        <th class="sortable {{ $sortBy == 'role' ? ($sortOrder == 'asc' ? 'sort-asc' : 'sort-desc') : '' }}" 
                            onclick="sortTable('role')">Vai trò</th>
                        <th class="sortable {{ $sortBy == 'department_id' ? ($sortOrder == 'asc' ? 'sort-asc' : 'sort-desc') : '' }}" 
                            onclick="sortTable('department_id')">Phòng ban</th>
                        <th>Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                        <tr>
                            <td data-label="#">{{ $loop->iteration }}</td>
                            <td data-label="Tên" class="name-cell">{{ $user->name }}</td>
                            <td data-label="Email" class="email-cell">{{ $user->email ?? '-' }}</td>
                            <td data-label="Số điện thoại" class="phone-cell">{{ $user->phone ?? '-' }}</td>
                            <td data-label="Vai trò" class="role-cell">
                                <span class="badge bg-{{ $user->role == 'admin' ? 'danger' : ($user->role == 'manager' ? 'warning' : 'success') }}">
                                    {{ $user->role == 'admin' ? 'Quản trị viên' : ($user->role == 'manager' ? 'Quản lý' : 'Nhân viên') }}
                                </span>
                            </td>
                            <td data-label="Phòng ban" class="department-cell">{{ $user->department->name ?? '-' }}</td>
                            <td data-label="Hành động" class="actions">
                                <a href="{{ route('users.edit', $user) }}" class="btn btn-sm" style="background:#facc15; color:#333; border-color:#facc15;">
                                    <i class="bi bi-pencil"></i> Sửa
                                </a>
                                <form action="{{ route('users.destroy', $user) }}" method="POST" class="d-inline" onsubmit="return confirm('Bạn có chắc muốn xóa?');">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm" style="background:#dc2626; color:#fff; border-color:#dc2626;">
                                        <i class="bi bi-trash"></i> Xóa
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-4">
                                <div class="text-muted">
                                    <i class="bi bi-search" style="font-size: 2rem;"></i>
                                    <p class="mt-2">Không tìm thấy nhân viên nào phù hợp với tiêu chí tìm kiếm.</p>
                                </div>
                            </td>
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

<script>
// Fix table responsive issues on mobile
document.addEventListener('DOMContentLoaded', function() {
    const table = document.querySelector('.table-responsive');
    if (table) {
        // Add horizontal scroll indicator
        table.addEventListener('scroll', function() {
            const isAtStart = this.scrollLeft === 0;
            const isAtEnd = this.scrollLeft + this.clientWidth >= this.scrollWidth;
            
            // Add visual indicators for scrollable content
            if (!isAtStart || !isAtEnd) {
                this.style.boxShadow = '0 0 10px rgba(0,0,0,0.1)';
            } else {
                this.style.boxShadow = 'none';
            }
        });
        
        // Fix mobile touch scrolling
        let isScrolling = false;
        let startX = 0;
        let scrollLeft = 0;
        
        table.addEventListener('touchstart', function(e) {
            isScrolling = true;
            startX = e.touches[0].pageX - table.offsetLeft;
            scrollLeft = table.scrollLeft;
        });
        
        table.addEventListener('touchmove', function(e) {
            if (!isScrolling) return;
            e.preventDefault();
            const x = e.touches[0].pageX - table.offsetLeft;
            const walk = (x - startX) * 2;
            table.scrollLeft = scrollLeft - walk;
        });
        
        table.addEventListener('touchend', function() {
            isScrolling = false;
        });
    }
});

// Function to change items per page
function changePerPage(value) {
    const url = new URL(window.location);
    url.searchParams.set('per_page', value);
    url.searchParams.set('page', '1'); // Reset to first page
    window.location.href = url.toString();
}

// Function to sort table
function sortTable(field) {
    const url = new URL(window.location);
    const currentSortBy = url.searchParams.get('sort_by');
    const currentSortOrder = url.searchParams.get('sort_order');
    
    let newSortOrder = 'asc';
    if (currentSortBy === field && currentSortOrder === 'asc') {
        newSortOrder = 'desc';
    }
    
    url.searchParams.set('sort_by', field);
    url.searchParams.set('sort_order', newSortOrder);
    url.searchParams.set('page', '1'); // Reset to first page
    window.location.href = url.toString();
}

// Auto-submit form when filters change
document.addEventListener('DOMContentLoaded', function() {
    const autoSubmitFields = ['department', 'role', 'per_page'];
    autoSubmitFields.forEach(field => {
        const element = document.getElementById(field);
        if (element) {
            element.addEventListener('change', function() {
                document.getElementById('searchForm').submit();
            });
        }
    });
});
</script>
@endsection
