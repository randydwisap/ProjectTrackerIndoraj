<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReportFumigasi extends Model
{
    use HasFactory;
    protected $fillable = ['task_fumigasi_id', 'tanggal', 'jenistahapfumigasi_id', 'keterangan', 'gambar', 'lampiran'];

    public function taskfumigasi()
    {
        return $this->belongsTo(\App\Models\TaskFumigasi::class, 'task_fumigasi_id');
    }

    public function jenistahapfumigasi()
    {
        return $this->belongsTo(JenisTahapFumigasi::class);
    }
    protected $casts = [
        'gambar' => 'array',
    ];


    // Hook untuk update Taskfumigasi.tahap_pengerjaan setelah simpan
    protected static function booted()
    {
        static::saved(function ($report) {
            $task = $report->taskfumigasi;
            $tahap = $report->jenistahapfumigasi;

            if ($task && $tahap) {
                $task->tahap_pengerjaan = $tahap->nama_task;
                $task->save();
            }
        });
    }
}
