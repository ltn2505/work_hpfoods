<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Task extends Model
{
    protected $fillable = [
        'title',
        'description', 
        'status',
        'priority',
        'attachments',
        'department_id',
        'assignee_id',
        'creator_id',
        'rejection_reason',
        'finish_note',
        'deadline',
        'is_recurring',
        'recurring_start_date',
        'recurring_days',
        'last_reset_date',
        'rework_hours',
        'rework_deadline',
        'completed_at',
        'is_multi_department'
    ];
    
    protected $casts = [
        'deadline' => 'datetime',
        'attachments' => 'array',
        'is_recurring' => 'boolean',
        'recurring_start_date' => 'date',
        'last_reset_date' => 'date',
        'rework_deadline' => 'datetime',
        'completed_at' => 'datetime',
        'is_multi_department' => 'boolean',
    ];

    public function department(){ return $this->belongsTo(Department::class); }
    public function assignee(){ return $this->belongsTo(User::class, 'assignee_id'); }
    public function creator(){ return $this->belongsTo(User::class, 'creator_id'); }
    public function assignees(){ return $this->hasMany(TaskAssignee::class); }
    public function assignedUsers(){ return $this->belongsToMany(User::class, 'task_assignees'); }
    public function activities(){ 
        return $this->hasMany(TaskActivity::class)->orderBy('created_at', 'desc'); 
    }
    
    // Quan hệ many-to-many với Department
    public function departments()
    {
        return $this->belongsToMany(Department::class, 'department_task');
    }

    /**
     * Kiểm tra xem công việc có cần cập nhật deadline không
     */
    public function needsNewDeadline(): bool
    {
        // Chỉ kiểm tra nếu có bật lặp lại
        if (!$this->is_recurring || !$this->recurring_days) {
            return false;
        }

        // Nếu chưa có last_reset_date, cần cập nhật deadline
        if (!$this->last_reset_date) {
            return true;
        }

        $today = Carbon::today();
        $daysSinceLastReset = $today->diffInDays($this->last_reset_date);
        
        // Cập nhật deadline theo số ngày đã được tính từ task gốc
        return $daysSinceLastReset >= $this->recurring_days;
    }

    /**
     * Tính toán deadline mới dựa trên số ngày của task gốc
     */
    public function calculateNextDeadline(): Carbon
    {
        // Sử dụng ngày bắt đầu lặp lại hoặc ngày hiện tại
        $startDate = $this->recurring_start_date ? Carbon::parse($this->recurring_start_date) : Carbon::today();
        
        // Sử dụng số ngày đã được lưu hoặc tính từ task gốc
        $days = $this->recurring_days ?: 3; // Mặc định 3 ngày
        
        return $startDate->copy()->addDays($days)->endOfDay();
    }

    /**
     * Cập nhật deadline cho công việc lặp lại
     */
    public function updateRecurringDeadline(): void
    {
        $this->update([
            'deadline' => $this->calculateNextDeadline(),
            'last_reset_date' => Carbon::today()
        ]);

        // Tạo activity log
        $this->activities()->create([
            'user_id' => $this->creator_id,
            'action' => 'updated_recurring_deadline',
            'meta' => 'Cập nhật deadline lặp lại: ' . $this->deadline->format('d/m/Y')
        ]);
    }

    /**
     * Kiểm tra xem công việc có cần làm lại không
     */
    public function needsRework(): bool
    {
        if ($this->status !== 'rejected' || !$this->rework_deadline) {
            return false;
        }

        return Carbon::now()->gt($this->rework_deadline);
    }

    /**
     * Set thời gian làm lại khi bị từ chối
     */
    public function setReworkDeadline(int $hours): void
    {
        $this->update([
            'rework_hours' => $hours,
            'rework_deadline' => Carbon::now()->addHours($hours)
        ]);
    }



    /**
     * Kiểm tra xem công việc có đang trong trạng thái làm lại không
     */
    public function isInRework(): bool
    {
        return $this->status === 'rejected' && $this->rework_deadline && Carbon::now()->lt($this->rework_deadline);
    }

    /**
     * Kiểm tra xem có thể hoàn tác công việc không
     */
    public function canUndo(): bool
    {
        if ($this->status !== 'completed' || !$this->completed_at) {
            return false;
        }

        $hoursSinceCompleted = Carbon::now()->diffInHours($this->completed_at);
        return $hoursSinceCompleted <= 3;
    }

    /**
     * Hoàn tác công việc về trạng thái "in_progress"
     */
    public function undoCompletion(): void
    {
        $this->update([
            'status' => 'in_progress',
            'completed_at' => null
        ]);

        // Tạo activity log
        $this->activities()->create([
            'user_id' => $this->assignee_id,
            'action' => 'undo_completion',
            'meta' => 'Hoàn tác trạng thái hoàn thành về "Đang làm"'
        ]);
    }
}

