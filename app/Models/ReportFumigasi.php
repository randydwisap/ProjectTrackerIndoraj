<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReportFumigasi extends Model
{
    use HasFactory;

    protected $fillable = ['task_fumigasi_id', 'tanggal', 'jenistahapfumigasi_id', 'keterangan', 'gambar', 'lampiran'];

    protected $casts = [
        'gambar' => 'array',
    ];

    public function taskfumigasi()
    {
        return $this->belongsTo(\App\Models\TaskFumigasi::class, 'task_fumigasi_id');
    }

    public function jenistahapfumigasi()
    {
        return $this->belongsTo(JenisTahapFumigasi::class);
    }

    protected static function booted()
    {
        $updateTaskTahapan = function ($report) {
            $task = $report->taskfumigasi;

            if ($task) {
                // Update tahap_pengerjaan dengan tahap terbaru (terbesar)
                $latestReport = $task->reportFumigasi()->orderByDesc('jenistahapfumigasi_id')->first();

                if ($latestReport && $latestReport->jenistahapfumigasi) {
                    $task->tahap_pengerjaan = $latestReport->jenistahapfumigasi->nama_task;
                } else {
                    $task->tahap_pengerjaan = \App\Models\JenisTahapFumigasi::find(1)->nama_task;
                }

                // Ambil semua ID tahapan yang harus ada
                $allRequiredIds = \App\Models\JenisTahapFumigasi::pluck('id')->sort()->values()->all();

                // Ambil semua ID tahapan yang sudah ada di laporan task ini
                $existingIds = $task->reportFumigasi()
                    ->pluck('jenistahapfumigasi_id')
                    ->unique()
                    ->sort()
                    ->values()
                    ->all();

                // Hitung progress berdasarkan durasi dan tahapan
                $totalDurasiHari = $task->lama_pengerjaan;
                $tglMulai = $task->tgl_mulai;
                $dateNow = $report->tanggal;

                $progressWaktu = (strtotime($dateNow) - strtotime($tglMulai)) / 86400;
                $progressWaktu = max(0, round($progressWaktu));

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
