<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTimefieldsToClockTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('clock', function (Blueprint $table) {
            $table->timestamp('in')->nullable();
            $table->timestamp('out')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('clock', function (Blueprint $table) {
            $table->dropColumn('in');
            $table->dropColumn('out');
        });
    }
}
