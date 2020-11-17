<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateClientsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
            Schema::create('clients', function($table)
            {
		$table->increments('id');
                $table->string('name')->nullable();
                $table->integer('address_id')->unsigned();
                $table->foreign('address_id')->references('id')->on('address');
                $table->string('notes');
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
		Schema::drop('clients');
	}

}
