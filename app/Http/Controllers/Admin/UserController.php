<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UserController extends Controller

{
    public function index()
    {
        $users = User::with('department')->latest()->paginate(15);
        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        $departments = Department::orderBy('name')->get();
        return view('admin.users.create', compact('departments'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'=>'required|string|max:255',
            'email'=>'required|email|unique:users,email',
            'password'=>'required|min:8|confirmed',
            'role'=>'required|in:admin,manager,employee',
            'department_id'=>'nullable|exists:departments,id',
        ]);
        // Bắt buộc department cho manager/employee
        if (in_array($data['role'], ['manager','employee']) && empty($data['department_id'])) {
            return back()->withErrors(['department_id'=>'Bắt buộc chọn phòng ban.'])->withInput();
        }
        $data['password'] = bcrypt($data['password']);
        User::create($data);
        return redirect()->route('users.index')->with('success','Tạo người dùng thành công.');
    }

    public function edit(User $user)
    {
        $departments = Department::orderBy('name')->get();
        return view('admin.users.edit', compact('user','departments'));
    }

    public function update(Request $request, User $user)
    {
        $data = $request->validate([
            'name'=>'required|string|max:255',
            'email'=>['required','email', Rule::unique('users','email')->ignore($user->id)],
            'password'=>'nullable|min:8|confirmed',
            'role'=>'required|in:admin,manager,employee',
            'department_id'=>'nullable|exists:departments,id',
        ]);
        if (in_array($data['role'], ['manager','employee']) && empty($data['department_id'])) {
            return back()->withErrors(['department_id'=>'Bắt buộc chọn phòng ban.'])->withInput();
        }
        if (!empty($data['password'])) $data['password'] = bcrypt($data['password']); else unset($data['password']);
        $user->update($data);
        return redirect()->route('users.index')->with('success','Cập nhật thành công.');
    }

    public function destroy(User $user)
    {
        $user->delete();
        return back()->with('success','Đã xóa.');
    }
}
