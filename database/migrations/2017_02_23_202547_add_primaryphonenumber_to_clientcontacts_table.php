<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPrimaryphonenumberToClientcontactsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('clientcontacts', function (Blueprint $table) {
            $table->tinyInteger('primaryphonenumber')->default(1);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('clientcontacts', function (Blueprint $table) {
            $table->dropColumn('primaryphonenumber');
        });
    }
}
