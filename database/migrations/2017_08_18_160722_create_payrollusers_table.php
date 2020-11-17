<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePayrollusersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payrollusers', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users');
            $table->integer('payroll_id')->unsigned()->nullable()->default(null);
            $table->foreign('payroll_id')->references('id')->on('payroll');
            $table->boolean('final')->default(0);
            
            $table->string('description')->default("");
            $table->string('comment')->default("");
            $table->boolean('taxable')->default(1);
            $table->decimal('netpay', 24, 4)->default(0);
            $table->integer('units')->default(1);
            $table->decimal('total', 24, 4)->default(0);
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
        Schema::dropIfExists('payrollusers');
    }
}

/*
                            Description

                            Comment

                            Taxable

                            Net Pay($)

                            Units

                            Total($)
 * 
 */