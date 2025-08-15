<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;
use Illuminate\Support\Carbon;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $u = $request->user();
        $base = Task::with(['assignee','department','creator'])->latest();

        if ($u->role === 'admin') {
            // xem tất cả
        } elseif ($u->role === 'manager') {
            $base->where('department_id', $u->department_id);
        } else {
            $base->where('assignee_id', $u->id);
        }

        $now   = Carbon::now();
        $tasks = $base->paginate(10);

        $stats = [
            'todo'    => (clone $base)->where('status','todo')->count(),
            'doing'   => (clone $base)->where('status','in_progress')->count(),
            'done'    => (clone $base)->where('status','done')->count(),
            'overdue' => (clone $base)->where(function($q) use ($now){
                $q->where('status','!=','done')
                  ->whereNotNull('deadline')
                  ->where('deadline','<',$now);
            })->count(),
        ];

        // gắn trạng thái overdue để hiển thị màu đúng
        foreach ($tasks as $t) {
            if ($t->status !== 'done' && $t->deadline && $t->deadline->lt($now)) {
                $t->status = 'overdue';
            }
        }

        // giữ nguyên giao diện đã duyệt: dùng file welcome.blade.php làm dashboard
        return view('welcome', compact('tasks','stats'));
    }
}
