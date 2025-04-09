<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReportAplikasi extends Model
{
    use HasFactory;

    protected $fillable = ['task_aplikasi_id', 'tanggal', 'jenistahapaplikasi_id', 'keterangan', 'gambar', 'lampiran'];

    public function taskaplikasi()
    {
        return $this->belongsTo(\App\Models\TaskAplikasi::class, 'task_aplikasi_id');
    }

    public function jenistahapaplikasi()
    {
        return $this->belongsTo(JenisTahapAplikasi::class);
    }

    // Hook untuk update TaskAplikasi.tahap_pengerjaan setelah simpan
    protected static function booted()
    {
        static::saved(function ($report) {
            $task = $report->taskaplikasi;
            $tahap = $report->jenistahapaplikasi;

            if ($task && $tahap) {
                $task->tahap_pengerjaan = $tahap->nama_task;
                $task->save();
            }
        });
    }
}
