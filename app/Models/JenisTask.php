<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JenisTask extends Model
{
    protected $fillable = ['nama_task'];

    public function taskDetails()
    {
        return $this->hasMany(TaskDetail::class);
    }
}
