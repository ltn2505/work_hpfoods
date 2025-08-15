<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;
use App\Models\User;
use App\Models\Department;
use App\Models\TaskActivity;

class TaskController extends Controller
{
    public function index()
    {
        $u = auth()->user();
        $q = Task::with(['assignee','department'])->latest();

        if ($u->role === 'admin') {
            // all
        } elseif ($u->role === 'manager') {
            $q->where('department_id', $u->department_id);
        } else {
            $q->where('assignee_id', $u->id);
        }

        $tasks = $q->paginate(15);
        return view('tasks.index', compact('tasks'));
    }

    public function create()
    {
        $u = auth()->user();
        $assignees = User::when(!$u->isAdmin(), fn($q)=>$q->where('department_id',$u->department_id))
                          ->orderBy('name')->get();
        $departments = $u->isAdmin()
            ? Department::orderBy('name')->get()
            : Department::where('id',$u->department_id)->get();

        return view('create-task', compact('assignees','departments'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title'         => 'required|string|max:255',
            'description'   => 'nullable|string',
            'status'        => 'required|in:todo,in_progress,done',
            'department_id' => 'nullable|exists:departments,id',
            'assignee_id'   => 'nullable|exists:users,id',
            'deadline'      => 'nullable|date',
        ]);

        $u = auth()->user();
        if ($u->isManager()) {
            $data['department_id'] = $u->department_id;
        }
        $data['creator_id'] = $u->id;

        $task = Task::create($data);

        TaskActivity::create([
            'task_id' => $task->id,
            'user_id' => $u->id,
            'action'  => 'created',
            'meta'    => json_encode(['title' => $task->title]),
        ]);

        return redirect()->route('dashboard')->with('success','Tạo công việc thành công.');
    }

    public function show(Task $task)
    {
        $task->load(['assignee','department','creator','activities.user']);
        return view('task-detail', compact('task'));
    }

    public function edit(Task $task)
    {
        $u = auth()->user();
        $assignees = User::when(!$u->isAdmin(), fn($q)=>$q->where('department_id',$u->department_id))
                          ->orderBy('name')->get();
        $departments = $u->isAdmin()
            ? Department::orderBy('name')->get()
            : Department::where('id',$u->department_id)->get();

        return view('tasks.edit', compact('task','assignees','departments'));
    }

    public function update(Request $request, Task $task)
    {
        $data = $request->validate([
            'title'         => 'required|string|max:255',
            'description'   => 'nullable|string',
            'status'        => 'required|in:todo,in_progress,done',
            'department_id' => 'nullable|exists:departments,id',
            'assignee_id'   => 'nullable|exists:users,id',
            'deadline'      => 'nullable|date',
        ]);

        $task->update($data);

        TaskActivity::create([
            'task_id' => $task->id,
            'user_id' => auth()->id(),
            'action'  => 'updated',
            'meta'    => json_encode(['title' => $task->title]),
        ]);

        return redirect()->route('dashboard')->with('success','Cập nhật công việc thành công.');
    }

    public function destroy(Task $task)
    {
        $task->delete();
        return back()->with('success','Đã xoá công việc.');
    }

    public function myTasks()
    {
        $tasks = Task::with(['assignee','department'])
            ->where('assignee_id', auth()->id())
            ->latest()->paginate(15);

        return view('tasks.mine', compact('tasks'));
    }
}
