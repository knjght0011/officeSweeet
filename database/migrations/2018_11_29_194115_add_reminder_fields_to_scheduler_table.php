<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddReminderFieldsToSchedulerTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('scheduler', function (Blueprint $table) {
            $table->date('reminderdate')->nullable()->default(null)->after('contents');
            $table->string('reminderemails')->nullable()->default(null)->after('reminderdate');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('scheduler', function (Blueprint $table) {
            $table->dropColumn('reminderdate');
            $table->dropColumn('reminderemails');
        });
    }
}
