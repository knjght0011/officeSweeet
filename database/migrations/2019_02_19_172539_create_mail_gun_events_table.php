<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMailGunEventsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mail_gun_events', function (Blueprint $table) {
            $table->increments('id');
            $table->string('event');
            $table->string('messagetype');
            $table->text('data');
            $table->integer('emails_id')->unsigned()->nullable()->default(null);
            $table->foreign('emails_id')->references('id')->on('emails');
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
        Schema::dropIfExists('mail_gun_events');
    }
}
