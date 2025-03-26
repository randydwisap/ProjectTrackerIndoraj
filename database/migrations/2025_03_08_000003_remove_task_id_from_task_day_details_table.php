<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveTaskIdFromTaskDayDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('task_day_details', function (Blueprint $table) {
            $table->dropColumn('task_id'); // Remove task_id column
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
            $table->foreignId('task_id')->constrained('tasks')->onDelete('cascade'); // Re-add task_id column
        });
    }
}
