<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class TaskDayDetail extends Model
{
    protected $fillable = ['task_week_overview_id', 'tanggal', 'output', 'status', 'task_id', 'hasil', 'jenis_task_id','hasil_inarsip',];

    public function taskWeekOverview()
    {
        return $this->belongsTo(TaskWeekOverview::class, 'task_week_overview_id');
    }    
    public function task()
    {
        return $this->belongsTo(Task::class); // Corrected to reference Task model
    }

    public function jenisTask()
    {
        return $this->belongsTo(JenisTask::class); // Corrected to reference Task model
    }

    // protected static function boot()
    // {
    //     parent::boot();
    
    //     static::saved(function ($model) {
    //         $taskId = $model->task_id;
    //         $weekId = $model->task_week_overview_id;
    //         $jenisTaskId = $model->jenis_task_id;
    //         $tanggal = $model->tanggal;
    
    //         if (!$taskId || !$weekId) return;
    
    //         // Total output untuk kombinasi task_id dan task_week_overview_id pada tanggal tertentu
    //         $totalOutput = self::where('task_id', $taskId)
    //             ->where('task_week_overview_id', $weekId)
    //             ->where('tanggal', $tanggal)
    //             ->sum('output');
    //         $totalOutputWeek = self::where('task_id', $taskId)
    //             ->where('task_week_overview_id', $weekId)
    //             ->sum('output');
    //         $totalOutputWeekStep = self::where('task_id', $jenisTaskId)
    //             ->where('task_week_overview_id', $weekId)
    //             ->where('jenisTask_id', $jenisTaskId)
    //             ->sum('output');
    //         // Update task_week_overviews.volume_dikerjakan
    //         $week = \App\Models\TaskWeekOverview::find($weekId);
    //         $week->volume_dikerjakan = $totalOutputWeek;
    //         $week->save(); // ini akan memicu event saved/updated
    
    //         // Ambil task untuk dapatkan target_perday
    //         $task = $model->task;
    
    //         if ($task && $totalOutput >= $task->target_perday) {
    //             // Update semua task_day_detail pada tanggal yang sama jadi On Track
    //             self::where('task_id', $taskId)
    //                 ->where('task_week_overview_id', $weekId)
    //                 ->where('tanggal', $tanggal)
    //                 ->update(['status' => 'On Track']);
    //         }
    //     });
    // }    
    protected static function boot()
    {
        parent::boot();
    
        $recalculate = function ($model) {
            $taskId = $model->task_id;
            $weekId = $model->task_week_overview_id;
            $jenisTaskId = $model->jenis_task_id;
            $tanggal = $model->tanggal;
    
            if (!$taskId || !$weekId) return;
    
            // Total output untuk task_id & week_id pada tanggal tertentu
            $totalOutput = self::where('task_id', $taskId)
                ->where('task_week_overview_id', $weekId)
                ->where('tanggal', $tanggal)
                ->sum('output');
    
            // Total output keseluruhan minggu
            $totalOutputWeek = self::where('task_id', $taskId)
                ->where('task_week_overview_id', $weekId)
                ->sum('output');
    
            // Total output untuk step tertentu (jenis_task_id)
            $totalOutputWeekStep = self::where('task_id', $taskId)
                ->where('task_week_overview_id', $weekId)
                ->where('jenis_task_id', $jenisTaskId)
                ->sum('output');
            $totalOutputWeekArsip = self::where('task_id', $taskId)
                ->where('task_week_overview_id', $weekId)
                ->where('jenis_task_id', $jenisTaskId)
                ->sum('hasil');
            $totalOutputWeekInarsip = self::where('task_id', $taskId)
                ->where('task_week_overview_id', $weekId)
                ->where('jenis_task_id', $jenisTaskId)
                ->sum('hasil_inarsip');
    
            // Update task_week_overviews
            $week = \App\Models\TaskWeekOverview::find($weekId);
            if ($week) {
                $week->volume_dikerjakan = $totalOutputWeek;
    
                switch ($jenisTaskId) {
                    case 1:
                        $week->total_step1 = $totalOutputWeekStep;
                        $week->arsip = $totalOutputWeekArsip;
                        $week->inarsip = $totalOutputWeekInarsip;
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
            $task = $model->task;
            if ($task) {
                $targetPerDay = $task->target_perday;                
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
    
                self::where('task_id', $taskId)
                    ->where('task_week_overview_id', $weekId)
                    ->where('tanggal', $tanggal)
                    ->update(['status' => $newStatus]);

                LogAktivitas::create([
                'user_id' => Auth::id(), // ID user yang login
                'menu' => 'Task Day Pengolahan Arsip',
                'menu_id' => $model->id,
                'aksi' => 'Update/Buat/Hapus',
                'waktu' => now(),
            ]);
            }
        };
    
        // Saat data disimpan (created / updated)
        static::saved($recalculate);
    
        // Saat data dihapus
        static::deleted($recalculate);
    }    
}
