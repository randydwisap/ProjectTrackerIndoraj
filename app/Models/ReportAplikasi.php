<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReportAplikasi extends Model
{
    use HasFactory;

    protected $casts = [
        'gambar' => 'array',
    ];

    protected $fillable = ['task_aplikasi_id', 'tanggal', 'jenistahapaplikasi_id', 'keterangan', 'gambar', 'lampiran'];

    public function taskaplikasi()
    {
        return $this->belongsTo(\App\Models\TaskAplikasi::class, 'task_aplikasi_id');
    }

    public function jenistahapaplikasi()
    {
        return $this->belongsTo(JenisTahapAplikasi::class);
    }

    protected static function booted()
    {
        $updateTaskTahapan = function ($report) {
            $task = $report->taskaplikasi;

            if ($task) {
                // Update tahap_pengerjaan dengan tahap terbaru (terbesar)
                $latestReport = $task->reportAplikasi()->orderByDesc('jenistahapaplikasi_id')->first();

                if ($latestReport && $latestReport->jenistahapaplikasi) {
                    $task->tahap_pengerjaan = $latestReport->jenistahapaplikasi->nama_task;
                } else {
                    $allRequiredIds = \App\Models\JenisTahapAplikasi::pluck('id')->sort()->values()->all();
                }

                // Ambil semua ID tahapan yang harus ada
                $allRequiredIds = \App\Models\JenisTahapAplikasi::pluck('id')->sort()->values()->all();

                // Ambil semua ID tahapan yang sudah ada di laporan task ini
                $existingIds = $task->reportAplikasi()
                    ->pluck('jenistahapaplikasi_id')
                    ->unique()
                    ->sort()
                    ->values()
                    ->all();

                // Hitung progress berdasarkan durasi dan tahapan
                $totalDurasiHari = $task->lama_pengerjaan;
                $tglMulai = $task->tgl_mulai;
                $dateNow = $report->tanggal;

                $progressWaktu = (strtotime($dateNow) - strtotime($tglMulai)) / 86400;
                $progressWaktu = max(0, round($progressWaktu)); // jangan negatif

                $progressStep = count($existingIds);
                $totalStep = count($allRequiredIds);

                // STATUS pengerjaan
                if ($progressStep >= $totalStep) {
                    $task->status = 'Completed';
                } elseif ($progressStep + 20 < $progressWaktu) {
                    $task->status = 'Far Behind Schedule';
                } elseif ($progressStep + 10 < $progressWaktu) {
                    $task->status = 'Behind Schedule';
                } else {
                    $task->status = 'On Track';
                }

                // RESIKO keterlambatan
                $selisih = $progressWaktu - $progressStep;

                    if ($selisih <= 3) {
                        $task->resiko_keterlambatan = 'Low';
                    } elseif ($selisih <= 7) {
                        $task->resiko_keterlambatan = 'Medium';
                    } else {
                        $task->resiko_keterlambatan = 'High';
                    }

                $task->save();
            }
        };

        static::saved($updateTaskTahapan);
        static::deleted($updateTaskTahapan);
    }
}
