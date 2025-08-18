<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\Department;

class ReportController extends Controller
{
    public function index()
    {
        $summary = [
            'total'   => Task::count(),
            'done'    => Task::where('status','done')->count(),
            'doing'   => Task::where('status','in_progress')->count(),
            'todo'    => Task::where('status','todo')->count(),
            'overdue' => Task::where('status','!=','done')->whereNotNull('deadline')->where('deadline','<',now())->count(),
        ];

        $weeks = collect(range(1,4));
        $weekly = [
            'labels' => $weeks->map(fn($w)=>"Tuần $w"),
            'values' => $weeks->map(fn()=> rand(5,30)), // thay bằng thống kê thật nếu cần
        ];

        $byDept = Department::withCount('tasks')->pluck('tasks_count','name');

        $topEmployees = [
            [
                'name' => 'Minh Quân',
                'initials' => 'MQ',
                'color' => '2563eb',
                'done' => 12,
                'eff' => 95,
                'effClass' => 'eff-green',
            ],
            [
                'name' => 'Ngọc Anh',
                'initials' => 'NA',
                'color' => '22c55e',
                'done' => 10,
                'eff' => 92,
                'effClass' => 'eff-green',
            ],
            [
                'name' => 'Hồng Nhung',
                'initials' => 'HN',
                'color' => 'facc15',
                'done' => 8,
                'eff' => 85,
                'effClass' => 'eff-yellow',
            ],
        ];

        $deptReport = [
            [
                'name' => 'Marketing',
                'total' => 18,
                'done' => 15,
                'doing' => 2,
                'overdue' => 1,
                'eff' => 83,
                'effClass' => 'eff-green',
            ],
            [
                'name' => 'Design',
                'total' => 12,
                'done' => 10,
                'doing' => 1,
                'overdue' => 1,
                'eff' => 83,
                'effClass' => 'eff-green',
            ],
            [
                'name' => 'Kế toán',
                'total' => 8,
                'done' => 6,
                'doing' => 1,
                'overdue' => 1,
                'eff' => 75,
                'effClass' => 'eff-yellow',
            ],
            [
                'name' => 'IT',
                'total' => 10,
                'done' => 4,
                'doing' => 4,
                'overdue' => 2,
                'eff' => 40,
                'effClass' => 'eff-red',
            ],
        ];

        return view('reports.index', compact('summary','weekly','byDept','topEmployees','deptReport'));
    }
}
