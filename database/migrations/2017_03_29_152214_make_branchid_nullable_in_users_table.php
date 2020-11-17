<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MakeBranchidNullableInUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            #DB::statement('ALTER TABLE `users` MODIFY `branch_id` INTEGER UNSIGNED NULL;');
            $table->integer('branch_id')->unsigned()->nullable();
            $table->foreign('branch_id')->references('id')->on('branches');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            #DB::statement('ALTER TABLE `users` MODIFY `branch_id` INTEGER UNSIGNED NOT NULL;');
            $table->dropColumn('branch_id');
        });
    }
}
