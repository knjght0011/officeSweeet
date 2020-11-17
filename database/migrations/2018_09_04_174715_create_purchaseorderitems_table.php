<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePurchaseorderitemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchaseorderitems', function (Blueprint $table) {
            $table->increments('id');

            $table->string('vendorref');

            $table->string('description');

            $table->decimal('units', 24, 4);

            $table->decimal('unitcost', 24, 4);

            $table->integer('product_id')->unsigned()->nullable();
            $table->foreign('product_id')->references('id')->on('productlibrary');

            $table->integer('purchaseorder_id')->unsigned();
            $table->foreign('purchaseorder_id')->references('id')->on('purchaseorders');

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
        Schema::dropIfExists('purchaseorderitems');
    }
}
