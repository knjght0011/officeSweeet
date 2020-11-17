<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MakeUserIdNullableOnDepositTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('deposit', function (Blueprint $table) {
            DB::statement('ALTER TABLE `deposit` MODIFY `user_id` INTEGER UNSIGNED NULL DEFAULT NULL;');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('deposit', function (Blueprint $table) {
            DB::statement('ALTER TABLE `calendar_events` MODIFY `user_id` INTEGER UNSIGNED NOT NULL;');
        });
    }
}
