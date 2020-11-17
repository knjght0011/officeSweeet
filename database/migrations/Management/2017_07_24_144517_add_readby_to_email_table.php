<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddReadbyToEmailTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('management')->table('email', function (Blueprint $table) {
            $table->json('readby');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('management')->table('email', function (Blueprint $table) {
            if (Schema::hasColumn('email', 'readby'))
            {
                $table->dropColumn('readby');
            } 
        });
    }
}
