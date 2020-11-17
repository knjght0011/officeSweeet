<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddProductidsToQuoteitemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('quoteitems', function (Blueprint $table) {
            $table->integer('productlibrary_id')->unsigned()->nullable()->default(null)->after('user_id');
            $table->foreign('productlibrary_id')->references('id')->on('productlibrary');

            $table->integer('billablehours_id')->unsigned()->nullable()->default(null)->after('productlibrary_id');
            $table->foreign('billablehours_id')->references('id')->on('billablehours');

            $table->integer('receipts_id')->unsigned()->nullable()->default(null)->after('billablehours_id');
            $table->foreign('receipts_id')->references('id')->on('receipts');
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
            $table->dropForeign(['productlibrary_id']);
            $table->dropColumn('productlibrary_id');

            $table->dropForeign(['billablehours_id']);
            $table->dropColumn('billablehours_id');

            $table->dropForeign(['receipts_id']);
            $table->dropColumn('receipts_id');
        });
    }
}
