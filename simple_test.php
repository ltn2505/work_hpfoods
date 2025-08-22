<?php
require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== SIMPLE TEST ===\n";

// Check departments
echo "\n1. DEPARTMENTS:\n";
$departments = \App\Models\Department::all();
echo "Total: " . $departments->count() . "\n";
foreach ($departments as $dept) {
    echo "- {$dept->id}: {$dept->name}\n";
}

// Check tasks
echo "\n2. TASKS:\n";
$allTasks = \App\Models\Task::all();
echo "Total: " . $allTasks->count() . "\n";

$singleDeptTasks = \App\Models\Task::where('is_multi_department', false)->get();
echo "Single department tasks: " . $singleDeptTasks->count() . "\n";

$multiDeptTasks = \App\Models\Task::where('is_multi_department', true)->get();
echo "Multi department tasks: " . $multiDeptTasks->count() . "\n";

// Check each department's tasks
echo "\n3. TASKS PER DEPARTMENT:\n";
foreach ($departments as $dept) {
    echo "\n{$dept->name}:\n";
    
    // Simple approach: just get all single-dept tasks
    $tasks = \App\Models\Task::where('is_multi_department', false)->get();
    echo "  All single-dept tasks: {$tasks->count()}\n";
    
    // Check if any tasks have assignees from this department
    $deptTasks = \App\Models\Task::where('is_multi_department', false)
                    ->whereHas('assignedUsers', function($q) use ($dept) {
                        $q->where('department_id', $dept->id);
                    })->get();
    echo "  Tasks with assignees from this dept: {$deptTasks->count()}\n";
}
