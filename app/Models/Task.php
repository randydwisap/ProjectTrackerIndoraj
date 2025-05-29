<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Support\Facades\Http;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Auth;

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
        'alamat',
        'no_st',
        'tgl_surat',
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
            $task->total_hari_kerja = $task->getTotalHariKerja();
            $task->hitungDurasiDanLamaPekerjaan();
            $task->hitungTargetPerHariArsip();
            $task->hitungTargetPerMingguArsip();
            $task->hitungStatus();            
            $task->hitungResiko();  
            $task->hitungTotalStep();   
            $task->hitungTahap();
            LogAktivitas::create([
                'user_id' => Auth::id(), // ID user yang login
                'menu' => 'Task Pengolahan Arsip',
                'menu_id' => $task->id,
                'aksi' => 'Simpan',
                'waktu' => now(),
            ]);
        });

        static::updating(function ($task) {
            $task->total_hari_kerja = $task->getTotalHariKerja();
            $task->hitungDurasiDanLamaPekerjaan();
            $task->hitungTargetPerHariArsip();
            $task->hitungTargetPerMingguArsip();
            $task->hitungStatus();
            $task->hitungResiko();             
            $task->hitungTotalStep();     
            $task->hitungTahap();
                        LogAktivitas::create([
                'user_id' => Auth::id(), // ID user yang login
                'menu' => 'Task Pengolahan Arsip',
                'menu_id' => $task->id,
                'aksi' => 'Update',
                'waktu' => now(),
            ]);
        });

        static::created(function ($task) {
            // Update status marketing menjadi "On Hold"            

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
            if ($task->marketing_id) {
                $marketing = Marketing::find($task->marketing_id);
                if ($marketing) {
                    $marketing->status = 'Pengerjaan'; // Ubah status
                    $marketing->save(); // Simpan perubahan
                }
            }
            LogAktivitas::create([
                'user_id' => Auth::id(), // ID user yang login
                'menu' => 'Task Pengolahan Arsip',
                'menu_id' => $task->id,
                'aksi' => 'Create',
                'waktu' => now(),
            ]);
        }
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
                'menu' => 'Task Pengolahan Arsip',
                'menu_id' => $task->id,
                'aksi' => 'Delete',
                'waktu' => now(),
            ]);
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
        $periode = CarbonPeriod::create($start, $end);

        // Hitung lama pekerjaan (total hari kalender)
        $this->lama_pekerjaan = $start->diffInDays($end);

        // Ambil semua tahun dalam periode
        $years = range($start->year, $end->year);
        $tanggalMerah = [];

        foreach ($years as $year) {
            $response = Http::get("https://dayoffapi.vercel.app/api?year={$year}");

            if ($response->successful()) {
                $data = $response->json();

                foreach ($data as $item) {
                    $tanggalMerah[] = Carbon::parse($item['tanggal'])->toDateString();
                }
            }
        }

        // Hitung minggu kerja aktif
        $mingguAktif = [];

        foreach ($periode as $tanggal) {
            if (
                $tanggal->dayOfWeek !== Carbon::SUNDAY &&
                !in_array($tanggal->toDateString(), $tanggalMerah)
            ) {
                $mingguKe = intval($start->diffInWeeks($tanggal)) + 1;
                $mingguAktif[$mingguKe] = true;
            }
        }

        $this->durasi_proyek = count($mingguAktif);
    } else {
        $this->lama_pekerjaan = 0;
        $this->durasi_proyek = 0;
    }
}


    public function hitungTargetPerHariArsip()
{
    $jumlahTahap234 = \App\Models\JenisTask::where('id', '!=', 1)->count();
    if ($this->total_hari_kerja > 0) {
        $totalArsip = \App\Models\TaskDayDetail::where('task_id', $this->id)
                        ->where('jenis_task_id', 1)
                        ->sum('hasil');

        $this->target_perday_arsip = $totalArsip*$jumlahTahap234 / $this->total_hari_kerja;
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

        $this->target_perminggu_arsip = $totalArsip*3 / $this->durasi_proyek;
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
        $marketing = Marketing::find($this->marketing_id);
        if ($marketing) {
            $marketing->status = 'Completed'; // Ubah status
            $marketing->save(); // Simpan perubahan
        }
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
    $periode = CarbonPeriod::create($start, $end);

    // Ambil semua tahun yang dicakup dalam periode
    $years = range($start->year, $end->year);
    $tanggalMerah = [];

    // Ambil semua tanggal merah (termasuk cuti)
    foreach ($years as $year) {
        $response = Http::get("https://dayoffapi.vercel.app/api?year={$year}");

        if ($response->successful()) {
            $data = $response->json();

            foreach ($data as $item) {
                $tanggalMerah[] = Carbon::parse($item['tanggal'])->toDateString();
            }
        }
    }

    $hasil = [];
    foreach ($periode as $tanggal) {
        // Hitung hanya jika bukan Minggu dan bukan tanggal merah
        if (
            $tanggal->dayOfWeek !== Carbon::SUNDAY &&
            !in_array($tanggal->toDateString(), $tanggalMerah)
        ) {
            // Hitung minggu ke-n dari tgl_mulai
            $mingguKe = intval($start->diffInWeeks($tanggal)) + 1;

            $key = 'HariKerjaMingguKe' . $mingguKe;
            if (!isset($hasil[$key])) {
                $hasil[$key] = 0;
            }

            $hasil[$key]++;
        }
    }

    return $hasil;
}

}