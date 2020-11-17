<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateServiceLibrariesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('service_libraries', function (Blueprint $table) {
            $table->increments('id');
            $table->string('sku');
            $table->string('description');
            $table->decimal('cost', 22, 2);
            $table->decimal('charge', 22, 2);
            $table->boolean('taxable');
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
        Schema::dropIfExists('service_libraries');
    }
}
