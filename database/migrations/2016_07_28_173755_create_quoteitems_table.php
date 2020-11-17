<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateQuoteitemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('quoteitems', function (Blueprint $table) {
            $table->increments('id');
            $table->string('description');
            $table->integer('quote_id')->unsigned();
            $table->foreign('quote_id')->references('id')->on('quote');
            $table->decimal('costperunit', 24, 4);
            $table->decimal('units', 24, 4);
            $table->string('comments');
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
        Schema::drop('quoteitems');
    }
}
