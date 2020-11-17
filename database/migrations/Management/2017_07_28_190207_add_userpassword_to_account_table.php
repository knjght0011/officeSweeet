<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddUserpasswordToAccountTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('management')->table('account', function (Blueprint $table) {
            $table->string('userpassword')->default("");
            $table->json('installstage')->default("");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('management')->table('account', function (Blueprint $table) {
            $table->dropColumn('userpassword');
            $table->dropColumn('installstage');
        });
    }
}
