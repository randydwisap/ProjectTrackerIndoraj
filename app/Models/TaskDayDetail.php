<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaskDayDetail extends Model
{
    protected $fillable = ['task_detail_id', 'tanggal', 'output', 'status', 'task_id', 'hasil', 'jenis_task_id',];

    public function taskDetail()
    {
        return $this->belongsTo(TaskDetail::class);
    }

    public function task()
    {
        return $this->belongsTo(Task::class); // Corrected to reference Task model
    }

    public function Jenistask()
    {
        return $this->belongsTo(JenisTask::class); // Corrected to reference Task model
    }
    protected static function boot()
    {
        parent::boot();
        static::creating(function ($taskDayDetail) {
            if (!$taskDayDetail->task_id && $taskDayDetail->taskDetail) {
                $taskDayDetail->task_id = $taskDayDetail->taskDetail->task_id;
            }
        });
    }
}
