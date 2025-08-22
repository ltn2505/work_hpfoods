<?php
require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\Schema;

if (Schema::hasTable('department_task')) {
    Schema::dropIfExists('department_task');
    echo "Table department_task dropped successfully!\n";
} else {
    echo "Table department_task does not exist.\n";
}
