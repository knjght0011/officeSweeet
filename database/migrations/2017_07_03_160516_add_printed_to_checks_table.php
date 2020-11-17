<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

use Carbon\Carbon;

class AddPrintedToChecksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('checks', function (Blueprint $table) {
            $table->dateTime('printed')->default(Carbon::now())->nullable();
        });
        if (Schema::hasColumn('checks', 'printed'))
        {
            Schema::table('checks', function (Blueprint $table) {
                $table->dateTime('printed')->default(null)->nullable()->change();
            });   
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasColumn('checks', 'printed'))
        {
            Schema::table('checks', function (Blueprint $table) {
                $table->dropColumn('printed');
            });
        }
    }
}
