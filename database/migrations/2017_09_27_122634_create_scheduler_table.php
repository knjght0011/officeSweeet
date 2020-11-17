<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSchedulerTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('scheduler', function (Blueprint $table) {
            $table->increments('event_id');
            $table->integer('parent_id')->unsigned();
            $table->foreign('parent_id')->references('id')->on('scheduler_parent');            
            $table->datetime('start');
            $table->datetime('end');
            $table->string('title');
            $table->string('contents');

            
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
        Schema::dropIfExists('scheduler');
    }
}

/*
 * 
 * 
event_id	int(30)unsigned	primary	NULL	auto_increment
parent_id	int(30)unsigned		NULL	
start	datetime		NULL	
end	datetime		NULL	
title	varchar(120)		NULL
 */