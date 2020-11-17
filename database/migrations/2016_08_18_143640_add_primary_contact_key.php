<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPrimaryContactKey extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('clients', function (Blueprint $table) {
            $table->integer('primarycontact_id')->unsigned()->nullable()->default(null);
            $table->foreign('primarycontact_id')->references('id')->on('clientcontacts');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('clients', function (Blueprint $table) {
            $table->dropForeign(['primarycontact_id']);
            $table->dropColumn('primarycontact_id');
        });
    }
}
