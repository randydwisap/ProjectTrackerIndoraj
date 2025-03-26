<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->string('pekerjaan'); // Nama pekerjaan
            $table->string('klien'); // Nama klien
            $table->string('tahap_pengerjaan'); // Tahap pengerjaan
            $table->enum('status', ['Pending', 'In Progress', 'Completed', 'On Hold']); // Status pekerjaan
            $table->enum('resiko_keterlambatan', ['Low', 'Medium', 'High'])->default('Low'); // Resiko keterlambatan
            $table->integer('durasi_proyek'); // Durasi proyek (dalam hari)
            $table->integer('jumlah_sdm'); // Jumlah SDM yang terlibat
            $table->string('project_manager'); // Nama Project Manager
            $table->string('no_telp_pm'); // Nomor Telepon Project Manager
            $table->decimal('nilai_proyek', 15, 2); // Nilai proyek dalam mata uang
            $table->string('link_rab')->nullable(); // Link ke RAB (Rencana Anggaran Biaya)
            $table->date('tgl_mulai'); // Tanggal mulai proyek
            $table->date('tgl_selesai'); // Tanggal selesai proyek
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};
