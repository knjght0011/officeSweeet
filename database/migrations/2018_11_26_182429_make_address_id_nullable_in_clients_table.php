<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MakeAddressIdNullableInClientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('clients', function (Blueprint $table) {
            DB::statement("ALTER TABLE `clients` MODIFY `address_id` INTEGER UNSIGNED NULL DEFAULT NULL;");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('clients', function (Blueprint $table) {
            DB::statement('ALTER TABLE `clients` MODIFY `address_id` INTEGER UNSIGNED NOT NULL;');
        });
    }
}
