<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTrainingRequestIdToSchedulerParentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('scheduler_parent', function (Blueprint $table) {
            $table->integer('training_request_id')->unsigned()->nullable()->default(null)->after('vendor_id');
            $table->foreign('training_request_id')->references('id')->on('training_requests');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('scheduler_parent', function (Blueprint $table) {
            $table->dropForeign('scheduler_parent_training_request_id_foreign');
            $table->dropColumn('training_request_id');
        });
    }
}
