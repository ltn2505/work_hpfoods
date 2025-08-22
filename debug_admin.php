<?php
require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== DEBUG ADMIN DASHBOARD ===\n";

// Check departments
echo "\n1. DEPARTMENTS:\n";
$departments = \App\Models\Department::all();
echo "Total: " . $departments->count() . "\n";
foreach ($departments as $dept) {
    echo "- {$dept->id}: {$dept->name}\n";
}

// Check tasks per department
echo "\n2. TASKS PER DEPARTMENT:\n";
foreach ($departments as $department) {
    echo "\n{$department->name}:\n";
    
    // Check tasks with assignees from this department
    $deptTasks = \App\Models\Task::where('is_multi_department', false)
                    ->whereHas('assignedUsers', function($q) use ($department) {
                        $q->where('department_id', $department->id);
                    })->get();
    
    echo "  Tasks with assignees from this dept: {$deptTasks->count()}\n";
    
    foreach ($deptTasks as $task) {
        echo "    - {$task->title}\n";
        echo "      Assignees: ";
        foreach ($task->assignedUsers as $assignee) {
            echo "{$assignee->name} ({$assignee->department->name}) ";
        }
        echo "\n";
    }
}

// Check all single-dept tasks
echo "\n3. ALL SINGLE-DEPARTMENT TASKS:\n";
$allSingleTasks = \App\Models\Task::where('is_multi_department', false)->get();
echo "Total single-dept tasks: {$allSingleTasks->count()}\n";

foreach ($allSingleTasks as $task) {
    echo "- {$task->title}\n";
    echo "  Assignees: ";
    foreach ($task->assignedUsers as $assignee) {
        echo "{$assignee->name} ({$assignee->department->name}) ";
    }
    echo "\n";
}

// Check multi-dept tasks
echo "\n4. MULTI-DEPARTMENT TASKS:\n";
$multiTasks = \App\Models\Task::where('is_multi_department', true)->get();
echo "Total multi-dept tasks: {$multiTasks->count()}\n";

foreach ($multiTasks as $task) {
    echo "- {$task->title}\n";
    echo "  Assignees: ";
    foreach ($task->assignedUsers as $assignee) {
        echo "{$assignee->name} ({$assignee->department->name}) ";
    }
    echo "\n";
}
