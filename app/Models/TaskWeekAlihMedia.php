<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaskWeekAlihMedia extends Model
{
    use HasFactory;
    protected $fillable = [
        
        'task_id',
        'nama_week',
        'status',
        'resiko_keterlambatan',
        'total_volume',
        'volume_dikerjakan',
        'total_step1',
        'total_step2',
        'total_step3',
        'total_step4',
    ];

    public function taskDayAlihMedia()
{
    return $this->hasMany(TaskDayAlihMedia::class);
}

    public function taskAlihMedia()
{
    return $this->belongsTo(TaskAlihMedia::class);
}
protected static function booted()
{
    static::saving(function ($weekAlihMedia) {
        $taskAlihMedia = $weekAlihMedia->taskAlihMedia;

        // ✅ Perhitungan status berdasarkan volume_dikerjakan
        if ($taskAlihMedia && $taskAlihMedia->target_perminggu > 0) {
            $persentase = ($weekAlihMedia->volume_dikerjakan / $taskAlihMedia->target_perminggu) * 100;

            if ($persentase >= 100) {
                $weekAlihMedia->status = 'On Track';
            } elseif ($persentase >= 50) {
                $weekAlihMedia->status = 'Behind Schedule';
            } else {
                $weekAlihMedia->status = 'Far Behind Schedule';
            }
        }

        // ✅ Hitung jumlah taskDayDetails dengan status "On Track"
        $jumlahOnTrack = \App\Models\TaskDayAlihMedia::where('task_week_alih_media_id', $weekAlihMedia->id)
            ->where('status', 'On Track')
            ->count();

        // ✅ Hitung total jumlah detail harian dalam weekOverview ini
        $totalDetails = \App\Models\TaskDayAlihMedia::where('task_week_alih_media_id', $weekAlihMedia->id)->count();

        // ✅ Update resiko_keterlambatan berdasarkan jumlah On Track
        if ($jumlahOnTrack === $totalDetails && $totalDetails > 0) {
            $weekAlihMedia->resiko_keterlambatan = 'Low';
        } elseif ($jumlahOnTrack <= 1) {
            $weekAlihMedia->resiko_keterlambatan = 'High';
        } elseif ($jumlahOnTrack <= 3) {
            $weekAlihMedia->resiko_keterlambatan = 'Medium';
        } else {
            $weekAlihMedia->resiko_keterlambatan = 'Medium'; // fallback default
        }

        // ✅ Hitung total volume_dikerjakan dari semua week overview untuk task ini
        $totalDikerjakan = \App\Models\TaskDayAlihMedia::where('task_alih_media_id', $weekAlihMedia->task_alih_media_id)
            ->sum('output');

        // ✅ Update kolom volume_dikerjakan di tabel tasks
        \App\Models\TaskAlihMedia::where('id', $weekAlihMedia->task_alih_media_id)
            ->update(['volume_dikerjakan' => $totalDikerjakan]);

        $task = \App\Models\TaskAlihMedia::find($weekAlihMedia->task_alih_media_id);
        $task->save(); // Ini akan memicu saving & updating

   
    });
}
}
