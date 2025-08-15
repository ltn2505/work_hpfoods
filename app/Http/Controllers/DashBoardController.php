<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $req)
    {
        $query = Task::with(['assignee','creator'])->latest();
        if ($req->filled('status')) {
            $s = $req->status;
            if ($s === 'overdue') {
                $query->where('status','!=','done')
                      ->whereNotNull('deadline')->where('deadline','<',now());
            } else {
                $query->where('status',$s);
            }
        }

        $tasks = $query->paginate(10);

        $stats = [
            'doing'   => Task::where('status','in_progress')->count(),
            'done'    => Task::where('status','done')->count(),
            'todo'    => Task::where('status','todo')->count(),
            'overdue' => Task::where('status','!=','done')
                             ->whereNotNull('deadline')->where('deadline','<',now())->count(),
        ];

        return view('welcome', compact('tasks','stats'));
    }
}
