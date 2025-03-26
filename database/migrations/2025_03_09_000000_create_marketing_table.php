<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('marketing', function (Blueprint $table) {
            $table->id();
            $table->string('nama_pekerjaan');
            $table->string('jenis_pekerjaan');
            $table->string('nama_klien');
            $table->string('lokasi');
            $table->string('tahap_pengerjaan');
            $table->integer('total_volume');
            $table->unsignedBigInteger('nama_pic'); // user.id
            $table->unsignedBigInteger('project_manager'); // user.id
            $table->enum('status', ['Pending', 'In Progress', 'Completed', 'On Hold']);
            $table->enum('resiko_keterlambatan', ['Low', 'Medium', 'High'])->default('Low');
            $table->integer('durasi_proyek');
            $table->integer('jumlah_sdm');
            $table->decimal('nilai_proyek', 15, 2);
            $table->string('link_rab')->nullable();
            $table->date('tgl_mulai');
            $table->date('tgl_selesai');
            $table->decimal('nilai_akhir_proyek', 15, 2);
            $table->integer('terms_of_payment');
            $table->enum('status_pembayaran',['Belum Lunas', 'Lunas']);
            $table->json('dokumentasi_foto'); // can upload more than 1 image
            $table->string('lampiran'); // file .pdf
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('marketing');
    }
};
