<?php
require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== TEST DASHBOARD CONTROLLER ===\n";

// Simulate admin user
$admin = \App\Models\User::where('role', 'admin')->first();
if (!$admin) {
    echo "No admin user found!\n";
    exit;
}

echo "Admin user: {$admin->name}\n";

// Create request
$request = new \Illuminate\Http\Request();

// Create controller instance
$controller = new \App\Http\Controllers\DashboardController();

// Call index method
$response = $controller->index($request);

// Check if it's a view response
if ($response instanceof \Illuminate\View\View) {
    $data = $response->getData();
    
    echo "\n=== VIEW DATA ===\n";
    echo "Departments: " . (isset($data['departments']) ? $data['departments']->count() : 'N/A') . "\n";
    echo "Department Tasks: " . (isset($data['departmentTasks']) ? count($data['departmentTasks']) : 'N/A') . "\n";
    echo "Multi Department Tasks: " . (isset($data['multiDepartmentTasks']) ? $data['multiDepartmentTasks']->count() : 'N/A') . "\n";
    
    if (isset($data['departments'])) {
        echo "\nDepartments:\n";
        foreach ($data['departments'] as $dept) {
            echo "- {$dept->name} (ID: {$dept->id})\n";
        }
    }
    
    if (isset($data['departmentTasks'])) {
        echo "\nDepartment Tasks:\n";
        foreach ($data['departmentTasks'] as $deptId => $tasks) {
            $deptName = $data['departments']->firstWhere('id', $deptId)->name ?? 'Unknown';
            echo "- {$deptName}: {$tasks->count()} tasks\n";
        }
    }
} else {
    echo "Response is not a view: " . get_class($response) . "\n";
}
