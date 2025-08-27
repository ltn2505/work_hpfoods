<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\User;
use App\Models\TaskFollower;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskFollowerController extends Controller
{
    /**
     * Thêm Task Follower
     */
    public function addFollower(Request $request, Task $task)
    {
        $user = Auth::user();
        
        // Kiểm tra quyền thêm follower
        if (!$user->isAdmin() && !$user->isManager()) {
            return response()->json([
                'success' => false,
                'message' => 'Bạn không có quyền thêm Task Follower.'
            ], 403);
        }
        
        // Kiểm tra quyền quản lý task này
        if ($user->isManager()) {
            $canManage = false;
            
            // Kiểm tra task đơn phòng ban
            if ($task->department_id === $user->department_id) {
                $canManage = true;
            }
            
            // Kiểm tra task đa phòng ban
            if ($task->is_multi_department && $task->departments->contains('id', $user->department_id)) {
                $canManage = true;
            }
            
            // Kiểm tra assignees
            if ($task->assignees->where('department_id', $user->department_id)->count() > 0) {
                $canManage = true;
            }
            
            // Kiểm tra creator
            if ($task->creator && $task->creator->department_id === $user->department_id) {
                $canManage = true;
            }
            
            if (!$canManage) {
                return response()->json([
                    'success' => false,
                    'message' => 'Bạn chỉ có thể thêm Task Follower cho task của phòng ban mình.'
                ], 403);
            }
        }
        
        $request->validate([
            'user_id' => 'required|exists:users,id'
        ]);
        
        $followerId = $request->user_id;
        $follower = User::find($followerId);
        
        // Kiểm tra xem user có thể làm follower không
        if (!$follower->isManager() && !$follower->isEmployee()) {
            return response()->json([
                'success' => false,
                'message' => 'Chỉ Manager và Employee mới có thể làm Task Follower.'
            ], 400);
        }
        
        // Kiểm tra xem user đã là follower chưa
        if ($task->followers()->where('user_id', $followerId)->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'Người này đã là Task Follower của công việc này.'
            ], 400);
        }
        
        // Kiểm tra xem user có phải là assignee hoặc creator không
        $isAssignee = $task->assignee_id === $followerId || 
                     $task->creator_id === $followerId ||
                     $task->assignees->contains('id', $followerId);
        
        if ($isAssignee) {
            return response()->json([
                'success' => false,
                'message' => 'Không thể thêm người được giao việc hoặc người tạo việc làm Task Follower.'
            ], 400);
        }
        
        // Thêm follower
        $task->followers()->attach($followerId);
        
        // Ghi log hoạt động
        $task->activities()->create([
            'user_id' => $user->id,
            'action' => 'added_follower',
            'meta' => 'Đã thêm Task Follower: ' . $follower->name,
        ]);
        
        return response()->json([
            'success' => true,
            'message' => 'Đã thêm Task Follower thành công.',
            'follower' => $follower
        ]);
    }
    
    /**
     * Xóa Task Follower
     */
    public function removeFollower(Request $request, Task $task)
    {
        $user = Auth::user();
        
        // Kiểm tra quyền xóa follower
        if (!$user->isAdmin() && !$user->isManager()) {
            return response()->json([
                'success' => false,
                'message' => 'Bạn không có quyền xóa Task Follower.'
            ], 403);
        }
        
        // Kiểm tra quyền quản lý task này
        if ($user->isManager()) {
            $canManage = false;
            
            // Kiểm tra task đơn phòng ban
            if ($task->department_id === $user->department_id) {
                $canManage = true;
            }
            
            // Kiểm tra task đa phòng ban
            if ($task->is_multi_department && $task->departments->contains('id', $user->department_id)) {
                $canManage = true;
            }
            
            // Kiểm tra assignees
            if ($task->assignees->where('department_id', $user->department_id)->count() > 0) {
                $canManage = true;
            }
            
            // Kiểm tra creator
            if ($task->creator && $task->creator->department_id === $user->department_id) {
                $canManage = true;
            }
            
            if (!$canManage) {
                return response()->json([
                    'success' => false,
                    'message' => 'Bạn chỉ có thể xóa Task Follower của task phòng ban mình.'
                ], 403);
            }
        }
        
        $request->validate([
            'user_id' => 'required|exists:users,id'
        ]);
        
        $followerId = $request->user_id;
        $follower = User::find($followerId);
        
        // Kiểm tra xem user có phải là follower không
        if (!$task->followers()->where('user_id', $followerId)->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'Người này không phải là Task Follower của công việc này.'
            ], 400);
        }
        
        // Xóa follower
        $task->followers()->detach($followerId);
        
        // Ghi log hoạt động
        $task->activities()->create([
            'user_id' => $user->id,
            'action' => 'removed_follower',
            'meta' => 'Đã xóa Task Follower: ' . $follower->name,
        ]);
        
        return response()->json([
            'success' => true,
            'message' => 'Đã xóa Task Follower thành công.'
        ]);
    }
    
    /**
     * Lấy danh sách users có thể làm follower
     */
    public function getAvailableFollowers(Task $task)
    {
        $user = Auth::user();
        
        // Kiểm tra quyền xem task
        if ($user->isAdmin()) {
            // Admin có thể xem mọi task
        } elseif ($user->isManager()) {
            // Manager có thể xem task của phòng ban mình
            $canView = false;
            
            if ($task->department_id === $user->department_id) {
                $canView = true;
            }
            
            if ($task->is_multi_department && $task->departments->contains('id', $user->department_id)) {
                $canView = true;
            }
            
            if ($task->assignees->where('department_id', $user->department_id)->count() > 0) {
                $canView = true;
            }
            
            if ($task->creator && $task->creator->department_id === $user->department_id) {
                $canView = true;
            }
            
            if (!$canView) {
                return response()->json([
                    'success' => false,
                    'message' => 'Bạn không có quyền xem task này.'
                ], 403);
            }
        } else {
            // Employee chỉ có thể xem task mà họ được assign hoặc tạo
            $isAssigned = $task->assignee_id === $user->id || 
                         $task->creator_id === $user->id ||
                         $task->assignees->contains('id', $user->id);
            
            if (!$isAssigned) {
                return response()->json([
                    'success' => false,
                    'message' => 'Bạn không có quyền xem task này.'
                ], 403);
            }
        }
        
        // Lấy danh sách users có thể làm follower
        $availableUsers = User::where(function($query) {
            $query->where('role', 'manager')
                  ->orWhere('role', 'employee');
        })
        ->where('id', '!=', $task->creator_id)
        ->where('id', '!=', $task->assignee_id)
        ->whereNotIn('id', $task->assignees->pluck('id'))
        ->whereNotIn('id', $task->followers->pluck('id'))
        ->with('department')
        ->orderBy('name')
        ->get(['id', 'name', 'role', 'department_id']);
        
        return response()->json([
            'success' => true,
            'users' => $availableUsers
        ]);
    }
    
    /**
     * Lấy danh sách followers hiện tại
     */
    public function getCurrentFollowers(Task $task)
    {
        $user = Auth::user();
        
        // Kiểm tra quyền xem task
        if ($user->isAdmin()) {
            // Admin có thể xem mọi task
        } elseif ($user->isManager()) {
            // Manager có thể xem task của phòng ban mình
            $canView = false;
            
            if ($task->department_id === $user->department_id) {
                $canView = true;
            }
            
            if ($task->is_multi_department && $task->departments->contains('id', $user->department_id)) {
                $canView = true;
            }
            
            if ($task->assignees->where('department_id', $user->department_id)->count() > 0) {
                $canView = true;
            }
            
            if ($task->creator && $task->creator->department_id === $user->department_id) {
                $canView = true;
            }
            
            if (!$canView) {
                return response()->json([
                    'success' => false,
                    'message' => 'Bạn không có quyền xem task này.'
                ], 403);
            }
        } else {
            // Employee chỉ có thể xem task mà họ được assign hoặc tạo
            $isAssigned = $task->assignee_id === $user->id || 
                         $task->creator_id === $user->id ||
                         $task->assignees->contains('id', $user->id);
            
            if (!$isAssigned) {
                return response()->json([
                    'success' => false,
                    'message' => 'Bạn không có quyền xem task này.'
                ], 403);
            }
        }
        
        $followers = $task->followers()->with('department')->get(['users.id', 'users.name', 'users.role', 'users.department_id']);
        
        return response()->json([
            'success' => true,
            'followers' => $followers
        ]);
    }
}
