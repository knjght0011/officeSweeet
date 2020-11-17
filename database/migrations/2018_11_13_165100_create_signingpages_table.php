<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSigningpagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::create('signingpages', function (Blueprint $table) {
            $table->increments('id');
            $table->longtext('file');
            $table->integer('pageindex');
            $table->integer('signing_id')->unsigned()->nullable()->default(null);
            $table->foreign('signing_id')->references('id')->on('signing');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::table('signing', function (Blueprint $table) {
            $table->dropColumn('positions');
        });

        Schema::table('signature', function (Blueprint $table) {
            $table->dropForeign(['signing_id']);
            $table->dropColumn('signing_id');

            $table->integer('signingpage_id')->unsigned()->nullable()->default(null);
            $table->foreign('signingpage_id')->references('id')->on('signingpages');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('signingpages');
    }
}
