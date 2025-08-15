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

        return view('reports.index', compact('summary','weekly','byDept'));
    }
}
