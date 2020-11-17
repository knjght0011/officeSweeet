<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MakeAddressIdNullableOnPatientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('patients', function (Blueprint $table) {
            DB::statement("ALTER TABLE `patients` MODIFY `address_id` INTEGER UNSIGNED NULL DEFAULT NULL;");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('patients', function (Blueprint $table) {
            DB::statement('ALTER TABLE `patients` MODIFY `address_id` INTEGER UNSIGNED NOT NULL;');
        });
    }
}
