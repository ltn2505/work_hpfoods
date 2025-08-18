<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>@yield('title','HP Foods')</title>
  
  {{-- Favicon --}}
  <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">

  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="{{ asset('css/home.css') }}">
  @stack('styles')
</head>
<body class="bg-light">

  @include('partials.navbar')

  <div class="container-fluid">
    <div class="row">
      {{-- Sidebar trái --}}
      <aside class="col-12 col-md-3 col-lg-2 sidebar p-0">
        <div class="list-group rounded-0">
          <a href="{{ route('dashboard') }}"
             class="list-group-item {{ request()->routeIs('dashboard')?'active':'' }}">
            <i class="bi bi-list-task me-2"></i> Danh sách
          </a>

          @auth
          @if(Auth::user()->isAdmin())
            <a href="{{ route('users.index') }}"
               class="list-group-item {{ request()->routeIs('users.*')?'active':'' }}">
              <i class="bi bi-people me-2"></i> Nhân viên
            </a>
            <a href="{{ route('departments.index') }}"
               class="list-group-item {{ request()->routeIs('departments.*')?'active':'' }}">
              <i class="bi bi-building me-2"></i> Phòng ban
            </a>
          @endif
          @if(Auth::user()->isAdmin() || Auth::user()->isManager())
            <a href="{{ route('create-task') }}"
               class="list-group-item {{ request()->routeIs('create-task')?'active':'' }}">
              <i class="bi bi-plus-square me-2"></i> Tạo công việc
            </a>
          @endif
          @endauth

          @if(Auth::user()->isAdmin() || Auth::user()->isManager())
            <a href="{{ route('reports.index') }}"
               class="list-group-item {{ request()->routeIs('reports.index')?'active':'' }}">
              <i class="bi bi-bar-chart me-2"></i> Báo cáo
            </a>
          @endif
        </div>
      </aside>

      {{-- Nội dung --}}
      <main class="col-12 col-md-9 col-lg-10 py-3">
        @yield('content')
      </main>
    </div>
  </div>

  @auth
  @if(Auth::user()->isAdmin() || Auth::user()->isManager())
    <a href="{{ route('create-task') }}"
       class="btn btn-success position-fixed"
       style="right:24px; bottom:24px; z-index:1050;">
       ➕ Tạo công việc
    </a>
  @endif
  @endauth

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1"></script>
  <script src="{{ asset('js/home.js') }}"></script>
  @stack('scripts')
</body>
</html>
