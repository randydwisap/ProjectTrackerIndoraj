<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JenisTahapAplikasi extends Model
{
    protected $fillable = ['nama_task'];
    use HasFactory;
    public function ReportAplikasi()
    {
        return $this->hasMany(ReportAplikasi::class);
    }
}
