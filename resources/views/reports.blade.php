@extends('layouts.master')
@section('title','Báo cáo tổng quan')

@push('styles')
<style>
/* Test CSS - đảm bảo CSS đang hoạt động */
body::before {
    content: "CSS đang hoạt động!";
    position: fixed !important;
    top: 10px !important;
    right: 10px !important;
    background: red !important;
    color: white !important;
    padding: 5px !important;
    z-index: 9999 !important;
    font-size: 12px !important;
}

/* Đảm bảo bottom-nav hiển thị trên trang reports - SỬ DỤNG !IMPORTANT MẠNH */
.bottom-nav {
    display: flex !important;
    position: fixed !important;
    bottom: 0 !important;
    left: 0 !important;
    right: 0 !important;
    width: 100vw !important;
    max-width: 100vw !important;
    z-index: 9999 !important;
    height: 70px !important;
    background: #ffffff !important;
    border-top: 1px solid #dee2e6 !important;
    box-shadow: 0 -2px 10px rgba(0,0,0,0.1) !important;
    overflow: visible !important;
    transform: none !important;
    margin: 0 !important;
    padding: 0 !important;
    visibility: visible !important;
    opacity: 1 !important;
    pointer-events: auto !important;
}

/* Fix container overflow */
.container-fluid {
    overflow-x: hidden !important;
    max-width: 100vw !important;
    margin-bottom: 90px !important;
    padding-bottom: 20px !important;
}

/* Đảm bảo content không bị che bởi bottom-nav */
body {
    padding-bottom: 90px !important;
    overflow-x: hidden !important;
    max-width: 100vw !important;
}

/* Fix main content overflow */
.main-content {
    overflow-x: hidden !important;
    max-width: 100vw !important;
}

/* Cải thiện hiển thị charts */
.chart-container {
    position: relative;
    height: 300px;
    width: 100%;
    max-width: 100%;
    overflow: hidden;
}

.card canvas {
    max-height: 100% !important;
    width: 100% !important;
    height: 100% !important;
    max-width: 100% !important;
}

/* Responsive cho mobile - Sử dụng CSS specificity cao */
@media (max-width: 768px) {
    /* Stats cards responsive */
    .row.g-3 .col-md-3 {
        width: 50% !important;
        margin-bottom: 1rem !important;
    }
    
    .card-stat {
        padding: 1rem !important;
        margin-bottom: 0.5rem !important;
    }
    
    .card-stat h5 {
        font-size: 1.5rem !important;
        margin-bottom: 0.5rem !important;
    }
    
    .card-stat p {
        font-size: 0.9rem !important;
        margin-bottom: 0 !important;
    }
    
    /* Chart cards responsive */
    .card {
        padding: 1rem !important;
        margin-bottom: 1rem !important;
    }
    
    .card h6 {
        font-size: 1rem !important;
        margin-bottom: 1rem !important;
    }
    
    .chart-container {
        height: 250px !important;
    }
    
    .card canvas {
        max-height: 100% !important;
        height: 100% !important;
    }
    
    /* Container responsive */
    .container-fluid {
        padding-left: 1rem !important;
        padding-right: 1rem !important;
        overflow-x: hidden !important;
    }
    
    /* Bottom navigation responsive fixes - SỬ DỤNG !IMPORTANT MẠNH */
    .bottom-nav {
        display: flex !important;
        position: fixed !important;
        bottom: 0 !important;
        left: 0 !important;
        right: 0 !important;
        width: 100vw !important;
        max-width: 100vw !important;
        height: 70px !important;
        padding: 8px 0 !important;
        overflow: visible !important;
        transform: none !important;
        margin: 0 !important;
        z-index: 9999 !important;
        visibility: visible !important;
        opacity: 1 !important;
        pointer-events: auto !important;
    }
    
    .bottom-nav .nav-link {
        font-size: 0.7rem !important;
        padding: 6px 2px !important;
        min-height: 54px !important;
        color: #6c757d !important;
        text-decoration: none !important;
        display: flex !important;
        flex-direction: column !important;
        align-items: center !important;
        justify-content: center !important;
        white-space: nowrap !important;
        overflow: visible !important;
    }
    
    .bottom-nav .nav-link i {
        font-size: 1.1rem !important;
        margin-bottom: 2px !important;
    }
    
    .bottom-nav .nav-link span {
        font-size: 0.65rem !important;
        line-height: 1 !important;
        white-space: nowrap !important;
    }
    
    /* Ensure proper spacing for mobile */
    .main-content {
        margin-bottom: 90px !important;
        padding-bottom: 20px !important;
        overflow-x: hidden !important;
    }
}

/* Responsive cho mobile nhỏ */
@media (max-width: 576px) {
    /* Stats cards - 1 cột trên mobile nhỏ */
    .row.g-3 .col-md-3 {
        width: 100% !important;
        margin-bottom: 0.75rem !important;
    }
    
    .card-stat {
        padding: 0.75rem !important;
    }
    
    .card-stat h5 {
        font-size: 1.3rem !important;
    }
    
    .card-stat p {
        font-size: 0.85rem !important;
    }
    
    /* Chart cards */
    .card {
        padding: 0.75rem !important;
        margin-bottom: 0.75rem !important;
    }
    
    .card h6 {
        font-size: 0.95rem !important;
        margin-bottom: 0.75rem !important;
    }
    
    .chart-container {
        height: 200px !important;
    }
    
    .card canvas {
        max-height: 100% !important;
        height: 100% !important;
    }
    
    /* Container */
    .container-fluid {
        padding-left: 0.75rem !important;
        padding-right: 0.75rem !important;
        overflow-x: hidden !important;
    }
    
    /* Bottom navigation for very small screens */
    .bottom-nav {
        height: 65px !important;
        padding: 6px 0 !important;
        width: 100vw !important;
        max-width: 100vw !important;
        visibility: visible !important;
        opacity: 1 !important;
        pointer-events: auto !important;
    }
    
    .bottom-nav .nav-link {
        font-size: 0.65rem !important;
        padding: 4px 1px !important;
        min-height: 53px !important;
    }
    
    .bottom-nav .nav-link i {
        font-size: 1rem !important;
        margin-bottom: 1px !important;
    }
    
    .bottom-nav .nav-link span {
        font-size: 0.6rem !important;
    }
    
    /* Adjust main content spacing */
    .main-content {
        margin-bottom: 85px !important;
        padding-bottom: 15px !important;
        overflow-x: hidden !important;
    }
}

/* Đảm bảo charts responsive */
@media (max-width: 1200px) {
    .chart-container {
        height: 280px !important;
    }
    
    .card canvas {
        max-height: 100% !important;
        height: 100% !important;
    }
}

/* Force bottom navigation to be always visible and properly positioned */
@media (max-width: 768px) {
    .bottom-nav {
        display: flex !important;
        position: fixed !important;
        bottom: 0 !important;
        left: 0 !important;
        right: 0 !important;
        width: 100vw !important;
        max-width: 100vw !important;
        z-index: 9999 !important;
        background: #ffffff !important;
        border-top: 1px solid #dee2e6 !important;
        box-shadow: 0 -2px 10px rgba(0,0,0,0.1) !important;
        overflow: visible !important;
        transform: none !important;
        margin: 0 !important;
        padding: 0 !important;
        visibility: visible !important;
        opacity: 1 !important;
        pointer-events: auto !important;
    }
    
    /* Prevent content from being hidden behind bottom nav */
    body, .container-fluid, .main-content {
        padding-bottom: 90px !important;
        margin-bottom: 0 !important;
        overflow-x: hidden !important;
        max-width: 100vw !important;
    }
    
    /* Fix any potential horizontal scroll */
    html, body {
        overflow-x: hidden !important;
        max-width: 100vw !important;
    }
}

/* Đảm bảo bottom nav luôn hiển thị ngay lập tức */
@media (max-width: 768px) {
    .bottom-nav {
        animation: none !important;
        transition: none !important;
        will-change: auto !important;
    }
}
</style>
@endpush

@section('content')
<div class="row g-2 g-md-3 mb-3">
  <div class="col-6 col-md-3 mb-2 mb-md-0">
    <div class="card card-stat p-2 p-md-3 text-center">
      <h5 class="mb-1 mb-md-2">{{ $summary['total'] }}</h5>
      <p class="mb-0">Tổng công việc</p>
    </div>
  </div>
  <div class="col-6 col-md-3 mb-2 mb-md-0">
    <div class="card card-stat p-2 p-md-3 text-center">
      <h5 class="text-success mb-1 mb-md-2">{{ $summary['done'] }}</h5>
      <p class="mb-0">Hoàn thành</p>
    </div>
  </div>
  <div class="col-6 col-md-3 mb-2 mb-md-0">
    <div class="card card-stat p-2 p-md-3 text-center">
      <h5 class="text-primary mb-1 mb-md-2">{{ $summary['doing'] }}</h5>
      <p class="mb-0">Đang làm</p>
    </div>
  </div>
  <div class="col-6 col-md-3 mb-2 mb-md-0">
    <div class="card card-stat p-2 p-md-3 text-center">
      <h5 class="text-danger mb-1 mb-md-2">{{ $summary['overdue'] }}</h5>
      <p class="mb-0">Trễ hạn</p>
    </div>
  </div>
</div>

<div class="card p-2 p-md-3 mb-3">
  <h6 class="mb-2 mb-md-3">Phân bổ trạng thái công việc</h6>
  <div class="chart-container">
    <canvas id="statusDonut"></canvas>
  </div>
</div>

<div class="card p-2 p-md-3 mb-3">
  <h6 class="mb-2 mb-md-3">Tiến độ hoàn thành theo tuần</h6>
  <div class="chart-container">
    <canvas id="weeklyLine"></canvas>
  </div>
</div>

<div class="card p-2 p-md-3">
  <h6 class="mb-2 mb-md-3">Phân tích theo phòng ban</h6>
  <div class="chart-container">
    <canvas id="deptBar"></canvas>
  </div>
</div>

@push('scripts')
<script>
// Đảm bảo bottom navigation hiển thị đúng trên mobile - SỬ DỤNG !IMPORTANT
document.addEventListener('DOMContentLoaded', function() {
    // Force bottom navigation to be visible on mobile IMMEDIATELY
    const bottomNav = document.querySelector('.bottom-nav');
    if (bottomNav && window.innerWidth <= 768) {
        // Apply all styles with JavaScript as backup
        Object.assign(bottomNav.style, {
            display: 'flex',
            position: 'fixed',
            bottom: '0',
            left: '0',
            right: '0',
            width: '100vw',
            maxWidth: '100vw',
            zIndex: '9999',
            height: '70px',
            background: '#ffffff',
            borderTop: '1px solid #dee2e6',
            boxShadow: '0 -2px 10px rgba(0,0,0,0.1)',
            overflow: 'visible',
            transform: 'none',
            margin: '0',
            padding: '0',
            visibility: 'visible',
            opacity: '1',
            pointerEvents: 'auto'
        });
        
        // Ensure content has proper spacing
        document.body.style.paddingBottom = '90px';
        document.body.style.overflowX = 'hidden';
        document.body.style.maxWidth = '100vw';
        
        const mainContent = document.querySelector('.main-content');
        if (mainContent) {
            mainContent.style.marginBottom = '90px';
            mainContent.style.paddingBottom = '20px';
            mainContent.style.overflowX = 'hidden';
            mainContent.style.maxWidth = '100vw';
        }
        
        // Fix container overflow
        const container = document.querySelector('.container-fluid');
        if (container) {
            container.style.overflowX = 'hidden';
            container.style.maxWidth = '100vw';
        }
        
        // Force immediate visibility
        bottomNav.style.visibility = 'visible';
        bottomNav.style.opacity = '1';
        bottomNav.style.pointerEvents = 'auto';
    }
    
    // Handle resize events
    window.addEventListener('resize', function() {
        if (window.innerWidth <= 768) {
            if (bottomNav) {
                bottomNav.style.display = 'flex';
                bottomNav.style.width = '100vw';
                bottomNav.style.maxWidth = '100vw';
                bottomNav.style.visibility = 'visible';
                bottomNav.style.opacity = '1';
                bottomNav.style.pointerEvents = 'auto';
            }
            document.body.style.paddingBottom = '90px';
            document.body.style.overflowX = 'hidden';
        } else {
            if (bottomNav) {
                bottomNav.style.display = 'none';
            }
            document.body.style.paddingBottom = '0';
            document.body.style.overflowX = 'auto';
        }
    });
    
    // Force bottom nav to be visible immediately - NO DELAY
    if (bottomNav && window.innerWidth <= 768) {
        // Apply styles immediately without setTimeout
        bottomNav.style.display = 'flex';
        bottomNav.style.visibility = 'visible';
        bottomNav.style.opacity = '1';
        bottomNav.style.pointerEvents = 'auto';
        
        // Also force it in the next frame
        requestAnimationFrame(() => {
            bottomNav.style.display = 'flex';
            bottomNav.style.visibility = 'visible';
            bottomNav.style.opacity = '1';
            bottomNav.style.pointerEvents = 'auto';
        });
    }
});

// Additional script to ensure bottom nav is visible
window.addEventListener('load', function() {
    const bottomNav = document.querySelector('.bottom-nav');
    if (bottomNav && window.innerWidth <= 768) {
        bottomNav.style.display = 'flex';
        bottomNav.style.visibility = 'visible';
        bottomNav.style.opacity = '1';
        bottomNav.style.pointerEvents = 'auto';
    }
});

const donut = new Chart(document.getElementById('statusDonut'),{
  type:'doughnut',
  data:{labels:['Hoàn thành','Đang làm','Trễ hạn','Chưa bắt đầu'],
        datasets:[{data:[{{ $summary['done'] }},{{ $summary['doing'] }},{{ $summary['overdue'] }},{{ $summary['todo'] }}]}]}
});

const weekly = new Chart(document.getElementById('weeklyLine'),{
  type:'line',
  data:{labels: @json($weekly['labels']),
        datasets:[{label:'Hoàn thành', data:@json($weekly['values']), fill:true, tension:.3}]}
});

const dept = new Chart(document.getElementById('deptBar'),{
  type:'bar',
  data:{labels: @json(array_keys($byDept)),
        datasets:[{label:'Số việc', data:@json(array_values($byDept))}]}
});
</script>
@endpush
@endsection
