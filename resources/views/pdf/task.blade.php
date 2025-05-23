<!DOCTYPE html>
<html>
<head>
    <style>
        @page {
            margin: 0;
        }

        .content {
    margin-top: 75px; /* contoh: tambahkan margin top lebih besar */
}

        body {
            margin: 0;
            padding: 100px 50px 50px 50px;
            background-image: url("{{ public_path('storage/kop.jpg') }}");
            background-image: url('{{ storage_path("app/public/kop.jpg") }}'); untuk deploy
            background-size: cover;
            background-repeat: no-repeat;
            background-position: center;
            font-family: 'Times New Roman', serif;
            font-size: 12pt;
            color: #000;
            line-height: 1.4;
        }

        table {
            border-collapse: collapse;
            width: 100%;
            font-size: 11pt;
        }

        th, td {
            padding: 2px 6px; /* Lebih rapat */
            vertical-align: top;
        }

        .two-column-table td {
            width: 50%;
        }

        .section-title {
            font-size: 14pt;
            margin-top: 1em;
            margin-bottom: 0.5em;
        }

        .bold {
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="content">
        <h2>SPT Proyek: {{ $task->pekerjaan }}</h2>

        <table class="two-column-table">
            <tr>
                <td><span class="bold">Klien:</span> {{ $task->klien }}</td>
                <td><span class="bold">Lokasi:</span> {{ $task->lokasi }}</td>
            </tr>
            <tr>
                <td><span class="bold">Jenis Arsip:</span> {{ $task->jenis_arsip }}</td>
                <td><span class="bold">Total Volume:</span> {{ number_format($task->volume_arsip, 2) }} ml</td>
            </tr>
            <tr>
                <td><span class="bold">Tanggal Mulai:</span> {{ $task->tgl_mulai }}</td>
                <td><span class="bold">Tanggal Selesai:</span> {{ $task->tgl_selesai }}</td>
            </tr>
            <tr>
                <td><span class="bold">Total Hari Kerja:</span> {{ $task->total_hari_kerja }}</td>
                <td><span class="bold">Deskripsi:</span> {{ $task->deskripsi_pekerjaan }}</td>
            </tr>
        </table>

        <h3 class="section-title">Pelaksana</h3>
        <table border="1">
            <thead>
                <tr>
                    <th>No.</th>
                    <th>Nama</th>
                    <th>Jabatan</th>
                    <th>Kontak</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>1</td>
                    <td>{{ $task->user->name ?? '-' }}</td>
                    <td>Project Manager</td>
                    <td>{{ $task->no_telp_pm ?? '-' }}</td>
                </tr>
                @php $no = 2; @endphp
                @forelse ($task->pelaksana ?? [] as $item)
                    @if(isset($task->user) && $item['nama'] == $task->user->name)
                        @continue
                    @endif
                    <tr>
                        <td>{{ $no++ }}</td>
                        <td>{{ $item['nama'] ?? '-' }}</td>
                        <td>Pelaksana</td>
                        <td>{{ $item['kontak'] ?? '-' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4">Tidak ada pelaksana tersedia.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <h3 class="section-title">Rangkuman Mingguan</h3>
        <table border="1">
            <thead>
                <tr>
                    <th>Minggu ke-</th>
                    <th>Target</th>
                    <th>Hari Kerja</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($task->taskWeekOverviews as $week)
                    <tr>
                        <td>{{ $week->nama_week }}</td>
                        <td> {{ number_format($week->target_minggu, 2) }} ml</td>
                        <td>{{ $week->hari_kerja }} hari</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3">Tidak ada data mingguan tersedia.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</body>
</html>
