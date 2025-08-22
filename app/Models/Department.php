<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    protected $fillable = ['name'];
    
    public function users(){ 
        return $this->hasMany(User::class); 
    }
    
    public function tasks(){ 
        return $this->hasMany(Task::class); 
    }
    
    // Quan hệ many-to-many để lấy mọi công việc (kể cả đa phòng ban)
    public function tasksForView()
    {
        return $this->belongsToMany(Task::class, 'department_task')
                    ->with('creator', 'assignedUsers');
    }
}

