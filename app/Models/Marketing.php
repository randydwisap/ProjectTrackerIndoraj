<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Marketing extends Model
{
    public function approve()
    {
        $this->update(['status' => 'Completed']);
    }

    public function reject()
    {
        $this->update(['status' => 'Pending']);
    }

    protected $casts = [
        'dokumentasi_foto' => 'array',
    ];


    public function pic()
    {
        return $this->belongsTo(User::class, 'nama_pic');
    }

    public function manager()
    {
        return $this->belongsTo(User::class, 'project_manager');
    }

    use HasFactory;

    protected $fillable = [
        'nama_pekerjaan',
        'jenis_pekerjaan',
        'nama_klien',
        'lokasi',
        'tahap_pengerjaan',
        'total_volume',
        'nama_pic',
        'project_manager',
        'status',
        'durasi_proyek',
        'jumlah_sdm',
        'nilai_proyek',
        'link_rab',
        'tgl_mulai',
        'tgl_selesai',
        'nilai_akhir_proyek',
        'terms_of_payment',
        'status_pembayaran',
        'dokumentasi_foto',
        'lampiran',
        'note',
    ];

    
}
