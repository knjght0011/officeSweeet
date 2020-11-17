<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTrackstockToProductlibraryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::rename('ProductLibrary', 'productlibrary');
        Schema::table('productlibrary', function (Blueprint $table) {
            $table->boolean('trackstock')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::rename('productlibrary', 'ProductLibrary');
        Schema::table('productlibrary', function (Blueprint $table) {
            $table->dropColumn('trackstock');
        });
    }
}
