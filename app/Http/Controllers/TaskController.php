<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function create()
    {
        $users = User::orderBy('name')->get(['id','name']);
        // view: resources/views/tasks/create.blade.php
        return view('tasks.create', compact('users'));
    }

    public function store(Request $r)
    {
        $data = $r->validate([
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
            'assignee_id' => 'nullable|exists:users,id',
            'deadline'    => 'nullable|date',
            'priority'    => 'nullable|in:low,medium,high',
        ]);

        $data['creator_id'] = $r->user()->id;
        $data['status']     = 'todo';

        $task = Task::create($data);

        // TODO: xử lý upload file nếu có

        return redirect()->route('task-detail', $task)->with('ok', 'Đã tạo công việc');
    }

    public function show(Task $task)
    {
        $task->load(['creator','assignee','activities.user']);
        return view('tasks.show', compact('task'));
    }

    public function updateStatus(Task $task, Request $r)
    {
        $status = $r->get('status');
        if (in_array($status, ['todo','in_progress','done'], true)) {
            $task->update(['status' => $status]);
            $task->activities()->create([
                'user_id' => $r->user()->id,
                'action'  => 'updated_status',
                'meta'    => "Cập nhật trạng thái: $status",
            ]);
        }
        return back();
    }

    public function index(Request $request)
    {
        $query = Task::with(['assignee', 'creator']);
        if ($request->has('status') && in_array($request->status, ['todo','in_progress','done'])) {
            $query->where('status', $request->status);
        }
        $tasks = $query->latest()->paginate(15);
        return view('admin.tasks.index', compact('tasks'));
    }

    // (Tuỳ bạn đã có hay chưa)
    public function myTasks(Request $r)
    {
        $tasks = Task::with('assignee','creator')
            ->where('assignee_id', $r->user()->id)
            ->latest()
            ->paginate(10);

        $stats = [
            'doing'   => Task::where('assignee_id',$r->user()->id)->where('status','in_progress')->count(),
            'done'    => Task::where('assignee_id',$r->user()->id)->where('status','done')->count(),
            'todo'    => Task::where('assignee_id',$r->user()->id)->where('status','todo')->count(),
            'overdue' => Task::where('assignee_id',$r->user()->id)->where('status','!=','done')
                             ->whereNotNull('deadline')->where('deadline','<',now())->count(),
        ];

        return view('welcome', compact('tasks','stats'));
    }

    // (Tuỳ bạn đã có hay chưa)
    public function comment(Task $task, Request $r)
    {
        $r->validate(['content' => 'required|string|max:2000']);
        $task->activities()->create([
            'user_id' => $r->user()->id,
            'action'  => 'comment',
            'meta'    => $r->content,
        ]);
        return back();
    }

    public function history(Task $task)
    {
        $task->load(['activities.user']);
        return view('tasks.history', compact('task'));
    }
}
