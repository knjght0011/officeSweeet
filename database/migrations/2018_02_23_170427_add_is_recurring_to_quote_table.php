<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIsRecurringToQuoteTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('quote', function (Blueprint $table) {
            $table->integer('is_recurring')->unsigned()->unsigned()->nullable();
            $table->foreign('is_recurring')->references('id')->on('recurringinvoice');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('quote', function (Blueprint $table) {
            $table->dropForeign(['is_recurring']);
            $table->dropColumn('is_recurring');
        });
    }
}
