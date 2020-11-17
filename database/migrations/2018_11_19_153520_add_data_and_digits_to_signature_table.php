<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDataAndDigitsToSignatureTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('signature', function (Blueprint $table) {
            $table->date('signeddate')->nullable()->default(null)->after('signature');
            $table->smallInteger('digits')->nullable()->default(null)->after('signeddate');
            $table->tinyInteger('digittype')->nullable()->default(null)->after('digits');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('signature', function (Blueprint $table) {
            $table->dropColumn('signeddate');
            $table->dropColumn('digits');
            $table->dropColumn('digittype');
        });
    }
}
