<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JenisTask extends Model
{
    protected $fillable = ['nama_task'];

    public function taskWeekOverviews()
    {
        return $this->hasMany(TaskWeekOverview::class);
    }
    
    public function taskDayDetails()
    {
        return $this->hasMany(TaskDayDetail::class);
    }
}
