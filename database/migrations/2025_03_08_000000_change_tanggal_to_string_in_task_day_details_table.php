<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeTanggalToStringInTaskDayDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('task_day_details', function (Blueprint $table) {
            $table->string('tanggal')->change(); // Change the type to string
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('task_day_details', function (Blueprint $table) {
            $table->date('tanggal')->change(); // Revert back to date type if needed
        });
    }
}
