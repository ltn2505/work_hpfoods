<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // Reset công việc lặp lại mỗi 7h sáng
        $schedule->command('tasks:reset-recurring')
                ->dailyAt('07:00')
                ->withoutOverlapping()
                ->runInBackground()
                ->appendOutputTo(storage_path('logs/recurring-tasks.log'));
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
