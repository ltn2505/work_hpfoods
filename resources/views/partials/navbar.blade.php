<!-- Navbar -->
<nav class="navbar navbar-light bg-white shadow-sm sticky-top" style="z-index: 1030;">
  <div class="container-fluid">
    <a class="navbar-brand fw-bold text-primary" href="{{ route('dashboard') }}">📋 Quản lý công việc</a>
    <div class="d-flex align-items-center">
      <span class="me-3">Xin chào, {{ auth()->user()->name }}</span>
      <div class="dropdown">
        <button class="btn btn-outline-secondary dropdown-toggle" type="button" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
          <i class="fas fa-user me-1"></i>{{ auth()->user()->name }}
        </button>
        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
          <li>
            <a class="dropdown-item" href="{{ route('profile.index') }}">
              <i class="fas fa-user me-2"></i>Hồ sơ
            </a>
          </li>
          <li><hr class="dropdown-divider"></li>
          <li>
            <form method="POST" action="{{ route('logout') }}" class="d-inline">
              @csrf
              <button type="submit" class="dropdown-item text-danger">
                <i class="fas fa-sign-out-alt me-2"></i>Đăng xuất
              </button>
            </form>
          </li>
        </ul>
      </div>
    </div>
  </div>
</nav>
