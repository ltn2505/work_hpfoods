public function create()
{
    $users = \App\Models\User::orderBy('name')->get(['id','name']);
    return view('tasks.create', compact('users'));
}

public function store(Request $r)
{
    $data = $r->validate([
        'title'=>'required|string|max:255',
        'description'=>'nullable|string',
        'assignee_id'=>'nullable|exists:users,id',
        'deadline'=>'nullable|date',
        'priority'=>'nullable|in:low,medium,high',
    ]);
    $data['creator_id'] = auth()->id();
    $task = \App\Models\Task::create($data);

    // TODO: xử lý upload attachments nếu cần

    return redirect()->route('task-detail',$task)->with('ok','Đã tạo công việc');
}

public function show(\App\Models\Task $task)
{
    $task->load(['creator','assignee','activities.user']);
    return view('tasks.show', compact('task'));
}

public function updateStatus(\App\Models\Task $task, Request $r)
{
    $status = $r->get('status');
    if (in_array($status,['todo','in_progress','done'])) {
        $task->update(['status'=>$status]);
        $task->activities()->create([
            'user_id'=>auth()->id(),
            'action'=>'updated_status',
            'meta'=>"Cập nhật trạng thái: $status",
        ]);
    }
    return back();
}
