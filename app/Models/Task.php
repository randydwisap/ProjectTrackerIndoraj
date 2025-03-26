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
        'jenis_arsip',
        'deskripsi_pekerjaan',
        'lama_pekerjaan',
        'target_perminggu',
        'target_perday',
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
        });

        static::updating(function ($task) {
            $task->hitungDurasiDanLamaPekerjaan();
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
                foreach ($jenisTasks as $jenisTask) {
                    $taskDetail = new TaskDetail();
                    $taskDetail->task_id = $task->id;
                    $taskDetail->jenis_task_id = $jenisTask->id; // Use the current jenis_task id
                    $taskDetail->nama_week = "Week " . $i;
                    $taskDetail->total_volume = $task->target_perminggu; // Set total_volume from target_perminggu
                    $taskDetail->volume_dikerjakan = 0; // Set default or calculate as needed
                    $taskDetail->save();
                }
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

    public function taskDetails()
    {
        return $this->hasMany(TaskDetail::class);
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