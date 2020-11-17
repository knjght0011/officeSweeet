<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEmailTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('management')->create('email', function (Blueprint $table) {
            $table->increments('id');
            $table->string('recipient')->default('');
            $table->string('sender')->default('');
            $table->string('subject')->default('');
            $table->string('from')->default('');
            $table->string('received')->default('');
            $table->string('message_id')->default('');
            $table->dateTimeTz('date')->useCurrent = true;
            $table->string('user_agent')->default('');
            $table->string('mime_version')->default('');
            $table->string('to')->default('');
            $table->string('references')->default('');
            $table->string('in_reply_to')->default('');
            $table->text('x_mailgun_variables')->default('');
            $table->string('content_type')->default('');
            $table->string('sender_two')->default('');
            $table->text('message_headers')->default('');
            $table->string('timestamp')->default('');
            $table->string('token')->default('');
            $table->string('signature')->default('');
            $table->text('body_plain', 21845)->default('');
            $table->text('body_html', 21845)->default('');    
            $table->text('stripped_html', 21845)->default('');
            $table->text('stripped_text', 21845)->default('');
            $table->string('stripped_signature')->default('');
            $table->text('content_id_map')->default('');        
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
        Schema::dropIfExists('email');
    }
}
