<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSkuNumbers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ProductLibrary', function (Blueprint $table) {
            $table->string('SKU')->default("");
        });
        Schema::table('quoteitems', function (Blueprint $table) {
            $table->string('SKU')->default("");
        });
        Schema::table('invoiceitems', function (Blueprint $table) {
            $table->string('SKU')->default("");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ProductLibrary', function (Blueprint $table) {
            $table->dropColumn('SKU');
        });
        Schema::table('quoteitems', function (Blueprint $table) {
            $table->dropColumn('SKU');
        });
        Schema::table('invoiceitems', function (Blueprint $table) {
            $table->dropColumn('SKU');
        });
    }
}
