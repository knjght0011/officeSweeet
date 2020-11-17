<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSignatureTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('signature', function (Blueprint $table) {
            $table->increments('id');

            $table->decimal('left', 20, 20);
            $table->decimal('top', 20, 20);
            $table->decimal('width', 20, 20);
            $table->decimal('height', 20, 20);

            $table->string('signature')->nullable()->default(null);

            $table->longtext('image')->nullable()->default(null);

            $table->integer('signing_id')->unsigned()->nullable()->default(null);
            $table->foreign('signing_id')->references('id')->on('signing');

            $table->integer('clientcontact_id')->unsigned()->nullable()->default(null);
            $table->foreign('clientcontact_id')->references('id')->on('clientcontacts');

            $table->integer('vendorcontact_id')->unsigned()->nullable()->default(null);
            $table->foreign('vendorcontact_id')->references('id')->on('vendorcontacts');

            $table->integer('user_id')->unsigned()->nullable()->default(null);
            $table->foreign('user_id')->references('id')->on('users');
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
        Schema::dropIfExists('signature');
    }
}
