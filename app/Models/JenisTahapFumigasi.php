<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class JenisTahapFumigasi extends Model
{
    protected $fillable = ['nama_task'];
    use HasFactory;
    protected static function booted()
    {
        static::created(function ($jenisFumigasi) {
            LogAktivitas::create([
                'user_id' => Auth::id(), // ID user yang login
                'menu' => 'Jenis Fumigasi',
                'menu_id' => $jenisFumigasi->id,
                'aksi' => 'Create',
                'waktu' => now(),
            ]);
        });

        static::updated(function ($jenisFumigasi) {
            LogAktivitas::create([
                'user_id' => Auth::id(),
                'menu' => 'Jenis Fumigasi',
                'menu_id' => $jenisFumigasi->id,
                'aksi' => 'Update',
                'waktu' => now(),
            ]);
        });

        static::deleted(function ($jenisFumigasi) {
            LogAktivitas::create([
                'user_id' => Auth::id(),
                'menu' => 'Jenis Fumigasi',
                'menu_id' => $jenisFumigasi->id,
                'aksi' => 'Delete',
                'waktu' => now(),
            ]);
        });
    }
}
