<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDepositlinkTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('depositlink', function (Blueprint $table) {
            $table->increments('id');
            $table->decimal('amount', 24, 4);
            $table->integer('deposit_id')->unsigned();
            $table->foreign('deposit_id')->references('id')->on('deposit');
            $table->integer('quote_id')->unsigned();
            $table->foreign('quote_id')->references('id')->on('quote');
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
        Schema::dropIfExists('depositlink');
    }
}
