<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class IncreaseSizeOfContentFieldInCustomtablesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('customtables', function (Blueprint $table) {
            DB::statement("ALTER TABLE customtables MODIFY COLUMN content MEDIUMTEXT");
            #$table->string('content', 65534)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('customtables', function (Blueprint $table) {
            //
        });
    }
}
