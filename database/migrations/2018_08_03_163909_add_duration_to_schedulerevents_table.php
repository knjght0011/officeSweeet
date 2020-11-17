<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDurationToSchedulereventsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('SchedulerEvents', function (Blueprint $table) {
            $table->string('duration')->default('01:00')->after('eventname');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('SchedulerEvents', function (Blueprint $table) {
            $table->dropColumn('duration');
        });
    }
}
