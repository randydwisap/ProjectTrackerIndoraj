<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class JenisTaskAlihMedia extends Model
{
    use HasFactory;
    protected $fillable = ['nama_task'];

    public function taskWeekAlihMedia()
    {
        return $this->hasMany(TaskWeekAlihMedia::class);
    }
    
    public function taskDayAlihMedia()
    {
        return $this->hasMany(TaskDayAlihMedia::class);
    }

        protected static function booted()
    {
        static::created(function ($jenisAlihMedia) {
            LogAktivitas::create([
                'user_id' => Auth::id(), // ID user yang login
                'menu' => 'Jenis Alih Media',
                'menu_id' => $jenisAlihMedia->id,
                'aksi' => 'Create',
                'waktu' => now(),
            ]);
        });

        static::updated(function ($jenisAlihMedia) {
            LogAktivitas::create([
                'user_id' => Auth::id(),
                'menu' => 'Jenis Alih Media',
                'menu_id' => $jenisAlihMedia->id,
                'aksi' => 'Update',
                'waktu' => now(),
            ]);
        });

        static::deleted(function ($jenisAlihMedia) {
            LogAktivitas::create([
                'user_id' => Auth::id(),
                'menu' => 'Jenis Alih Media',
                'menu_id' => $jenisAlihMedia->id,
                'aksi' => 'Delete',
                'waktu' => now(),
            ]);
        });
    }
}
