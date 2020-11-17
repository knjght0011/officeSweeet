<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateExpenseaccountsubcategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('expenseaccountsubcategories', function (Blueprint $table) {
            $table->increments('id');
            $table->string('subcategory');
            $table->integer('expenseaccountcategories_id')->unsigned();
            $table->foreign('expenseaccountcategories_id')->references('id')->on('expenseaccountcategories');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('expenseaccountsubcategories');
    }
}
