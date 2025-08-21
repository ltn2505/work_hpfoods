<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('tasks', function (Blueprint $table) {
            // Trường lặp lại công việc
            $table->enum('recurring_type', ['none', 'daily', '3_days', 'weekly', 'custom'])->default('none')->after('finish_note');
            
            // Ngày bắt đầu lặp lại
            $table->date('recurring_start_date')->nullable()->after('recurring_type');
            
            // Ngày kết thúc lặp lại
            $table->date('recurring_end_date')->nullable()->after('recurring_start_date');
            
            // Số ngày lặp lại (cho trường hợp custom)
            $table->integer('recurring_days')->nullable()->after('recurring_end_date');
            
            // Ngày cuối cùng được reset
            $table->date('last_reset_date')->nullable()->after('recurring_days');
            
            // Trạng thái lặp lại (active, paused, completed)
            $table->enum('recurring_status', ['active', 'paused', 'completed'])->default('active')->after('last_reset_date');
            
            // Thời gian làm lại khi bị từ chối (số giờ)
            $table->integer('rework_hours')->nullable()->after('recurring_status');
            
            // Ngày hết hạn làm lại
            $table->timestamp('rework_deadline')->nullable()->after('rework_hours');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tasks', function (Blueprint $table) {
            $table->dropColumn([
                'recurring_type',
                'recurring_start_date',
                'recurring_end_date',
                'recurring_days',
                'last_reset_date',
                'recurring_status',
                'rework_hours',
                'rework_deadline'
            ]);
        });
    }
};
