<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddServiceLibrariesIdToQuoteitemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('quoteitems', function (Blueprint $table) {
            $table->integer('service_libraries_id')->unsigned()->nullable()->default(null)->after('productlibrary_id');
            $table->foreign('service_libraries_id')->references('id')->on('service_libraries');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('quoteitems', function (Blueprint $table) {
            $table->dropForeign(['service_libraries_id']);
            $table->dropColumn('service_libraries_id');
        });
    }
}
