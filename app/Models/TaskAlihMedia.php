<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class TaskAlihMedia extends Model
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
        'target_perday',        
        'telepon',        
        'marketing_id',
    ];

    public function Telepon()
    {
        return $this->belongsTo(User::class, 'Telepon');
    }

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

        static::saving(function ($taskAlihMedia) {
            $taskAlihMedia->hitungDurasiDanLamaPekerjaan();
            $taskAlihMedia->hitungStatus();
            $taskAlihMedia->hitungResiko();  
            $taskAlihMedia->hitungTotalStep();   
            $taskAlihMedia->hitungTahap();
        });

        static::updating(function ($taskAlihMedia) {
            $taskAlihMedia->hitungDurasiDanLamaPekerjaan();
            $taskAlihMedia->hitungStatus();
            $taskAlihMedia->hitungResiko(); 
            $taskAlihMedia->hitungTotalStep();     
            $taskAlihMedia->hitungTahap();
        });

        static::created(function ($taskAlihMedia) {
            // Update status marketing menjadi "On Hold"
            if ($taskAlihMedia->marketing_id) {
                $marketing = Marketing::find($taskAlihMedia->marketing_id);
                if ($marketing) {
                    $marketing->status = 'On Hold'; // Ubah status
                    $marketing->save(); // Simpan perubahan
                }
            }

            $durasiProyek = $taskAlihMedia->durasi_proyek;
            $jenisTaskAlihhMedia = JenisTaskAlihMedia::all(); // Assuming you have a model for JenisTask
        
            for ($i = 1; $i <= $durasiProyek; $i++) {               
                    $taskWeekAlihMedia = new TaskWeekAlihMedia();
                    $taskWeekAlihMedia->task_alih_media_id = $taskAlihMedia->id;
                    $taskWeekAlihMedia->nama_week = "Week " . $i;
                    $taskWeekAlihMedia->total_volume = $taskAlihMedia->target_perminggu; // Set total_volume from target_perminggu
                    $taskWeekAlihMedia->volume_dikerjakan = 0; // Set default or calculate as needed
                    $taskWeekAlihMedia->total_step1 = 0;
                    $taskWeekAlihMedia->total_step2 = 0;
                    $taskWeekAlihMedia->total_step3 = 0;
                    $taskWeekAlihMedia->total_step4 = 0;
                    $taskWeekAlihMedia->save();                
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

    public function hitungStatus()
    {
        if (
        $this->dikerjakan_step4 >= $this->volume_arsip &&
        $this->dikerjakan_step3 >= $this->volume_arsip &&
        $this->dikerjakan_step2 >= $this->volume_arsip &&
        $this->dikerjakan_step1 >= $this->volume_arsip
    ) {
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
    public function hitungTahap()
    {
        if ($this->dikerjakan_step4 > 0) {
            $jenisTaskAlihMedia = JenisTaskAlihMedia::find(4);
        } elseif ($this->dikerjakan_step3 > 0) {
            $jenisTaskAlihMedia = JenisTaskAlihMedia::find(3);
        } elseif ($this->dikerjakan_step2 > 0) {
            $jenisTaskAlihMedia = JenisTaskAlihMedia::find(2);
        } else {
            $jenisTaskAlihMedia = JenisTaskAlihMedia::find(1);
        }
        // Cek apakah task ditemukan
        if ($jenisTaskAlihMedia) {
            $this->tahap_pengerjaan = $jenisTaskAlihMedia->nama_task;
        }
    }

    public function hitungResiko()
{
    $jumlahOnTrack = \App\Models\TaskWeekAlihMedia::where('task_alih_media_id', $this->id)
    ->where('status', 'On Track')
    ->count();
    // ✅ Hitung total jumlah detail harian dalam weekOverview ini
    $totalDetails = \App\Models\TaskWeekAlihMedia::where('task_alih_media_id', $this->id)->count();
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
        $total = \App\Models\TaskDayAlihMedia::where('task_alih_media_id', $this->id)
            ->where('jenis_task_alih_media_id', $i)
            ->sum('output');

        $this->{"dikerjakan_step{$i}"} = $total;
    }
}

public function taskDayAlihMedia()
{
    return $this->hasMany(TaskDayAlihMedia::class);
}

public function taskWeekAlihMedia()
{
    return $this->hasMany(TaskWeekAlihMedia::class);
}
public function taskAlihMedia()
{
return $this->belongsTo(TaskAlihMedia::class);
}

public function marketing()
{
return $this->belongsTo(Marketing::class);
}
}
