<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddBranchIdToQuotesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasColumn('quote', 'branch_id') === false)
        {
            Schema::table('quote', function (Blueprint $table) {
                $table->integer('branch_id')->unsigned()->nullable();
                $table->foreign('branch_id')->references('id')->on('branches');
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
        if (Schema::hasColumn('quote', 'branch_id'))
        {
            Schema::table('quote', function (Blueprint $table) {
                $table->dropColumn('branch_id');
            });
        }
    }
}
