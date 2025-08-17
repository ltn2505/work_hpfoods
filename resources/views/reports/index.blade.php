@extends('layouts.master')
@section('title','Báo cáo tổng quan')

@section('content')
<style>
.report-card {
    border-radius: 18px;
    box-shadow: 0 2px 12px rgba(0,0,0,0.06);
    background: #fff;
    padding: 24px;
    margin-bottom: 24px;
}
.stat-card {
    border-radius: 16px;
    background: #f8fafc;
    text-align: center;
    padding: 18px 0 10px 0;
    margin-bottom: 16px;
    box-shadow: 0 1px 6px rgba(0,0,0,0.04);
}
.stat-card h2 { font-size: 2.2rem; font-weight: 700; margin-bottom: 4px; }
.stat-card .stat-label { font-size: 1rem; color: #888; }
.stat-blue { color: #2563eb; }
.stat-green { color: #22c55e; }
.stat-yellow { color: #facc15; }
.stat-red { color: #ef4444; }
.top-employee-avatar {
    width: 32px; height: 32px; border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; font-weight: bold; color: #fff; margin-right: 8px;
}
.efficiency-badge { border-radius: 8px; padding: 2px 10px; color: #fff; font-weight: 500; }
.eff-green { background: #22c55e; }
.eff-yellow { background: #facc15; color: #333; }
.eff-orange { background: #f59e42; }
.eff-red { background: #ef4444; }
.table-report th, .table-report td { vertical-align: middle; }
.table-report { border-radius: 12px; overflow: hidden; background: #fff; }
</style>

<div class="mb-4">
    <h3 class="mb-3"><i class="bi bi-bar-chart-fill me-2"></i>Báo cáo tổng quan</h3>
    <div class="row g-3 mb-3">
        <div class="col-md-3">
            <div class="stat-card">
                <h2 class="stat-blue">{{ $summary['total'] }}</h2>
                <div class="stat-label">Tổng công việc</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card">
                <h2 class="stat-green">{{ $summary['done'] }}</h2>
                <div class="stat-label">Hoàn thành</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card">
                <h2 class="stat-yellow">{{ $summary['doing'] }}</h2>
                <div class="stat-label">Đang làm</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card">
                <h2 class="stat-red">{{ $summary['overdue'] }}</h2>
                <div class="stat-label">Trễ hạn</div>
            </div>
        </div>
    </div>
    <div class="row g-4">
        <div class="col-lg-6">
            <div class="report-card">
                <h6 class="mb-3"><i class="bi bi-pie-chart me-2"></i>Phân bố trạng thái công việc</h6>
                <canvas id="statusDonut" height="120" style="max-width:350px; margin:auto; display:block;"></canvas>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="report-card">
                <h6 class="mb-3"><i class="bi bi-graph-up-arrow me-2"></i>Tiến độ hoàn thành theo tuần</h6>
                <canvas id="weeklyLine" height="180"></canvas>
            </div>
        </div>
    </div>
    <div class="row g-4">
        <div class="col-lg-6">
            <div class="report-card">
                <h6 class="mb-3"><i class="bi bi-trophy me-2"></i>Top nhân viên xuất sắc</h6>
                <table class="table table-borderless align-middle mb-0">
                    <thead>
                        <tr><th>Nhân viên</th><th>Công việc hoàn thành</th><th>Hiệu suất</th></tr>
                    </thead>
                    <tbody>
                        @foreach($topEmployees as $emp)
                        <tr>
                            <td>
                                <span class="top-employee-avatar" style="background: #{{ $emp['color'] }};">{{ $emp['initials'] }}</span>
                                {{ $emp['name'] }}
                            </td>
                            <td>{{ $emp['done'] }}</td>
                            <td><span class="efficiency-badge {{ $emp['effClass'] }}">{{ $emp['eff'] }}%</span></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="report-card">
                <h6 class="mb-3"><i class="bi bi-bar-chart-steps me-2"></i>Phân tích theo phòng ban</h6>
                <canvas id="deptBar" height="180"></canvas>
            </div>
        </div>
    </div>
    <div class="report-card mt-4">
        <h6 class="mb-3"><i class="bi bi-list-ul me-2"></i>Báo cáo chi tiết</h6>
        <div class="table-responsive">
            <table class="table table-report mb-0">
                <thead>
                    <tr>
                        <th>Phòng ban</th><th>Tổng công việc</th><th>Hoàn thành</th><th>Đang làm</th><th>Trễ hạn</th><th>Hiệu suất</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($deptReport as $dept)
                    <tr>
                        <td>{{ $dept['name'] }}</td>
                        <td>{{ $dept['total'] }}</td>
                        <td>{{ $dept['done'] }}</td>
                        <td>{{ $dept['doing'] }}</td>
                        <td>{{ $dept['overdue'] }}</td>
                        <td><span class="efficiency-badge {{ $dept['effClass'] }}">{{ $dept['eff'] }}%</span></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

@push('scripts')
<script>
const donut = new Chart(document.getElementById('statusDonut'),{
  type:'doughnut',
  data:{labels:['Hoàn thành','Đang làm','Trễ hạn','Chưa bắt đầu'],
        datasets:[{data:[{{ $summary['done'] }},{{ $summary['doing'] }},{{ $summary['overdue'] }},{{ $summary['todo'] }}],
        backgroundColor:['#22c55e','#2563eb','#ef4444','#facc15']}]},
  options:{plugins:{legend:{position:'bottom'}}}
});
const weekly = new Chart(document.getElementById('weeklyLine'),{
  type:'line',
  data:{labels: @json($weekly['labels']),
        datasets:[{label:'Hoàn thành', data:@json($weekly['values']), fill:true, tension:.3, borderColor:'#2563eb', backgroundColor:'rgba(37,99,235,0.08)'}]},
  options:{plugins:{legend:{display:false}}}
});
const dept = new Chart(document.getElementById('deptBar'),{
  type:'bar',
  data:{labels: @json(array_keys($byDept->toArray())),
        datasets:[{label:'Số việc', data:@json(array_values($byDept->toArray())), backgroundColor:['#22c55e','#2563eb','#facc15','#ef4444']}]},
  options:{plugins:{legend:{display:false}}}
});
</script>
@endpush
@endsection
