<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MakeUserIdNullableInClientnotesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('clientnotes', function (Blueprint $table) {

            DB::statement("ALTER TABLE `clientnotes` MODIFY `user_id` INTEGER UNSIGNED NULL DEFAULT NULL;");

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('clientnotes', function (Blueprint $table) {

            DB::statement('ALTER TABLE `clientnotes` MODIFY `user_id` INTEGER UNSIGNED NOT NULL;');

        });
    }
}
