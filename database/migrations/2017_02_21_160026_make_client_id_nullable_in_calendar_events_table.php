<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MakeClientIdNullableInCalendarEventsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('calendar_events', function (Blueprint $table) {
            DB::statement('ALTER TABLE `calendar_events` MODIFY `clients_id` INTEGER UNSIGNED NULL DEFAULT NULL;');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('calendar_events', function (Blueprint $table) {
            DB::statement('ALTER TABLE `calendar_events` MODIFY `clients_id` INTEGER UNSIGNED NOT NULL;');
        });
    }
}
