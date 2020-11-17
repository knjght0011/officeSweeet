<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTypeToExpenseaccountcategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('expenseaccountcategories', function (Blueprint $table) {
            $table->string('type')->default("expense");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('expenseaccountcategories', function (Blueprint $table) {
            $table->dropColumn('expense');
        });
    }
}
