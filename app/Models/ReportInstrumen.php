<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReportInstrumen extends Model
{
    use HasFactory;

    protected $casts = [
        'gambar' => 'array',
    ];

    protected $fillable = ['task_instrumen_id', 'tanggal', 'jenistahapinstrumen_id', 'keterangan', 'gambar', 'lampiran'];

    public function taskinstrumen()
    {
        return $this->belongsTo(\App\Models\TaskInstrumen::class, 'task_instrumen_id');
    }

    public function jenistahapinstrumen()
    {
        return $this->belongsTo(JenisInstrumen::class);
    }


    protected static function booted()
    {
        $updateTaskTahapan = function ($report) {
            $task = $report->taskinstrumen;

            if ($task) {
                // Update tahap_pengerjaan dengan tahap terbaru (terbesar)
                $latestReport = $task->reportInstrumen()->orderByDesc('jenistahapinstrumen_id')->first();

                if ($latestReport && $latestReport->jenistahapinstrumen) {
                    $task->tahap_pengerjaan = $latestReport->jenistahapinstrumen->nama_task;
                } else {
                    $allRequiredIds = \App\Models\JenisInstrumen::pluck('id')->sort()->values()->all();
                }

                // Ambil semua ID tahapan yang harus ada
                $allRequiredIds = \App\Models\JenisInstrumen::pluck('id')->sort()->values()->all();

                // Ambil semua ID tahapan yang sudah ada di laporan task ini
                $existingIds = $task->reportInstrumen()
                    ->pluck('jenistahapinstrumen_id')
                    ->unique()
                    ->sort()
                    ->values()
                    ->all();
              

                $task->save();
            }
        };

        static::saved($updateTaskTahapan);
        static::deleted($updateTaskTahapan);
    }
}
