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
        Schema::create('task_day_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('task_detail_id')->constrained('task_details')->onDelete('cascade');
            $table->date('tanggal');
            $table->integer('output')->default(0); // Volume yang dikerjakan
            $table->string('status')->nullable(); // Status pekerjaan
            $table->timestamps();
        });
    }    

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('task_day_details');
    }
};
