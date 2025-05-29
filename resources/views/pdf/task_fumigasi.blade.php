<!DOCTYPE html>
<html>
<head>
    {{-- /* background-image: url('{{ storage_path("app/public/kop.jpg") }}'); untuk deploy */ --}}
    <meta charset="utf-8">
    <title>Surat Tugas - {{ $taskFumigasi->pekerjaan }}</title>
    <style>
        @page {
            margin: 0cm;
        }
        
        
        .ttd {
            width: 300px;              /* Atur lebar sesuai kebutuhan */
            text-align: left;          /* Teks rata kiri */
            margin-top: 50px;
            margin-left: 80%;         /* Dorong elemen ke kanan */
        }


        .ttd-container {
            display: inline-block;
            width: 180px; /* Sesuaikan dengan ukuran gambar tanda tangan */
            position: relative;
            margin-right: 20%;
            margin-top: 2%;
            text-align: center; /* Supaya stempel dan ttd sejajar */
        }

        .ttd-image {
            width: 100%;
            position: relative;
            z-index: 1;
        }

        .stamp-image {
            position: absolute;
            top: -25px;              /* atau coba 5px jika ingin agak turun */
            left: 20%;
            transform: translateX(-50%);
            width: 90px;           /* Ukuran stempel */
            z-index: 2;
            opacity: 0.9;
            pointer-events: none;
        }

        body {
            margin: 0;
            padding: 0;
            font-family: 'Times New Roman', serif;
            font-size: 12pt;
            background-image: url('{{ storage_path("app/public/kop.jpg") }}');
            background-size: 100% 100%;
            background-repeat: no-repeat;
            background-position: top center;
            height: 100vh;
        }

        .content {
            padding: 230px 50px 50px 50px; /* atur sesuai posisi kop */
        }

        .judul {
            font-weight: bold;
            text-align: center;
            text-decoration: underline;
            margin: 0;
        }

        .nomor-surat {
            text-align: center;
            margin-bottom: 20px;
        }

        .section {
            margin-bottom: 15px;
        }
        
        .section-tugas {
            margin-bottom: 15px;
            font-weight: bold;
            text-align: center;
            text-decoration: underline;
        }

        table {
            width: 100%;
            font-size: 12pt;
        }

        td {
            vertical-align: top;
        }

        .red {
            color: red;
            font-weight: bold;
        }

        u {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="content">
        <h3 class="judul">SURAT TUGAS</h3>
        <div class="nomor-surat">{{ $taskFumigasi->no_st}}</div>

        <p>Yang bertanda tangan di bawah ini:</p>
        <table>
            <tr><td>Nama</td><td>: Fransiscus Setyadji, S.H.</td></tr>
            <tr><td>Jabatan</td><td>: Direktur</td></tr>
            <tr><td>Alamat</td><td>: Jl. Sanan IA/33, Purwantoro, Blimbing, Kota Malang</td></tr>
        </table>

        <p class="section-tugas"><strong>MENUGASKAN</strong></p>

<h3 class="section-title">A. Kepada</h3>
<ol type="1" style="padding-left: 20px;">
    <li>
        Nama : <span>{{ $taskFumigasi->user->name ?? '-' }}</span><br>
        Jabatan : <span>Project Manager</span><br>
        Kontak : <span>{{ $taskFumigasi->no_telp_pm ?? '-' }}</span>
    </li>
    @php $no = 2; @endphp
    @forelse ($taskFumigasi->pelaksana ?? [] as $item)
        @if(isset($taskFumigasi->user) && $item['nama'] == $taskFumigasi->user->name)
            @continue
        @endif
        <li>
            Nama : <span>{{ $item['nama'] ?? '-' }}</span><br>
            Jabatan : <span>Pelaksana</span><br>
        </li>
    @empty
        <li>
            <em>Tidak ada pelaksana tersedia.</em>
        </li>
    @endforelse
</ol>
        <div class="section">
            <strong>B. Untuk</strong>: Pelaksanaan Pekerjaan {{ $taskFumigasi->pekerjaan}}
        </div>

        <div class="section">
            <strong>C. Waktu</strong>:  {{ \Carbon\Carbon::parse($taskFumigasi->tgl_mulai)->translatedFormat('d F Y') }} –  {{ \Carbon\Carbon::parse($taskFumigasi->tgl_selesai)->translatedFormat('d F Y') }}
        </div>

        <div class="section">
            <strong>D. Tempat</strong>: {{ $taskFumigasi->alamat}} <br>
            {{ $taskFumigasi->lokasi }}
        </div>

        <div class="ttd">
            Malang, {{ \Carbon\Carbon::parse($taskFumigasi->tgl_surat)->translatedFormat('d F Y') }}<br>
            Direktur,<br><br><br>
            <div class="ttd-container">
                <img src="{{ storage_path('app/public/ttd.jpg') }}"alt="Tanda Tangan" class="ttd-image">
                <img src="{{ storage_path('app/public/stamp.png') }}" alt="Stempel"  class="stamp-image">
            </div>
            <span>Fransiscus Setyadji, S.H.</span><br>
        </div>
    </div>
</body>
</html>
