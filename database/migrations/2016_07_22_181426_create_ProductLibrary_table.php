<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductLibraryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('ProductLibrary', function (Blueprint $table) {
            $table->increments('id');
            $table->string('productname');
            $table->decimal('charge', 24, 4);
            $table->decimal('cost', 24, 4);
            $table->boolean('taxable');
            $table->string('billingfrequency');
            $table->integer('stock');
            $table->integer('reorderlevel');
            $table->integer('restockto');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('ProductLibrary');
    }
}
