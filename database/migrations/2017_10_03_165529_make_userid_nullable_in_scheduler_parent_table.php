<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MakeUseridNullableInSchedulerParentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('scheduler_parent', function (Blueprint $table) {
            DB::statement('ALTER TABLE `scheduler_parent` MODIFY `user_id` INTEGER UNSIGNED NULL DEFAULT NULL;');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('scheduler_parent', function (Blueprint $table) {
            DB::statement('ALTER TABLE `scheduler_parent` MODIFY `user_id` INTEGER UNSIGNED NOT NULL;');
        });
    }
}
