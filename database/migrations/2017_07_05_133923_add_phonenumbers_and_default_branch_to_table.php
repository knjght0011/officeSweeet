<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPhonenumbersAndDefaultBranchToTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('branches', function (Blueprint $table) {
            $table->string('phonenumber')->default("");
            $table->string('faxnumber')->default("");
            $table->string('default')->default(null)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasColumn('branches', 'phonenumber'))
        {
            Schema::table('branches', function (Blueprint $table) {
                $table->dropColumn('phonenumber');
            });
        }
        if (Schema::hasColumn('branches', 'faxnumber'))
        {
            Schema::table('branches', function (Blueprint $table) {
                $table->dropColumn('faxnumber');
            });
        } 
        if (Schema::hasColumn('branches', 'default'))
        {
            Schema::table('branches', function (Blueprint $table) {
                $table->dropColumn('default');
            });
        }         
    }
}
