<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Support\Facades\Http;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Task extends Model
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
        'volume_arsip',
        'volume_dikerjakan',
        'dikerjakan_step1',
        'dikerjakan_step2',
        'dikerjakan_step3',
        'dikerjakan_step4',
        'jenis_arsip',
        'deskripsi_pekerjaan',
        'lama_pekerjaan',
        'target_perminggu',
        'target_perminggu_arsip',
        'target_perday',
        'target_perday_arsip',
        'telepon',
        'hasil_pemilahan',
        'total_hari_kerja',
        'marketing_id',
    ];
    public function Telepon()
    {
        return $this->belongsTo(User::class, 'Telepon');
    }

    // Ubah `pelaksana` menjadi array secara otomatis
    protected $casts = [
        'pelaksana' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'project_manager'); // Relasi ke User
    }
    
    protected static function boot()
    {
        parent::boot();
        
        static::saving(function ($task) {            
            $task->hitungDurasiDanLamaPekerjaan();
            $task->hitungTargetPerHariArsip();
            $task->hitungTargetPerMingguArsip();
            $task->hitungStatus();            
            $task->hitungResiko();  
            $task->hitungTotalStep();   
            $task->hitungTahap();
        });

        static::updating(function ($task) {        
            $task->hitungDurasiDanLamaPekerjaan();
            $task->hitungTargetPerHariArsip();
            $task->hitungTargetPerMingguArsip();
            $task->hitungStatus();
            $task->hitungResiko();             
            $task->hitungTotalStep();     
            $task->hitungTahap();
        });

        static::created(function ($task) {
            // Update status marketing menjadi "On Hold"
            if ($task->marketing_id) {
                $marketing = Marketing::find($task->marketing_id);
                if ($marketing) {
                    $marketing->status = 'Pengerjaan'; // Ubah status
                    $marketing->save(); // Simpan perubahan
                }
            }

            $durasiProyek = $task->durasi_proyek;
            $totalHariKerja = $task->total_hari_kerja;
            $task->total_hari_kerja = $task->getTotalHariKerja();
            $jenisTasks = JenisTask::all(); // Assuming you have a model for JenisTask
            $hariKerjaPerMinggu = $task->getTotalHariKerjaPerMinggu(); // Asumsikan metode ini berada di model Task
            
            for ($i = 1; $i <= $durasiProyek; $i++) {
            $taskWeek = new TaskWeekOverview();
            $taskWeek->task_id = $task->id;
            $taskWeek->nama_week = "Week " . $i;
            $taskWeek->total_volume = $hariKerjaPerMinggu["HariKerjaMingguKe{$i}"] * $task->target_perday; // dari model Task
            $taskWeek->volume_dikerjakan = 0;
            $taskWeek->target_minggu = $hariKerjaPerMinggu["HariKerjaMingguKe{$i}"] * $task->target_perday;
            $taskWeek->total_step1 = 0;
            $taskWeek->total_step2 = 0;
            $taskWeek->total_step3 = 0;
            $taskWeek->total_step4 = 0;

            // Ambil hari kerja untuk minggu ke-i, default ke 0 jika tidak ada
            $taskWeek->hari_kerja = $hariKerjaPerMinggu["HariKerjaMingguKe{$i}"] ?? 0;

            $taskWeek->save();
        }
        });
    }

    /**
     * Menghitung durasi proyek dalam minggu dan lama pekerjaan dalam hari.
     */
    public function hitungDurasiDanLamaPekerjaan()
    {
        if ($this->tgl_mulai && $this->tgl_selesai) {
            $start = Carbon::parse($this->tgl_mulai);
            $end = Carbon::parse($this->tgl_selesai);
            
            // Hitung lama pekerjaan dalam hari
            $this->lama_pekerjaan = $start->diffInDays($end);
            
            // Hitung durasi proyek dalam minggu
            $this->durasi_proyek = ceil($this->lama_pekerjaan / 7);
        } else {
            $this->lama_pekerjaan = 0;
            $this->durasi_proyek = 0;
        }
    }

    public function hitungTargetPerHariArsip()
{
    if ($this->total_hari_kerja > 0) {
        $totalArsip = \App\Models\TaskDayDetail::where('task_id', $this->id)
                        ->where('jenis_task_id', 1)
                        ->sum('hasil');

        $this->target_perday_arsip = $totalArsip / $this->total_hari_kerja;
    } else {
        $this->target_perday_arsip = 0;
    }
}

public function hitungTargetPerMingguArsip()
{
    if ($this->durasi_proyek > 0) {
        $totalArsip = \App\Models\TaskDayDetail::where('task_id', $this->id)
                        ->where('jenis_task_id', 1)
                        ->sum('hasil');

        $this->target_perminggu_arsip = $totalArsip / $this->durasi_proyek;
    } else {
        $this->target_perminggu_arsip = 0;
    }
}

public function hitungStatus()
{
    if (
    $this->dikerjakan_step4 >= $this->hasil_pemilahan &&
    $this->dikerjakan_step4 != 0 &&
    $this->hasil_pemilahan != 0 &&
    $this->dikerjakan_step1 >= $this->volume_arsip
) {
        $this->status = 'Completed';
    } elseif ($this->volume_arsip > 0) {
        $persentase = ($this->volume_dikerjakan  / ($this->volume_arsip * 3)) * 100;

        if ($persentase >= 50) {
            $this->status = 'Behind Schedule';
        } else {
            $this->status = 'Far Behind Schedule';
        }
    } else {
        $this->status = 'Not Started';
    }
}

public function hitungTahap()
{
    if ($this->dikerjakan_step4 > 0) {
        $jenisTask = JenisTask::find(4);
    } elseif ($this->dikerjakan_step3 > 0) {
        $jenisTask = JenisTask::find(3);
    } elseif ($this->dikerjakan_step2 > 0) {
        $jenisTask = JenisTask::find(2);
    } else {
        $jenisTask = JenisTask::find(1);
    }

    // Cek apakah task ditemukan
    if ($jenisTask) {
        $this->tahap_pengerjaan = $jenisTask->nama_task;
    }
}

public function hitungResiko()
{
    $jumlahOnTrack = \App\Models\TaskWeekOverview::where('task_id', $this->id)
        ->where('status', 'On Track')
        ->count();

    $totalDetails = \App\Models\TaskWeekOverview::where('task_id', $this->id)
        ->count();

    // Handle kasus ketika tidak ada data
    if ($totalDetails === 0) {
        $this->resiko_keterlambatan = 'Low';
        return;
    }
    $persentaseOnTrack = ($jumlahOnTrack / $totalDetails) * 100;

    // Tentukan resiko berdasarkan persentase
    if ($persentaseOnTrack >= 80) {
        $this->resiko_keterlambatan = 'Low';
    } elseif ($persentaseOnTrack >= 50) {
        $this->resiko_keterlambatan = 'Medium';
    } else {
        $this->resiko_keterlambatan = 'High';
    }
}

public function hitungTotalStep()
{
    for ($i = 1; $i <= 4; $i++) {
        $total = \App\Models\TaskDayDetail::where('task_id', $this->id)
            ->where('jenis_task_id', $i)
            ->sum('output');

        $this->{"dikerjakan_step{$i}"} = $total;
    }
}


    public function taskDayDetails()
    {
        return $this->hasMany(TaskDayDetail::class);
    }

    public function taskWeekOverviews()
    {
        return $this->hasMany(TaskWeekOverview::class);
    }
    public function task()
{
    return $this->belongsTo(Task::class);
}

public function marketing()
{
    return $this->belongsTo(Marketing::class);
}
public function getTotalHariKerja()
{
    $start = Carbon::parse($this->tgl_mulai);
    $end = Carbon::parse($this->tgl_selesai);
    $periode = CarbonPeriod::create($start, $end);

    // Ambil semua tahun di rentang tgl_mulai - tgl_selesai
    $years = range($start->year, $end->year);
    $tanggalMerah = [];

    // Ambil semua tanggal libur dari API (baik is_cuti true maupun false)
    foreach ($years as $year) {
        $response = Http::get("https://dayoffapi.vercel.app/api?year={$year}");

        if ($response->successful()) {
            $data = $response->json();

            foreach ($data as $item) {
                $tanggalMerah[] = Carbon::parse($item['tanggal'])->toDateString(); // Normalisasi format
            }
        }
    }

    $hariKerja = 0;

    foreach ($periode as $tanggal) {
        // Hitung jika bukan Minggu DAN bukan tanggal merah
        if (
            $tanggal->dayOfWeek !== Carbon::SUNDAY &&
            !in_array($tanggal->toDateString(), $tanggalMerah)
        ) {
            $hariKerja++;
        }
    }

    return $hariKerja;
}
public function getTotalHariKerjaPerMinggu(): array
{
    $start = Carbon::parse($this->tgl_mulai);
    $end = Carbon::parse($this->tgl_selesai);
    
    // Hitung total minggu dalam proyek (pembulatan ke atas)
    $totalMinggu = ceil($start->diffInDays($end) / 7);
    
    // Inisialisasi array dengan nilai default 0 untuk semua minggu
    $hasil = array_fill(1, $totalMinggu, 0);

    $periode = CarbonPeriod::create($start, $end);
    
    // Ambil tanggal libur (sama seperti sebelumnya)
    $years = range($start->year, $end->year);
    $tanggalMerah = [];
    
    foreach ($years as $year) {
        $response = Http::get("https://dayoffapi.vercel.app/api?year={$year}");
        if ($response->successful()) {
            foreach ($response->json() as $item) {
                $tanggalMerah[] = Carbon::parse($item['tanggal'])->toDateString();
            }
        }
    }

    // Hitung hari kerja per minggu
    foreach ($periode as $tanggal) {
        if ($tanggal->dayOfWeek !== Carbon::SUNDAY && 
            !in_array($tanggal->toDateString(), $tanggalMerah)) {
            
            $mingguKe = $start->diffInWeeks($tanggal) + 1;
            
            // Pastikan mingguKe tidak melebihi totalMinggu
            if ($mingguKe <= $totalMinggu) {
                $hasil[$mingguKe]++;
            }
        }
    }

    return $hasil;
}

}