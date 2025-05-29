<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class JenisInstrumen extends Model
{
    protected $fillable = ['nama_task'];
    use HasFactory;
    protected static function booted()
    {
        static::created(function ($jenisIntrumen) {
            LogAktivitas::create([
                'user_id' => Auth::id(), // ID user yang login
                'menu' => 'Jenis Instrumen',
                'menu_id' => $jenisIntrumen->id,
                'aksi' => 'Create',
                'waktu' => now(),
            ]);
        });

        static::updated(function ($jenisIntrumen) {
            LogAktivitas::create([
                'user_id' => Auth::id(),
                'menu' => 'Jenis Instrumen',
                'menu_id' => $jenisIntrumen->id,
                'aksi' => 'Update',
                'waktu' => now(),
            ]);
        });

        static::deleted(function ($jenisIntrumen) {
            LogAktivitas::create([
                'user_id' => Auth::id(),
                'menu' => 'Jenis Instrumen',
                'menu_id' => $jenisIntrumen->id,
                'aksi' => 'Delete',
                'waktu' => now(),
            ]);
        });
    }
}
