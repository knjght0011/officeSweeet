<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPoIdToSigningTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('signing', function (Blueprint $table) {
            $table->integer('purchaseorder_id')->unsigned()->nullable()->default(null)->after('originalreport_id');
            $table->foreign('purchaseorder_id')->references('id')->on('purchaseorders');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('signing', function (Blueprint $table) {
            $table->dropForeign(['purchaseorder_id']);
            $table->dropColumn('purchaseorder_id');
        });
    }
}
