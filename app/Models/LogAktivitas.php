<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LogAktivitas extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'menu',
        'menu_id',
        'waktu',
        'aksi',
    ];

    public function user()
    {
        return $this->belongsTo(User::class); // otomatis pakai user_id
    }
}
