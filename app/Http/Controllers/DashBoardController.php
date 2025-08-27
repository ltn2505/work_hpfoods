<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $req)
    {
        $user = auth()->user();
        
        if ($user->isAdmin()) {
            // Admin thấy tasks theo từng phòng ban
            $departments = \App\Models\Department::all(); // Lấy tất cả phòng ban một cách đơn giản
            
            // Debug: Log để kiểm tra
            \Log::info('DashboardController - Total departments found: ' . $departments->count());
            foreach ($departments as $dept) {
                \Log::info("DashboardController - Department: {$dept->id} - {$dept->name}");
            }
            
            $departmentTasks = [];
            foreach ($departments as $department) {
                // Lấy task thực sự thuộc về phòng ban này
                $query = Task::with(['assignees.department', 'departments', 'creator'])
                            ->where('is_multi_department', false) // Chỉ hiển thị task đơn phòng ban
                            ->where(function($q) use ($department) {
                                // Task có assignees thuộc phòng ban này
                                $q->whereHas('assignees', function($subQ) use ($department) {
                                    $subQ->where('department_id', $department->id);
                                });
                            });
                
                // Filter theo phòng ban nếu có (hỗ trợ nhiều phòng ban)
                if ($req->filled('department_filter') && is_array($req->department_filter) && count($req->department_filter) > 0) {
                    if (!in_array($department->id, $req->department_filter)) {
                        continue; // Bỏ qua phòng ban không được chọn
                    }
                }
                
                // Filter theo trạng thái (hỗ trợ nhiều trạng thái)
                if ($req->has('statuses') && is_array($req->statuses) && count($req->statuses) > 0) {
                    $query->whereIn('status', $req->statuses);
                } elseif ($req->filled('status')) {
                    $s = $req->status;
                    if ($s === 'overdue') {
                        $query->where('status','overdue');
                    } else {
                        $query->where('status',$s);
                    }
                }
                
                // Filter theo khoảng thời gian
                if ($req->filled('date_from')) {
                    $query->whereDate('created_at', '>=', $req->date_from);
                }
                if ($req->filled('date_to')) {
                    $query->whereDate('created_at', '<=', $req->date_to);
                }
                
                // Sắp xếp theo thời gian
                if ($req->filled('sort')) {
                    if ($req->sort === 'oldest') {
                        $query->oldest();
                    } else {
                        $query->latest();
                    }
                } else {
                    $query->latest(); // Mặc định sắp xếp mới nhất
                }
                
                $tasks = $query->get();
                $departmentTasks[$department->id] = $tasks;
                
                // Debug: Log số lượng task cho mỗi phòng ban
                \Log::info("DashboardController - Department {$department->name}: {$tasks->count()} tasks");
            }
            
            // Đảm bảo tất cả phòng ban đều có trong $departmentTasks, kể cả không có task
            foreach ($departments as $department) {
                if (!isset($departmentTasks[$department->id])) {
                    $departmentTasks[$department->id] = collect(); // Empty collection cho phòng ban không có task
                }
            }
            
            $stats = [
                'doing'   => Task::where('status','in_progress')->count(),
                'completed' => Task::where('status','completed')->count(),
                'rejected' => Task::where('status','rejected')->count(),
                'overdue' => Task::where('status','overdue')->count(),
                'finished' => Task::where('status','finished')->count(),
            ];
            
            // Lấy multi-department tasks
            $multiDepartmentTasks = Task::with(['assignees.department', 'departments', 'creator'])
                ->where('is_multi_department', true);
            
            // Filter theo trạng thái (hỗ trợ nhiều trạng thái)
            if ($req->has('statuses') && is_array($req->statuses) && count($req->statuses) > 0) {
                $multiDepartmentTasks->whereIn('status', $req->statuses);
            } elseif ($req->filled('status')) {
                $s = $req->status;
                if ($s === 'overdue') {
                    $multiDepartmentTasks->where('status','overdue');
                } else {
                    $multiDepartmentTasks->where('status',$s);
                }
            }
            
            // Filter theo phòng ban nếu có (hỗ trợ nhiều phòng ban)
            if ($req->filled('department_filter') && is_array($req->department_filter) && count($req->department_filter) > 0) {
                $multiDepartmentTasks->whereHas('assignedUsers', function($q) use ($req) {
                    $q->whereIn('department_id', $req->department_filter);
                });
            }
            
            // Filter theo khoảng thời gian
            if ($req->filled('date_from')) {
                $multiDepartmentTasks->whereDate('created_at', '>=', $req->date_from);
            }
            if ($req->filled('date_to')) {
                $multiDepartmentTasks->whereDate('created_at', '<=', $req->date_to);
            }
            
            // Sắp xếp theo thời gian
            if ($req->filled('sort')) {
                if ($req->sort === 'oldest') {
                    $multiDepartmentTasks->oldest();
                } else {
                    $multiDepartmentTasks->latest();
                }
            } else {
                $multiDepartmentTasks->latest();
            }
            
            $multiDepartmentTasks = $multiDepartmentTasks->get();
            
            return view('welcome', compact('departments', 'departmentTasks', 'stats', 'multiDepartmentTasks'));
            
        } elseif ($user->isManager()) {
            // Manager: Lấy multi-department tasks có phòng ban tham gia
            $managerMultiDepartmentTasks = Task::with(['assignedUsers', 'creator'])
                ->where('is_multi_department', true)
                ->whereHas('assignedUsers', function($q) use ($user) {
                    $q->where('department_id', $user->department_id);
                });
            
            // Filter theo trạng thái (hỗ trợ nhiều trạng thái)
            if ($req->has('statuses') && is_array($req->statuses) && count($req->statuses) > 0) {
                $managerMultiDepartmentTasks->whereIn('status', $req->statuses);
            } elseif ($req->filled('status')) {
                $s = $req->status;
                if ($s === 'overdue') {
                    $managerMultiDepartmentTasks->where('status','overdue');
                } else {
                    $managerMultiDepartmentTasks->where('status',$s);
                }
            }
            
            // Filter theo khoảng thời gian
            if ($req->filled('date_from')) {
                $managerMultiDepartmentTasks->whereDate('created_at', '>=', $req->date_from);
            }
            if ($req->filled('date_to')) {
                $managerMultiDepartmentTasks->whereDate('created_at', '<=', $req->date_to);
            }
            
            // Sắp xếp theo thời gian
            if ($req->filled('sort')) {
                if ($req->sort === 'oldest') {
                    $managerMultiDepartmentTasks->oldest();
                } else {
                    $managerMultiDepartmentTasks->latest();
                }
            } else {
                $managerMultiDepartmentTasks->latest();
            }
            
            $managerMultiDepartmentTasks = $managerMultiDepartmentTasks->get();
            
            // Manager: Lấy tasks thuộc phòng ban (không phải multi-department)
            $managerDepartment = $user->department;
            $managerDepartmentTasks = Task::with(['assignedUsers','creator'])
                ->where('is_multi_department', false)
                ->where(function($q) use ($user) {
                    $q->whereHas('assignedUsers', function($subQ) use ($user) {
                        $subQ->where('department_id', $user->department_id);
                    })
                    ->orWhereHas('creator', function($subQ) use ($user) {
                        $subQ->where('department_id', $user->department_id);
                    });
                });
            
            // Sắp xếp theo thời gian
            if ($req->filled('sort')) {
                if ($req->sort === 'oldest') {
                    $managerDepartmentTasks->oldest();
                } else {
                    $managerDepartmentTasks->latest();
                }
            } else {
                $managerDepartmentTasks->latest();
            }
            
            $managerDepartmentTasks = $managerDepartmentTasks->get();
            
            // Query cho bảng Employee-style (fallback)
            $query = Task::with(['assignedUsers','creator'])
                        ->where(function($q) use ($user) {
                            $q->whereHas('assignedUsers', function($subQ) use ($user) {
                                $subQ->where('department_id', $user->department_id);
                            })
                            ->orWhereHas('creator', function($subQ) use ($user) {
                                $subQ->where('department_id', $user->department_id);
                            });
                        });
            
            $stats = [
                'doing'   => Task::whereHas('assignedUsers', function($q) use ($user) {
                                $q->where('department_id', $user->department_id);
                            })->where('status','in_progress')->count(),
                'completed' => Task::whereHas('assignedUsers', function($q) use ($user) {
                                $q->where('department_id', $user->department_id);
                            })->where('status','completed')->count(),
                'rejected' => Task::whereHas('assignedUsers', function($q) use ($user) {
                                $q->where('department_id', $user->department_id);
                            })->where('status','rejected')->count(),
                'overdue' => Task::whereHas('assignedUsers', function($q) use ($user) {
                                $q->where('department_id', $user->department_id);
                            })->where('status','overdue')->count(),
                'finished' => Task::whereHas('assignedUsers', function($q) use ($user) {
                                $q->where('department_id', $user->department_id);
                            })->where('status','finished')->count(),
            ];
            
            // Filter theo trạng thái (hỗ trợ nhiều trạng thái)
            if ($req->has('statuses') && is_array($req->statuses) && count($req->statuses) > 0) {
                $query->whereIn('status', $req->statuses);
            } elseif ($req->filled('status')) {
                $s = $req->status;
                if ($s === 'overdue') {
                    $query->where('status','overdue');
                } else {
                    $query->where('status',$s);
                }
            }
            
            // Filter theo khoảng thời gian
            if ($req->filled('date_from')) {
                $query->whereDate('created_at', '>=', $req->date_from);
            }
            if ($req->filled('date_to')) {
                $query->whereDate('created_at', '<=', $req->date_to);
            }
            
            // Sắp xếp theo thời gian
            if ($req->filled('sort')) {
                if ($req->sort === 'oldest') {
                    $query->oldest();
                } else {
                    $query->latest();
                }
            } else {
                $query->latest(); // Mặc định sắp xếp mới nhất
            }

            $tasks = $query->paginate(10);
            
            return view('welcome', compact('tasks','stats', 'managerMultiDepartmentTasks', 'managerDepartment', 'managerDepartmentTasks'));
            
        } else {
            // Employee: Hiển thị cấu trúc phòng ban giống Admin nhưng chỉ thấy task của mình
            $query = Task::with(['assignedUsers','creator'])
                        ->where(function($q) use ($user) {
                            $q->whereHas('assignedUsers', function($subQ) use ($user) {
                                $subQ->where('users.id', $user->id);
                            })
                            ->orWhere('creator_id', $user->id);
                        });
            
            $stats = [
                'doing'   => Task::whereHas('assignedUsers', function($q) use ($user) {
                                $q->where('users.id', $user->id);
                            })->where('status','in_progress')->count(),
                'completed' => Task::whereHas('assignedUsers', function($q) use ($user) {
                                $q->where('users.id', $user->id);
                            })->where('status','completed')->count(),
                'rejected' => Task::whereHas('assignedUsers', function($q) use ($user) {
                                $q->where('users.id', $user->id);
                            })->where('status','rejected')->count(),
                'overdue' => Task::whereHas('assignedUsers', function($q) use ($user) {
                                $q->where('users.id', $user->id);
                            })->where('status','overdue')->count(),
                'finished' => Task::whereHas('assignedUsers', function($q) use ($user) {
                                $q->where('users.id', $user->id);
                            })->where('status','finished')->count(),
            ];
            
            // Filter theo trạng thái (hỗ trợ nhiều trạng thái)
            if ($req->has('statuses') && is_array($req->statuses) && count($req->statuses) > 0) {
                $query->whereIn('status', $req->statuses);
            } elseif ($req->filled('status')) {
                $s = $req->status;
                if ($s === 'overdue') {
                    $query->where('status','overdue');
                } else {
                    $query->where('status',$s);
                }
            }
            
            // Filter theo khoảng thời gian
            if ($req->filled('date_from')) {
                $query->whereDate('created_at', '>=', $req->date_from);
            }
            if ($req->filled('date_to')) {
                $query->whereDate('created_at', '<=', $req->date_to);
            }
            
            // Sắp xếp theo thời gian
            if ($req->filled('sort')) {
                if ($req->sort === 'newest') {
                    $query->latest();
                } elseif ($req->sort === 'oldest') {
                    $query->oldest();
                }
            } else {
                $query->latest(); // Mặc định sắp xếp mới nhất
            }

            $tasks = $query->paginate(10);
            return view('welcome', compact('tasks','stats'));
        }
    }
}

