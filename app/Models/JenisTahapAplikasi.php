<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;


class JenisTahapAplikasi extends Model
{
    protected $fillable = ['nama_task'];
    use HasFactory;
    public function ReportAplikasi()
    {
        return $this->hasMany(ReportAplikasi::class);        
    }
    protected static function booted()
    {
        static::created(function ($jenisTahap) {
            LogAktivitas::create([
                'user_id' => Auth::id(), // ID user yang login
                'menu' => 'Jenis Tahap Aplikasi',
                'menu_id' => $jenisTahap->id,
                'aksi' => 'Create',
                'waktu' => now(),
            ]);
        });

        static::updated(function ($jenisTahap) {
            LogAktivitas::create([
                'user_id' => Auth::id(),
                'menu' => 'Jenis Tahap Aplikasi',
                'menu_id' => $jenisTahap->id,
                'aksi' => 'Update',
                'waktu' => now(),
            ]);
        });

        static::deleted(function ($jenisTahap) {
            LogAktivitas::create([
                'user_id' => Auth::id(),
                'menu' => 'Jenis Tahap Aplikasi',
                'menu_id' => $jenisTahap->id,
                'aksi' => 'Delete',
                'waktu' => now(),
            ]);
        });
    }
}
