<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MakeAddressIdNullableOnClientcontactsAndVendorcontactsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('clientcontacts', function (Blueprint $table) {
            DB::statement("ALTER TABLE `clientcontacts` MODIFY `address_id` INTEGER UNSIGNED NULL DEFAULT NULL;");
        });
        Schema::table('vendorcontacts', function (Blueprint $table) {
            DB::statement("ALTER TABLE `vendorcontacts` MODIFY `address_id` INTEGER UNSIGNED NULL DEFAULT NULL;");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('clientcontacts', function (Blueprint $table) {
            DB::statement('ALTER TABLE `clientcontacts` MODIFY `address_id` INTEGER UNSIGNED NOT NULL;');
        });
        Schema::table('vendorcontacts', function (Blueprint $table) {
            DB::statement('ALTER TABLE `vendorcontacts` MODIFY `address_id` INTEGER UNSIGNED NOT NULL;');
        });
    }
}
