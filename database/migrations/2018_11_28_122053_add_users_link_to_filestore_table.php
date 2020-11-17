<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddUsersLinkToFilestoreTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('filestore', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->renameColumn('user_id', 'upload_user');
            $table->foreign('upload_user')->references('id')->on('users');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('filestore', function (Blueprint $table) {
            $table->dropForeign(['upload_user']);
            $table->renameColumn('upload_user', 'user_id');
            $table->foreign('upload_user')->references('id')->on('users');
        });
    }
}
