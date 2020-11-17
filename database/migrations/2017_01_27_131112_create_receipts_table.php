<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReceiptsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('receipts', function (Blueprint $table) {
            $table->increments('id');
            $table->date('date');
            $table->decimal('amount', 24, 4);
            $table->string('description');
            $table->text('catagorys');
            $table->longtext('image');
            $table->integer('client_id')->unsigned()->nullable()->default(null);
            $table->foreign('client_id')->references('id')->on('clients');
            $table->integer('vendor_id')->unsigned()->nullable()->default(null);
            $table->foreign('vendor_id')->references('id')->on('vendors');
            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users'); 
            $table->integer('entered_by_user_id')->unsigned();
            $table->foreign('entered_by_user_id')->references('id')->on('users'); 
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
        Schema::dropIfExists('receipts');
    }
}
