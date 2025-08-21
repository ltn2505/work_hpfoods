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
            // Xóa các cột không cần thiết cho recurring đơn giản
            $table->dropColumn([
                'recurring_type',
                'recurring_start_date', 
                'recurring_end_date',
                'recurring_status'
            ]);
            
            // Thêm cột đơn giản cho recurring
            $table->boolean('is_recurring')->default(false)->after('deadline');
            
            // Cập nhật comment cho cột recurring_days đã tồn tại
            $table->integer('recurring_days')->nullable()->comment('Số ngày của task gốc')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tasks', function (Blueprint $table) {
            // Khôi phục lại cấu trúc cũ
            $table->dropColumn(['is_recurring']);
            
            $table->enum('recurring_type', ['none', 'daily', '3_days', 'weekly', 'custom'])->default('none')->after('deadline');
            $table->date('recurring_start_date')->nullable()->after('recurring_type');
            $table->date('recurring_end_date')->nullable()->after('recurring_start_date');
            $table->enum('recurring_status', ['active', 'paused'])->default('active')->after('recurring_days');
        });
    }
};
