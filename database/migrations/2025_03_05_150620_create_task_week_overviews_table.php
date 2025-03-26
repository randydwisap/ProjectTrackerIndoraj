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
        Schema::create('task_week_overviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('task_detail_id')->constrained('task_details')->onDelete('cascade'); 
            $table->integer('target_perorang');
            $table->integer('jumlah_sdm'); // FK dari table task
            $table->integer('target_harian');
            $table->integer('hari_kerja_efektif');
            $table->integer('target_minggu_ini');
            $table->string('status_weekly')->nullable();
            $table->string('resiko_keterlambatan')->nullable();
            $table->timestamps();
        });
    }
    
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('task_week_overviews');
    }
};
