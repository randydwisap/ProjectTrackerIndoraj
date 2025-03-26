<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('tasks', function (Blueprint $table) {
            // Ubah kolom 'pelaksana' menjadi JSON
            $table->json('pelaksana')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('tasks', function (Blueprint $table) {
            // Kembalikan ke tipe sebelumnya jika rollback
            $table->string('pelaksana')->nullable()->change();
        });
    }
};
