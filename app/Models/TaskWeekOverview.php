<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaskWeekOverview extends Model
{
    protected $fillable = [
        
        'task_id',
        'nama_week',
        'status',
        'resiko_keterlambatan',
        'total_volume',
        'volume_dikerjakan',
        'arsip',
        'inarsip',
        'target_minggu',
        'total_step1',
        'total_step2',
        'total_step3',
        'total_step4',
        
    ];

// Di dalam model TaskWeekOverview
public function taskDayDetails()
{
    return $this->hasMany(TaskDayDetail::class);
}

    public function task()
{
    return $this->belongsTo(Task::class);
}
protected static function booted()
{
    static::saving(function ($weekOverview) {
        $task = $weekOverview->task;

        // ✅ Perhitungan status berdasarkan volume_dikerjakan
        if ($task && $weekOverview->target_minggu > 0) {
            $persentase = ($weekOverview->volume_dikerjakan / $weekOverview->target_minggu) * 100;

            if ($persentase >= 100) {
                $weekOverview->status = 'On Track';
            } elseif ($persentase >= 50) {
                $weekOverview->status = 'Behind Schedule';
            } else {
                $weekOverview->status = 'Far Behind Schedule';
            }
        }

        // ✅ Hitung jumlah taskDayDetails dengan status "On Track"
        $jumlahOnTrack = \App\Models\TaskDayDetail::where('task_week_overview_id', $weekOverview->id)
            ->where('status', 'On Track')
            ->count();

        // ✅ Hitung total jumlah detail harian dalam weekOverview ini
        $totalDetails = \App\Models\TaskDayDetail::where('task_week_overview_id', $weekOverview->id)->count();

        // ✅ Update resiko_keterlambatan berdasarkan jumlah On Track
        if ($jumlahOnTrack === $totalDetails && $totalDetails > 0) {
            $weekOverview->resiko_keterlambatan = 'Low';
        } elseif ($jumlahOnTrack <= 1) {
            $weekOverview->resiko_keterlambatan = 'High';
        } elseif ($jumlahOnTrack <= 3) {
            $weekOverview->resiko_keterlambatan = 'Medium';
        } else {
            $weekOverview->resiko_keterlambatan = 'Medium'; // fallback default
        }

        // ✅ Hitung total volume_dikerjakan dari semua week overview untuk task ini
        $totalDikerjakan = \App\Models\TaskDayDetail::where('task_id', $weekOverview->task_id)
            ->sum('output');

        // ✅ Update kolom volume_dikerjakan di tabel tasks
        \App\Models\Task::where('id', $weekOverview->task_id)
            ->update(['volume_dikerjakan' => $totalDikerjakan]);

        $totalArsip = \App\Models\TaskDayDetail::where('task_id', $weekOverview->task_id)
        ->where('jenis_task_id', 1)
        ->sum('hasil');

        $totalUniqueDatesInput = \App\Models\TaskDayDetail::where('task_id', $weekOverview->task_id)
        ->distinct('tanggal')
        ->count('tanggal');

        $totalTargetMingguDinamis = $totalUniqueDatesInput * $task->target_perhari;

        $task = \App\Models\Task::find($weekOverview->task_id);
        $task->hasil_pemilahan = $totalArsip;
        $task->save(); // Ini akan memicu saving & updating

   
    });
}
}
