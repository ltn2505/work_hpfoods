<!-- Navbar -->
<nav class="navbar navbar-light bg-white shadow-sm sticky-top" style="z-index: 1030; padding: 0.15rem 1rem; min-height: 35px; height: 35px;">
  <div class="container-fluid">
    <a class="navbar-brand fw-bold text-primary" href="{{ route('dashboard') }}" style="font-size: 1rem; margin: 0; line-height: 1;">📋 Quản lý công việc</a>
    <div class="d-flex align-items-center">
      <span class="me-3" style="font-size: 0.8rem; line-height: 1;">Xin chào, {{ auth()->user()->name }}</span>
      <div class="dropdown">
        <button class="btn btn-outline-secondary dropdown-toggle btn-sm" type="button" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false" style="padding: 0.2rem 0.4rem; font-size: 0.75rem; line-height: 1; height: auto;">
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
