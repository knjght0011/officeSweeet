<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFilestoreIdToTrainingRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('training_requests', function (Blueprint $table) {
            $table->integer('filestore_id')->unsigned()->nullable()->default(null);
            $table->foreign('filestore_id')->references('id')->on('filestore');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('training_requests', function (Blueprint $table) {
            $table->dropForeign(['filestore_id']);
            $table->dropColumn('filestore_id');
        });
    }
}
