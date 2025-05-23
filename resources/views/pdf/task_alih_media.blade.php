<!DOCTYPE html>
<html>
<head>
    <title>SPT - {{ $taskAlihMedia->pekerjaan }} | {{ $taskAlihMedia->klien }}</title>
    <style>
        @page {
            margin: 0;
        }

        .content {
            margin-top: 75px;
        }

        body {
            margin: 0;
            padding: 100px 50px 50px 50px;
            background-image: url("{{ public_path('storage/kop.jpg') }}");
            /* background-image: url('{{ storage_path("app/public/kop.jpg") }}'); untuk deploy */
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
            padding: 2px 6px;
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
        <h2>SPT Alih Media: {{ $taskAlihMedia->pekerjaan }}</h2>

        <table class="two-column-table">
            <tr>
                <td><span class="bold">Klien:</span> {{ $taskAlihMedia->klien }}</td>
                <td><span class="bold">Lokasi:</span> {{ $taskAlihMedia->lokasi }}</td>
            </tr>
            <tr>
                <td><span class="bold">Jenis Arsip:</span> {{ $taskAlihMedia->jenis_arsip }}</td>
                <td><span class="bold">Total Volume:</span> {{ number_format($taskAlihMedia->volume_arsip, 2) }} ml</td>
            </tr>
            <tr>
                <td><span class="bold">Tanggal Mulai:</span> {{ $taskAlihMedia->tgl_mulai }}</td>
                <td><span class="bold">Tanggal Selesai:</span> {{ $taskAlihMedia->tgl_selesai }}</td>
            </tr>
            <tr>
                <td><span class="bold">Total Hari Kerja:</span> {{ $taskAlihMedia->total_hari_kerja }}</td>
                <td><span class="bold">Deskripsi:</span> {{ $taskAlihMedia->deskripsi_pekerjaan }}</td>
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
                    <td>{{ $taskAlihMedia->user->name ?? '-' }}</td>
                    <td>Project Manager</td>
                    <td>{{ $taskAlihMedia->no_telp_pm ?? '-' }}</td>
                </tr>
                @php $no = 2; @endphp
                @forelse ($taskAlihMedia->pelaksana ?? [] as $item)
                    @if(isset($taskAlihMedia->user) && $item['nama'] == $taskAlihMedia->user->name)
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
                @forelse ($taskAlihMedia->taskWeekAlihMedia as $week)
                    <tr>
                        <td>{{ $week->nama_week }}</td>
                        <td>{{ number_format($week->total_volume, 2) }} ml</td>
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
