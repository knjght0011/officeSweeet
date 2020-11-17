<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSchedulerParentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('scheduler_parent', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title');
            $table->boolean('weekday');
            $table->date('start_date');
            $table->time('start_time');
            $table->time('end_time');
            $table->boolean('repeats');
            $table->tinyInteger('repeat_freq');
            $table->integer('client_id')->unsigned()->nullable();
            $table->foreign('client_id')->references('id')->on('clients');
            $table->integer('vendor_id')->unsigned()->nullable();
            $table->foreign('vendor_id')->references('id')->on('vendors');
            $table->integer('user_id')->unsigned();
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
        Schema::dropIfExists('scheduler_parent');
    }
}


/**
 * 
parent_id	int(30)unsigned	primary	NULL	auto_increment
title	varchar(120)		NULL	
weekday	int(1)		NULL	
start_date	date		NULL	
start_time	time		NULL	
end_time	time		NULL	
repeats	tinyint(1)		NULL	
repeat_freq	tinyint(1)		NULL	
 */