<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JenisTaskAlihMedia extends Model
{
    use HasFactory;
    protected $fillable = ['nama_task'];

    public function taskWeekAlihMedia()
    {
        return $this->hasMany(TaskWeekAlihMedia::class);
    }
    
    public function taskDayAlihMedia()
    {
        return $this->hasMany(TaskDayAlihMedia::class);
    }
}
