<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDuedateIdToTasklistTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tasklist', function (Blueprint $table) {
            $table->integer('duedate_id')->unsigned()->nullable()->default(null)->after('user_id');
            $table->foreign('duedate_id')->references('event_id')->on('scheduler');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tasklist', function (Blueprint $table) {
            $table->dropForeign(['duedate_id']);
            $table->dropColumn('duedate_id');
        });
    }
}
