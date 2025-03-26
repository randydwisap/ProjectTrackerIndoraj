<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('tasks', function (Blueprint $table) {
            $table->string('lokasi')->nullable();
            $table->string('pelaksana')->nullable();
            $table->integer('volume_arsip')->nullable();
            $table->string('jenis_arsip')->nullable();
            $table->text('deskripsi_pekerjaan')->nullable();
            $table->integer('lama_pekerjaan')->nullable();
            $table->integer('target_perminggu')->nullable();
            
        });
    }    

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tasks', function (Blueprint $table) {
            $table->dropColumn([
                'lokasi',
                'pelaksana',
                'volume_arsip',
                'jenis_arsip',
                'deskripsi_pekerjaan',
                'lama_pekerjaan',
                'target_perminggu',
            ]);
        });
    }
    
};
