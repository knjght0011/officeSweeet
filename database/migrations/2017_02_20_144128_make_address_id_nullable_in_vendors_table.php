<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MakeAddressIdNullableInVendorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('vendors', function (Blueprint $table) {
            DB::statement('ALTER TABLE `vendors` MODIFY `address_id` INTEGER UNSIGNED NULL;');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('vendors', function (Blueprint $table) {
            DB::statement('ALTER TABLE `vendors` MODIFY `address_id` INTEGER UNSIGNED NOT NULL;');
        });
    }
}
