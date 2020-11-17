<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddLocationAndCategoryToProductlibraryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('productlibrary', function (Blueprint $table) {
            $table->string('location');
            $table->string('category');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('productlibrary', function (Blueprint $table) {
            $table->dropColumn('location');
            $table->dropColumn('category');
        });
    }
}
