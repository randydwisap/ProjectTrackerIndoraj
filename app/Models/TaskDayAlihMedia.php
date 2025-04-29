<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaskDayAlihMedia extends Model
{
    use HasFactory;
    protected $fillable = ['task_week_alih_media_id', 'tanggal', 'output', 'status', 'task_alih_media_id', 'jenis_task_alih_media_id',];

    public function taskWeekAlihMedia()
    {
        return $this->belongsTo(TaskWeekAlihMedia::class, 'task_week_alih_media_id');
    }    
    public function taskAlihMedia()
    {
        return $this->belongsTo(TaskAlihMedia::class); // Corrected to reference Task model
    }

    public function jenisTaskAlihMedia()
    {
        return $this->belongsTo(JenisTaskAlihMedia::class); // Corrected to reference Task model
    }
    protected static function boot()
    {
        parent::boot();
    
        $recalculate = function ($model) {
            $taskAlihMediaId = $model->task_alih_media_id;
            $weekAlihMediaId = $model->task_week_alih_media_id;
            $jenisTaskAlihMediaId = $model->jenis_task_alih_media_id;
            $tanggal = $model->tanggal;
    
            if (!$taskAlihMediaId || !$weekAlihMediaId) return;
    
            // Total output untuk task_id & week_id pada tanggal tertentu
            $totalOutput = self::where('task_alih_media_id', $taskAlihMediaId)
                ->where('task_week_alih_media_id', $weekAlihMediaId)
                ->where('tanggal', $tanggal)
                ->sum('output');
    
            // Total output keseluruhan minggu
            $totalOutputWeek = self::where('task_alih_media_id', $taskAlihMediaId)
                ->where('task_week_alih_media_id', $weekAlihMediaId)
                ->sum('output');
    
            // Total output untuk step tertentu (jenis_task_id)
            $totalOutputWeekStep = self::where('task_alih_media_id', $taskAlihMediaId)
                ->where('task_week_alih_media_id', $weekAlihMediaId)
                ->where('jenis_task_alih_media_id', $jenisTaskAlihMediaId)
                ->sum('output');
    
            // Update task_week_overviews
            $week = \App\Models\TaskWeekAlihMedia::find($weekAlihMediaId);
            if ($week) {
                $week->volume_dikerjakan = $totalOutputWeek;
    
                switch ($jenisTaskAlihMediaId) {
                    case 1:
                        $week->total_step1 = $totalOutputWeekStep;
                        break;
                    case 2:
                        $week->total_step2 = $totalOutputWeekStep;
                        break;
                    case 3:
                        $week->total_step3 = $totalOutputWeekStep;
                        break;
                    case 4:
                        $week->total_step4 = $totalOutputWeekStep;
                        break;
                }
    
                $week->save();
            }
    
            // Update status hanya jika target_perday masih tercapai
            $taskAlihMedia = $model->taskAlihMedia;
            if ($taskAlihMedia) {
                $targetPerDay = $taskAlihMedia->target_perday;                
                $output = $totalOutput;  // Misalnya, totalOutput adalah output yang ingin dihitung
            
                if ($targetPerDay > 0) {
                    $percent = ($output / $targetPerDay) * 100;
            
                    if ($percent >= 100) {
                        $status = 'On Track';
                    } elseif ($percent > 50) {
                        $status = 'Behind Schedule';
                    } else {
                        $status = 'Far Behind Schedule';
                    }
                } else {
                    $status = 'Invalid Target';
                }
            
                $newStatus = $status;            
    
                self::where('task_alih_media_id', $taskAlihMediaId)
                    ->where('task_week_alih_media_id', $weekAlihMediaId)
                    ->where('tanggal', $tanggal)
                    ->update(['status' => $newStatus]);
            }
        };
    
        // Saat data disimpan (created / updated)
        static::saved($recalculate);
    
        // Saat data dihapus
        static::deleted($recalculate);
    }    
}
