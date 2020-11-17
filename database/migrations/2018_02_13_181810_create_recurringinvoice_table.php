<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRecurringinvoiceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('recurringinvoice', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('quote_id')->unsigned();
            $table->foreign('quote_id')->references('id')->on('quote');
            $table->date('start');
            $table->date('end')->nullable()->default(null);
            $table->date('lastrun')->nullable()->default(null);
            $table->tinyInteger('repeat_freq');
            $table->tinyInteger('repeat_number');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::table('quote', function (Blueprint $table) {
            $table->integer('recurringinvoice_id')->unsigned()->unsigned()->nullable();
            $table->foreign('recurringinvoice_id')->references('id')->on('recurringinvoice');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('recurringinvoice');

        Schema::table('quote', function (Blueprint $table) {
            $table->dropForeign(['recurringinvoice_id']);
            $table->dropColumn('recurringinvoice_id');
        });
    }
}
