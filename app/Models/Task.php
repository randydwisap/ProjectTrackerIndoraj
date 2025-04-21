<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Carbon\Carbon;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'pekerjaan',
        'klien',
        'tahap_pengerjaan',
        'status',
        'resiko_keterlambatan',
        'tgl_mulai',
        'tgl_selesai',
        'durasi_proyek',
        'jumlah_sdm',
        'project_manager', // Ini menyimpan user_id
        'no_telp_pm',
        'nilai_proyek',
        'link_rab',
        'lokasi',
        'pelaksana',
        'volume_arsip',
        'volume_dikerjakan',
        'dikerjakan_step1',
        'dikerjakan_step2',
        'dikerjakan_step3',
        'dikerjakan_step4',
        'jenis_arsip',
        'deskripsi_pekerjaan',
        'lama_pekerjaan',
        'target_perminggu',
        'target_perminggu_arsip',
        'target_perday',
        'target_perday_arsip',
        'telepon',
        'hasil_pemilahan',
        'marketing_id',
    ];

    // Ubah `pelaksana` menjadi array secara otomatis
    protected $casts = [
        'pelaksana' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'project_manager'); // Relasi ke User
    }
    
    protected static function boot()
    {
        parent::boot();

        static::saving(function ($task) {
            $task->hitungDurasiDanLamaPekerjaan();
            $task->hitungTargetPerHariArsip();
            $task->hitungTargetPerMingguArsip();
            $task->hitungStatus();
            $task->hitungResiko();  
            $task->hitungTotalStep();            
        });

        static::updating(function ($task) {
            $task->hitungDurasiDanLamaPekerjaan();
            $task->hitungTargetPerHariArsip();
            $task->hitungTargetPerMingguArsip();
            $task->hitungStatus();
            $task->hitungResiko(); 
            $task->hitungTotalStep();     
        });

        static::created(function ($task) {
            // Update status marketing menjadi "On Hold"
            if ($task->marketing_id) {
                $marketing = Marketing::find($task->marketing_id);
                if ($marketing) {
                    $marketing->status = 'On Hold'; // Ubah status
                    $marketing->save(); // Simpan perubahan
                }
            }

            $durasiProyek = $task->durasi_proyek;
            $jenisTasks = JenisTask::all(); // Assuming you have a model for JenisTask
        
            for ($i = 1; $i <= $durasiProyek; $i++) {               
                    $taskWeek = new TaskWeekOverview();
                    $taskWeek->task_id = $task->id;
                    $taskWeek->nama_week = "Week " . $i;
                    $taskWeek->total_volume = $task->target_perminggu; // Set total_volume from target_perminggu
                    $taskWeek->volume_dikerjakan = 0; // Set default or calculate as needed
                    $taskWeek->total_step1 = 0;
                    $taskWeek->total_step2 = 0;
                    $taskWeek->total_step3 = 0;
                    $taskWeek->total_step4 = 0;
                    $taskWeek->save();                
            }
        });
    }

    /**
     * Menghitung durasi proyek dalam minggu dan lama pekerjaan dalam hari.
     */
    public function hitungDurasiDanLamaPekerjaan()
    {
        if ($this->tgl_mulai && $this->tgl_selesai) {
            $start = Carbon::parse($this->tgl_mulai);
            $end = Carbon::parse($this->tgl_selesai);
            
            // Hitung lama pekerjaan dalam hari
            $this->lama_pekerjaan = $start->diffInDays($end);
            
            // Hitung durasi proyek dalam minggu
            $this->durasi_proyek = ceil($this->lama_pekerjaan / 7);
        } else {
            $this->lama_pekerjaan = 0;
            $this->durasi_proyek = 0;
        }
    }

    public function hitungTargetPerHariArsip()
{
    if ($this->lama_pekerjaan > 0) {
        $totalArsip = \App\Models\TaskDayDetail::where('task_id', $this->id)
                        ->where('jenis_task_id', 1)
                        ->sum('hasil');

        $this->target_perday_arsip = $totalArsip / $this->lama_pekerjaan;
    } else {
        $this->target_perday_arsip = 0;
    }
}

public function hitungTargetPerMingguArsip()
{
    if ($this->durasi_proyek > 0) {
        $totalArsip = \App\Models\TaskDayDetail::where('task_id', $this->id)
                        ->where('jenis_task_id', 1)
                        ->sum('hasil');

        $this->target_perminggu_arsip = $totalArsip / $this->durasi_proyek;
    } else {
        $this->target_perminggu_arsip = 0;
    }
}

public function hitungStatus()
{
    if ($this->dikerjakan_step4 >= $this->hasil_pemilahan) {
        $this->status = 'Completed';
    } elseif ($this->volume_arsip > 0) {
        $persentase = ($this->volume_dikerjakan / $this->volume_arsip) * 100;

        if ($persentase >= 50) {
            $this->status = 'Behind Schedule';
        } else {
            $this->status = 'Far Behind Schedule';
        }
    } else {
        $this->status = 'Not Started';
    }
}

public function hitungResiko()
{
    $jumlahOnTrack = \App\Models\TaskWeekOverview::where('task_id', $this->id)
    ->where('status', 'On Track')
    ->count();
    // ✅ Hitung total jumlah detail harian dalam weekOverview ini
    $totalDetails = \App\Models\TaskWeekOverview::where('task_id', $this->id)->count();
    // ✅ Update resiko_keterlambatan berdasarkan jumlah On Track
    if ($jumlahOnTrack === $totalDetails && $totalDetails > 0) {
        $this->resiko_keterlambatan = 'Low';
    } elseif ($jumlahOnTrack <= 1) {
        $this->resiko_keterlambatan = 'High';
    } elseif ($jumlahOnTrack <= 3) {
        $this->resiko_keterlambatan = 'Medium';
    } else {
        $this->resiko_keterlambatan = 'Medium'; // fallback default
    }    
}

public function hitungTotalStep()
{
    for ($i = 1; $i <= 4; $i++) {
        $total = \App\Models\TaskDayDetail::where('task_id', $this->id)
            ->where('jenis_task_id', $i)
            ->sum('output');

        $this->{"dikerjakan_step{$i}"} = $total;
    }
}


    public function taskDayDetails()
    {
        return $this->hasMany(TaskDayDetail::class);
    }

    public function taskWeekOverviews()
    {
        return $this->hasMany(TaskWeekOverview::class);
    }
    public function task()
{
    return $this->belongsTo(Task::class);
}

public function marketing()
{
    return $this->belongsTo(Marketing::class);
}


}