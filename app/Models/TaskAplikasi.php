<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Carbon\Carbon;

class TaskAplikasi extends Model
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
        'volume',
        'jenis_arsip',
        'deskripsi_pekerjaan',
        'lama_pekerjaan',
        'target_perminggu',
        'target_perday',
        'telepon',
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

        static::saving(function ($taskaplikasi) {
            $taskaplikasi->hitungDurasiDanLamaPekerjaan();
        });

        static::updating(function ($taskaplikasi) {
            $taskaplikasi->hitungDurasiDanLamaPekerjaan();
        });

        static::created(function ($taskaplikasi) {
            // Update status marketing menjadi "On Hold"
            if ($taskaplikasi->marketing_id) {
                $marketing = Marketing::find($taskaplikasi->marketing_id);
                if ($marketing) {
                    $marketing->status = 'On Hold'; // Ubah status
                    $marketing->save(); // Simpan perubahan
                }
            }

            $durasiProyek = $taskaplikasi->durasi_proyek;
            //$jenisTasksAplikasi = JenisTaskAplikasi::all(); // Assuming you have a model for JenisTask
        
            // for ($i = 1; $i <= $durasiProyek; $i++) {
            //     foreach ($jenisTasks as $jenisTask) {
            //         $taskDetail = new TaskDetail();
            //         $taskDetail->task_id = $task->id;
            //         $taskDetail->jenis_task_id = $jenisTask->id; // Use the current jenis_task id
            //         $taskDetail->nama_week = "Week " . $i;
            //         $taskDetail->total_volume = $task->target_perminggu; // Set total_volume from target_perminggu
            //         $taskDetail->volume_dikerjakan = 0; // Set default or calculate as needed
            //         $taskDetail->save();
            //     }
            // }
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
    public function taskaplikasi()
{
    return $this->belongsTo(TaskAplikasi::class);
}
public function jenistahapaplikasi()
{
    return $this->hasMany(JenisTahapAplikasi::class);
}
public function reportaplikasi()
{
    return $this->hasMany(ReportAplikasi::class);
}
public function marketing()
{
    return $this->belongsTo(Marketing::class);
}


}