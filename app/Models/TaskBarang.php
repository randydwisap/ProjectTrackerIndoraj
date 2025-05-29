<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class TaskBarang extends Model
{
    use HasFactory;

    protected $fillable = [
        'pekerjaan',
        'klien',
        'tahap_pengerjaan',
        'tgl_mulai',
        'tgl_selesai',
        'durasi_proyek',
        'project_manager', 
        'no_telp_pm',
        'nilai_proyek',
        'link_rab',
        'lokasi',
        'volume_arsip',
        'volume_dikerjakan',
        'deskripsi_pekerjaan',
        'lama_pekerjaan',
        'telepon',        
        'total_hari_kerja',
        'marketing_id',
        'alamat',
    ];

    protected $casts = [
        'pelaksana' => 'array',
    ];

    public function telepon()
    {
        return $this->belongsTo(User::class, 'telepon');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'project_manager');
    }

    public function marketing()
    {
        return $this->belongsTo(Marketing::class);
    }

    protected static function booted()
    {
        static::created(function ($task) {
            if ($task->marketing) {
                $task->marketing->update(['status' => 'Completed']);
            }
                        LogAktivitas::create([
                'user_id' => Auth::id(), // ID user yang login
                'menu' => 'Task Barang',
                'menu_id' => $task->id,
                'aksi' => 'Create',
                'waktu' => now(),
            ]);
        });

        static::deleting(function ($task) {
        if ($task->marketing_id) {
            $marketing = Marketing::find($task->marketing_id);
            if ($marketing) {
                $marketing->status = 'Persiapan Operasional';
                $marketing->save();
            }
        }
        LogAktivitas::create([
                'user_id' => Auth::id(), // ID user yang login
                'menu' => 'Task Barang',
                'menu_id' => $task->id,
                'aksi' => 'Delete',
                'waktu' => now(),
            ]);
        });

        
        static::updated(function ($task) {
        LogAktivitas::create([
                'user_id' => Auth::id(), // ID user yang login
                'menu' => 'Task Barang',
                'menu_id' => $task->id,
                'aksi' => 'Update',
                'waktu' => now(),
            ]);
        });
    }
}
