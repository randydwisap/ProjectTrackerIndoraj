<!DOCTYPE html>
<html>
<head>
    <style>
        body {
            background-image: url('{{ public_path("kop/kop.png") }}');
            background-size: cover;
            margin: 50px;
            font-family: sans-serif;
        }
        .content {
            margin-top: 200px;
        }
    </style>
</head>
<body>
    <div class="content">
        <h2>Report Proyek: {{ $task->pekerjaan }}</h2>
        <p>Klien: {{ $task->klien }}</p>
        <p>Tanggal Mulai: {{ $task->tgl_mulai }}</p>
        <p>Tanggal Selesai: {{ $task->tgl_selesai }}</p>
        <p>Pelaksana:</p>
        <ul>
            @foreach ($task->pelaksana ?? [] as $item)
                <li>{{ $item['nama'] ?? '-' }}</li>
            @endforeach
        </ul>
    </div>
</body>
</html>
