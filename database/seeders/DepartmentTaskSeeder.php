<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Department;
use App\Models\Task;
use Illuminate\Support\Facades\DB;

class DepartmentTaskSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Xóa dữ liệu cũ
        DB::table('department_task')->truncate();
        
        $departments = Department::all();
        $tasks = Task::all();
        
        foreach ($tasks as $task) {
            if ($task->is_multi_department) {
                // Task đa phòng ban: lấy departments từ assignedUsers
                $taskDepartments = $task->assignedUsers->pluck('department_id')->unique();
                
                foreach ($taskDepartments as $deptId) {
                    DB::table('department_task')->insert([
                        'department_id' => $deptId,
                        'task_id' => $task->id,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            } else {
                // Task đơn phòng ban: lấy department từ assignedUsers
                $taskDepartments = $task->assignedUsers->pluck('department_id')->unique();
                
                if ($taskDepartments->count() > 0) {
                    foreach ($taskDepartments as $deptId) {
                        DB::table('department_task')->insert([
                            'department_id' => $deptId,
                            'task_id' => $task->id,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);
                    }
                } else {
                    // Nếu không có assignedUsers, sử dụng department_id của task
                    if ($task->department_id) {
                        DB::table('department_task')->insert([
                            'department_id' => $task->department_id,
                            'task_id' => $task->id,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);
                    }
                }
            }
        }
        
        $this->command->info('Department-Task relationships seeded successfully!');
    }
}
