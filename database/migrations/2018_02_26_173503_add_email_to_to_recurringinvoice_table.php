<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddEmailToToRecurringinvoiceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('recurringinvoice', function (Blueprint $table) {
            $table->integer('email_to')->unsigned()->unsigned()->nullable();
            $table->foreign('email_to')->references('id')->on('clientcontacts');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('recurringinvoice', function (Blueprint $table) {
            $table->dropForeign(['email_to']);
            $table->dropColumn('email_to');
        });
    }
}
