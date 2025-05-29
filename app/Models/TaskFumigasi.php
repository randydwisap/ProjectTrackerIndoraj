<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class TaskFumigasi extends Model
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
        'alamat',
        'no_st',
        'tgl_surat',
    ];

    protected $casts = [
        'pelaksana' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'project_manager');
    }

    protected static function boot()
    {
        parent::boot();

        static::saving(function ($taskfumigasi) {
            $taskfumigasi->hitungDurasiDanLamaPekerjaan();

            // Update status marketing jika status task 'Completed'
            if ($taskfumigasi->status === 'Completed' && $taskfumigasi->marketing_id) {
                $marketing = Marketing::find($taskfumigasi->marketing_id);
                if ($marketing) {
                    $marketing->status = 'Completed';
                    $marketing->save();
                }
            }
        });

        static::updating(function ($taskfumigasi) {
            $taskfumigasi->hitungDurasiDanLamaPekerjaan();

            // Update status marketing jika status task 'Completed'
            if ($taskfumigasi->status === 'Completed' && $taskfumigasi->marketing_id) {
                $marketing = Marketing::find($taskfumigasi->marketing_id);
                if ($marketing) {
                    $marketing->status = 'Completed';
                    $marketing->save();
                }
            }
        });

        static::created(function ($taskfumigasi) {
            // Update status marketing menjadi "Pengerjaan" saat dibuat
            if ($taskfumigasi->marketing_id) {
                $marketing = Marketing::find($taskfumigasi->marketing_id);
                if ($marketing) {
                    $marketing->status = $taskfumigasi->status === 'Completed' ? 'Completed' : 'Pengerjaan';
                    $marketing->save();
                }
            }
        });
        
        static::deleting(function ($taskfumigasi) {
        if ($taskfumigasi->marketing_id) {
            $marketing = Marketing::find($taskfumigasi->marketing_id);
            if ($marketing) {
                $marketing->status = 'Persiapan Operasional';
                $marketing->save();
            }
        }
        });
    }

    public function hitungDurasiDanLamaPekerjaan()
    {
        if ($this->tgl_mulai && $this->tgl_selesai) {
            $start = Carbon::parse($this->tgl_mulai);
            $end = Carbon::parse($this->tgl_selesai);
            $this->lama_pekerjaan = $start->diffInDays($end);
            $this->durasi_proyek = ceil($this->lama_pekerjaan / 7);
        } else {
            $this->lama_pekerjaan = 0;
            $this->durasi_proyek = 0;
        }
    }

    public function taskfumigasi()
    {
        return $this->belongsTo(TaskFumigasi::class);
    }

    public function jenistahapfumigasi()
    {
        return $this->hasMany(JenisTahapAplikasi::class);
    }

    public function marketing()
    {
        return $this->belongsTo(Marketing::class);
    }

    public function reportfumigasi()
    {
        return $this->hasMany(ReportFumigasi::class);
    }
}
