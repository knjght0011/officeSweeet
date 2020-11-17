<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSigningTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('signing', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->longtext('file');
            $table->integer('created_by')->unsigned()->nullable()->default(null);
            $table->foreign('created_by')->references('id')->on('users');
            $table->integer('originalreport_id')->unsigned()->nullable()->default(null);
            $table->foreign('originalreport_id')->references('id')->on('reports');
            $table->integer('client_id')->unsigned()->nullable()->default(null);
            $table->foreign('client_id')->references('id')->on('clients');
            $table->integer('vendor_id')->unsigned()->nullable()->default(null);
            $table->foreign('vendor_id')->references('id')->on('vendors');
            $table->integer('user_id')->unsigned()->nullable()->default(null);
            $table->foreign('user_id')->references('id')->on('users');
            $table->boolean('sign')->default('0');
            $table->integer('signed_by_client')->unsigned()->nullable()->default(null);
            $table->foreign('signed_by_client')->references('id')->on('clientcontacts');
            $table->integer('signed_by_vendor')->unsigned()->nullable()->default(null);
            $table->foreign('signed_by_vendor')->references('id')->on('vendorcontacts');
            $table->integer('signed_by_user')->unsigned()->nullable()->default(null);
            $table->foreign('signed_by_user')->references('id')->on('users');
            $table->string('token')->nullable()->default(null);
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
        Schema::dropIfExists('signing');
    }
}
