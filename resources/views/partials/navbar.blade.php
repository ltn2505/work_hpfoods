<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
  <div class="container-fluid">
    <a class="navbar-brand fw-bold text-primary" href="{{ route('dashboard') }}">📋 Quản lý công việc</a>
    <div class="d-flex align-items-center">
      <span class="me-3 fw-semibold">Xin chào, {{ auth()->user()->name }}</span>
      <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button class="btn btn-outline-danger btn-sm" type="submit">Đăng xuất</button>
      </form>
    </div>
  </div>
</nav>

