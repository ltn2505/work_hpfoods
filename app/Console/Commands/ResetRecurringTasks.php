<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Task;
use Carbon\Carbon;

class ResetRecurringTasks extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tasks:reset-recurring';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cập nhật deadline cho các công việc lặp lại';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Bắt đầu kiểm tra và cập nhật deadline công việc lặp lại...');
        
        $tasks = Task::where('is_recurring', true)
                    ->whereNotNull('recurring_days')
                    ->get();
        
        $updateCount = 0;
        $errorCount = 0;
        
        foreach ($tasks as $task) {
            try {
                if ($task->needsNewDeadline()) {
                    $this->info("Cập nhật deadline công việc: {$task->title} (ID: {$task->id})");
                    
                    $task->updateRecurringDeadline();
                    $updateCount++;
                    
                    $this->info("✓ Đã cập nhật thành công - Deadline mới: " . $task->deadline->format('d/m/Y H:i'));
                }
            } catch (\Exception $e) {
                $this->error("✗ Lỗi khi cập nhật deadline công việc {$task->id}: " . $e->getMessage());
                $errorCount++;
            }
        }
        
        // Kiểm tra và xử lý công việc cần làm lại
        $this->info('Kiểm tra công việc cần làm lại...');
        
        $reworkTasks = Task::where('status', 'rejected')
                           ->whereNotNull('rework_deadline')
                           ->get();
        
        foreach ($reworkTasks as $task) {
            if ($task->needsRework()) {
                $this->info("Công việc {$task->title} đã hết hạn làm lại, chuyển về trạng thái 'in_progress'");
                
                $task->update([
                    'status' => 'in_progress',
                    'rework_deadline' => null,
                    'rework_hours' => null
                ]);
                
                $task->activities()->create([
                    'user_id' => $task->creator_id,
                    'action' => 'rework_expired',
                    'meta' => 'Công việc được chuyển về trạng thái làm lại do hết hạn'
                ]);
            }
        }
        
        $this->info("Hoàn thành! Đã cập nhật {$updateCount} deadline, {$errorCount} lỗi.");
        
        return Command::SUCCESS;
    }
}
