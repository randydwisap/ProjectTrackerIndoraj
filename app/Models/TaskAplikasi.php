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
                        // Update status marketing jika status task 'Completed'
            if ($taskaplikasi->status === 'Completed' && $taskaplikasi->marketing_id) {
                $marketing = Marketing::find($taskaplikasi->marketing_id);
                if ($marketing) {
                    $marketing->status = 'Completed';
                    $marketing->save();
                }
            }
        });

        static::updating(function ($taskaplikasi) {
            $taskaplikasi->hitungDurasiDanLamaPekerjaan();
               if ($taskaplikasi->status === 'Completed' && $taskaplikasi->marketing_id) {
                $marketing = Marketing::find($taskaplikasi->marketing_id);
                if ($marketing) {
                    $marketing->status = 'Completed';
                    $marketing->save();
                }
            }
        });

        static::created(function ($taskaplikasi) {
            // Update status marketing menjadi "On Hold"
            if ($taskaplikasi->marketing_id) {
                $marketing = Marketing::find($taskaplikasi->marketing_id);
                if ($marketing) {
                    $marketing->status = 'Pengerjaan'; // Ubah status
                    $marketing->save(); // Simpan perubahan
                }
            }

            $durasiProyek = $taskaplikasi->durasi_proyek;
        });
        static::deleting(function ($taskaplikasi) {
        if ($taskaplikasi->marketing_id) {
            $marketing = Marketing::find($taskaplikasi->marketing_id);
            if ($marketing) {
                $marketing->status = 'Persiapan Operasional';
                $marketing->save();
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