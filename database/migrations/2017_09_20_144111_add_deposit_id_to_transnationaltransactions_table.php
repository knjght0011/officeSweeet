<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDepositIdToTransnationaltransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('transnationaltransactions', function (Blueprint $table) {
            $table->integer('deposit_id')->unsigned()->nullable()->default(null);
            $table->foreign('deposit_id')->references('id')->on('deposit');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('transnationaltransactions', function (Blueprint $table) {
            $table->dropColumn('deposit_id');
        });
    }
}
