<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('project_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('task_id')->constrained()->onDelete('cascade'); // Relasi ke Task
            $table->string('lokasi');
            $table->string('pelaksana');
            $table->integer('volume_arsip');
            $table->string('jenis_arsip');
            $table->text('deskripsi_pekerjaan')->nullable();
            $table->integer('lama_pekerjaan_hari');
            $table->integer('lama_pekerjaan_minggu');
            $table->integer('target_perminggu');
            $table->timestamps();
        });
    }    
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('project_details');
    }
};
