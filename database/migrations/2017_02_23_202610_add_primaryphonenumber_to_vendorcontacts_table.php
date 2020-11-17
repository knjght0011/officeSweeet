<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPrimaryphonenumberToVendorcontactsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('vendorcontacts', function (Blueprint $table) {
            $table->tinyInteger('primaryphonenumber')->default(1);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('vendorcontacts', function (Blueprint $table) {
            $table->dropColumn('primaryphonenumber');
        });
    }
}
