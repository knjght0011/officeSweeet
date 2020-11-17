<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateQuotesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('quote', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('client_id')->unsigned();
            $table->foreign('client_id')->references('id')->on('clients');
            $table->integer('contact_id')->unsigned();
            $table->foreign('contact_id')->references('id')->on('clientcontacts');
            $table->string('quotenumber');
            $table->string('comments');
            $table->integer('createdbyuser')->unsigned();
            $table->foreign('createdbyuser')->references('id')->on('users');
            $table->boolean('finalized')->default(0);
            $table->integer('finalizedbyuser')->unsigned()->nullable();
            $table->foreign('finalizedbyuser')->references('id')->on('users');
            $table->date('finalizeddate')->nullable();      
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
        Schema::drop('quote');
    }
}
