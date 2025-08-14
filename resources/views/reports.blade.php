<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Báo cáo - Quản lý công việc</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="{{ asset('css/home.css') }}" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <style>
    .report-card {
      background: white;
      border-radius: 12px;
      box-shadow: 0 4px 20px rgba(0,0,0,0.08);
      padding: 1.5rem;
      margin-bottom: 1.5rem;
    }
    .chart-container {
      position: relative;
      height: 300px;
      margin: 1rem 0;
    }
    .metric-item {
      text-align: center;
      padding: 1rem;
      border-radius: 8px;
      background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    }
    .metric-number {
      font-size: 2rem;
      font-weight: bold;
      margin-bottom: 0.5rem;
    }
    .metric-label {
      color: #6c757d;
      font-size: 0.875rem;
    }
  </style>
</head>
<body>

@include('partials.navbar')

<div class="container-fluid">
  <div class="row">
    <!-- Sidebar -->
    <div class="col-lg-2 col-md-3 p-0 sidebar">
      <div class="list-group list-group-flush mt-3">
        <a href="{{ route('home') }}" class="list-group-item list-group-item-action">📋 Danh sách</a>
        <a href="{{ route('create-task') }}" class="list-group-item list-group-item-action">➕ Tạo công việc</a>
        <a href="{{ route('reports') }}" class="list-group-item list-group-item-action active">📊 Báo cáo</a>
      </div>
    </div>

    <!-- Main Content -->
    <div class="col-lg-10 col-md-9 p-4">
      <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">📊 Báo cáo tổng quan</h2>
        <div class="d-flex gap-2">
          <select class="form-select form-select-sm" style="width: auto;">
            <option>Tháng 8/2025</option>
            <option>Tháng 7/2025</option>
            <option>Tháng 6/2025</option>
          </select>
          <button class="btn btn-outline-primary btn-sm">📥 Xuất PDF</button>
        </div>
      </div>

      <!-- Thống kê tổng quan -->
      <div class="row g-3 mb-4">
        <div class="col-md-3 col-sm-6">
          <div class="metric-item">
            <div class="metric-number text-primary">48</div>
            <div class="metric-label">Tổng công việc</div>
          </div>
        </div>
        <div class="col-md-3 col-sm-6">
          <div class="metric-item">
            <div class="metric-number text-success">35</div>
            <div class="metric-label">Hoàn thành</div>
          </div>
        </div>
        <div class="col-md-3 col-sm-6">
          <div class="metric-item">
            <div class="metric-number text-warning">8</div>
            <div class="metric-label">Đang làm</div>
          </div>
        </div>
        <div class="col-md-3 col-sm-6">
          <div class="metric-item">
            <div class="metric-number text-danger">5</div>
            <div class="metric-label">Trễ hạn</div>
          </div>
        </div>
      </div>

      <div class="row">
        <!-- Biểu đồ trạng thái -->
        <div class="col-lg-6">
          <div class="report-card">
            <h5 class="mb-3">📊 Phân bố trạng thái công việc</h5>
            <div class="chart-container">
              <canvas id="statusChart"></canvas>
            </div>
          </div>
        </div>

        <!-- Biểu đồ tiến độ theo thời gian -->
        <div class="col-lg-6">
          <div class="report-card">
            <h5 class="mb-3">📈 Tiến độ hoàn thành theo tuần</h5>
            <div class="chart-container">
              <canvas id="progressChart"></canvas>
            </div>
          </div>
        </div>
      </div>

      <div class="row">
        <!-- Top nhân viên -->
        <div class="col-lg-6">
          <div class="report-card">
            <h5 class="mb-3">🏆 Top nhân viên xuất sắc</h5>
            <div class="table-responsive">
              <table class="table table-hover">
                <thead>
                  <tr>
                    <th>Nhân viên</th>
                    <th>Công việc hoàn thành</th>
                    <th>Hiệu suất</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td>
                      <div class="d-flex align-items-center">
                        <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 32px; height: 32px; color: white;">
                          MQ
                        </div>
                        <span>Minh Quân</span>
                      </div>
                    </td>
                    <td>12</td>
                    <td><span class="badge bg-success">95%</span></td>
                  </tr>
                  <tr>
                    <td>
                      <div class="d-flex align-items-center">
                        <div class="bg-success rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 32px; height: 32px; color: white;">
                          NA
                        </div>
                        <span>Ngọc Anh</span>
                      </div>
                    </td>
                    <td>10</td>
                    <td><span class="badge bg-success">92%</span></td>
                  </tr>
                  <tr>
                    <td>
                      <div class="d-flex align-items-center">
                        <div class="bg-warning rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 32px; height: 32px; color: white;">
                          HN
                        </div>
                        <span>Hồng Nhung</span>
                      </div>
                    </td>
                    <td>8</td>
                    <td><span class="badge bg-warning">85%</span></td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>

        <!-- Phân tích theo phòng ban -->
        <div class="col-lg-6">
          <div class="report-card">
            <h5 class="mb-3">🏢 Phân tích theo phòng ban</h5>
            <div class="chart-container">
              <canvas id="departmentChart"></canvas>
            </div>
          </div>
        </div>
      </div>

      <!-- Báo cáo chi tiết -->
      <div class="report-card">
        <h5 class="mb-3">📋 Báo cáo chi tiết</h5>
        <div class="table-responsive">
          <table class="table table-hover">
            <thead>
              <tr>
                <th>Phòng ban</th>
                <th>Tổng công việc</th>
                <th>Hoàn thành</th>
                <th>Đang làm</th>
                <th>Trễ hạn</th>
                <th>Hiệu suất</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td><strong>Marketing</strong></td>
                <td>18</td>
                <td class="text-success">15</td>
                <td class="text-warning">2</td>
                <td class="text-danger">1</td>
                <td><span class="badge bg-success">83%</span></td>
              </tr>
              <tr>
                <td><strong>Design</strong></td>
                <td>12</td>
                <td class="text-success">10</td>
                <td class="text-warning">1</td>
                <td class="text-danger">1</td>
                <td><span class="badge bg-success">83%</span></td>
              </tr>
              <tr>
                <td><strong>Kế toán</strong></td>
                <td>8</td>
                <td class="text-success">6</td>
                <td class="text-warning">1</td>
                <td class="text-danger">1</td>
                <td><span class="badge bg-warning">75%</span></td>
              </tr>
              <tr>
                <td><strong>IT</strong></td>
                <td>10</td>
                <td class="text-success">4</td>
                <td class="text-warning">4</td>
                <td class="text-danger">2</td>
                <td><span class="badge bg-warning">40%</span></td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
// Biểu đồ trạng thái (Doughnut)
const statusCtx = document.getElementById('statusChart').getContext('2d');
new Chart(statusCtx, {
  type: 'doughnut',
  data: {
    labels: ['Hoàn thành', 'Đang làm', 'Trễ hạn', 'Chưa bắt đầu'],
    datasets: [{
      data: [35, 8, 5, 0],
      backgroundColor: ['#198754', '#ffc107', '#dc3545', '#6c757d'],
      borderWidth: 0
    }]
  },
  options: {
    responsive: true,
    maintainAspectRatio: false,
    plugins: {
      legend: {
        position: 'bottom'
      }
    }
  }
});

// Biểu đồ tiến độ (Line)
const progressCtx = document.getElementById('progressChart').getContext('2d');
new Chart(progressCtx, {
  type: 'line',
  data: {
    labels: ['Tuần 1', 'Tuần 2', 'Tuần 3', 'Tuần 4'],
    datasets: [{
      label: 'Công việc hoàn thành',
      data: [8, 12, 15, 35],
      borderColor: '#0d6efd',
      backgroundColor: 'rgba(13, 110, 253, 0.1)',
      tension: 0.4,
      fill: true
    }]
  },
  options: {
    responsive: true,
    maintainAspectRatio: false,
    plugins: {
      legend: {
        display: false
      }
    },
    scales: {
      y: {
        beginAtZero: true
      }
    }
  }
});

// Biểu đồ phòng ban (Bar)
const departmentCtx = document.getElementById('departmentChart').getContext('2d');
new Chart(departmentCtx, {
  type: 'bar',
  data: {
    labels: ['Marketing', 'Design', 'Kế toán', 'IT'],
    datasets: [{
      label: 'Hiệu suất (%)',
      data: [83, 83, 75, 40],
      backgroundColor: ['#198754', '#0d6efd', '#ffc107', '#dc3545']
    }]
  },
  options: {
    responsive: true,
    maintainAspectRatio: false,
    plugins: {
      legend: {
        display: false
      }
    },
    scales: {
      y: {
        beginAtZero: true,
        max: 100
      }
    }
  }
});
</script>
</body>
</html>
