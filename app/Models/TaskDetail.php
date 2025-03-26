<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaskDetail extends Model
{
    protected $fillable = [
        'task_id',
        'jenis_task_id',
        'nama_week',
        'total_volume',
        'volume_dikerjakan',
        'resiko_keterlambatan',
        'hasil',
    ];
    protected $casts = [
        'task_id' => 'integer',
    ];
    
    public function task()
    {
        return $this->belongsTo(Task::class);
    }
    
    

/*************  ✨ Codeium Command ⭐  *************/
    /**
     * Get the jenis task associated with the task detail.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */

/******  fbf70282-0ec0-4363-82b8-ed73b4ff6c93  *******/
    public function jenisTask()
    {
        return $this->belongsTo(JenisTask::class);
    }

    public function taskDayDetails()
    {
        return $this->hasMany(TaskDayDetail::class);
    }
}
