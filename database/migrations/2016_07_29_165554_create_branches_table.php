<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBranchesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('branches', function($table)
        {
            $table->increments('id');
            $table->string('number');
            $table->string('address1', 50);
            $table->string('address2', 50);
            $table->string('city', 50);
            $table->string('region');
            $table->string('state', 50);
            $table->string('zip', 10);
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
        Schema::drop('branches');
    }
}
