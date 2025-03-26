<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaskWeekOverview extends Model
{
    protected $fillable = [
        'task_detail_id',
        'task_id',
        'nama_week',
        'status',
    ];

    public function taskDetail()
    {
        return $this->belongsTo(TaskDetail::class);
    }    
    public function task()
{
    return $this->belongsTo(Task::class);
}

}
