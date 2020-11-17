<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFilestoreTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('filestore', function (Blueprint $table) {
            $table->increments('id');
            $table->string('description');
            $table->longtext('file');
            $table->integer('client_id')->unsigned()->nullable()->default(null);
            $table->foreign('client_id')->references('id')->on('clients');
            $table->integer('vendor_id')->unsigned()->nullable()->default(null);
            $table->foreign('vendor_id')->references('id')->on('vendors');
            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('filestore');
    }
}
