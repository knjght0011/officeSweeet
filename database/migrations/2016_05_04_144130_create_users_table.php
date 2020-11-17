<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
            Schema::create('users', function($table)
            {
                $table->increments('id');
                $table->string('email')->unique();
                $table->string('password');
                $table->dateTime('passwordlastchanged');
                $table->tinyInteger('locked');
                $table->integer('failedattempts');
                $table->tinyInteger('disabled');
                $table->softDeletes();
                #$table->integer('disabledeventid'); #for when events table gets added
                $table->timestamps();
                $table->rememberToken();
                $table->tinyInteger('usermanagement');
            });
        }

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('users');
	}

}
