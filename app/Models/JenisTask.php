<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class JenisTask extends Model
{
    protected $fillable = ['nama_task'];

    public function taskWeekOverviews()
    {
        return $this->hasMany(TaskWeekOverview::class);
    }
    
    public function taskDayDetails()
    {
        return $this->hasMany(TaskDayDetail::class);
    }


    protected static function booted()
    {
        static::created(function ($jenisTask) {
            LogAktivitas::create([
                'user_id' => Auth::id(), // ID user yang login
                'menu' => 'Jenis Pengolahan Arsip',
                'menu_id' => $jenisTask->id,
                'aksi' => 'Create',
                'waktu' => now(),
            ]);
        });

        static::updated(function ($jenisTask) {
            LogAktivitas::create([
                'user_id' => Auth::id(),
                'menu' => 'Jenis Pengolahan Arsip',
                'menu_id' => $jenisTask->id,
                'aksi' => 'Update',
                'waktu' => now(),
            ]);
        });

        static::deleted(function ($jenisTask) {
            LogAktivitas::create([
                'user_id' => Auth::id(),
                'menu' => 'Jenis Pengolahan Arsip',
                'menu_id' => $jenisTask->id,
                'aksi' => 'Delete',
                'waktu' => now(),
            ]);
        });
    }
}
