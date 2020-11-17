<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTransnationaltransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transnationaltransactions', function (Blueprint $table) {
            $table->increments('id');
            $table->string('response')->nullable()->default(null);
            $table->string('responsetext')->nullable()->default(null);
            $table->string('authcode')->nullable()->default(null);
            $table->string('transactionid')->nullable()->default(null);
            $table->string('avsresponse')->nullable()->default(null);
            $table->string('cvvresponse')->nullable()->default(null);
            $table->string('orderid')->nullable()->default(null);
            $table->string('response_code')->nullable()->default(null);
            $table->string('emv_auth_response_data')->nullable()->default(null);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transnationaltransactions');
    }
}
