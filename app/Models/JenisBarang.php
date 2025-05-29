<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class JenisBarang extends Model
{
    use HasFactory;

    protected $fillable = ['nama_task'];

    protected static function booted()
    {
        static::created(function ($jenisBarang) {
            LogAktivitas::create([
                'user_id' => Auth::id(), // ID user yang login
                'menu' => 'Jenis Barang',
                'menu_id' => $jenisBarang->id,
                'aksi' => 'Create',
                'waktu' => now(),
            ]);
        });

        static::updated(function ($jenisBarang) {
            LogAktivitas::create([
                'user_id' => Auth::id(),
                'menu' => 'Jenis Barang',
                'menu_id' => $jenisBarang->id,
                'aksi' => 'Update',
                'waktu' => now(),
            ]);
        });

        static::deleted(function ($jenisBarang) {
            LogAktivitas::create([
                'user_id' => Auth::id(),
                'menu' => 'Jenis Barang',
                'menu_id' => $jenisBarang->id,
                'aksi' => 'Delete',
                'waktu' => now(),
            ]);
        });
    }
}
