<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPhoneNumbers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('clients', function (Blueprint $table) {
            $table->string('phonenumber')->default("");
            $table->string('email')->default("");
        });
        Schema::table('clientcontacts', function (Blueprint $table) {
            $table->string('officenumber')->default("");
            $table->string('mobilenumber')->default("");
            $table->string('homenumber')->default("");
        });
        Schema::table('vendors', function (Blueprint $table) {
            $table->string('phonenumber')->default("");
            $table->string('email')->default("");
        });
        Schema::table('vendorcontacts', function (Blueprint $table) {
            $table->string('officenumber')->default("");
            $table->string('mobilenumber')->default("");
            $table->string('homenumber')->default("");
        });
        Schema::table('employees', function (Blueprint $table) {
            $table->string('officenumber')->default("");
            $table->string('mobilenumber')->default("");
            $table->string('homenumber')->default("");
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
            $table->dropColumn('phonenumber');
            $table->dropColumn('email');
        });
        Schema::table('clientcontacts', function (Blueprint $table) {
            $table->dropColumn('officenumber');
            $table->dropColumn('mobilenumber');
            $table->dropColumn('homenumber');
        });
        Schema::table('vendors', function (Blueprint $table) {
            $table->dropColumn('phonenumber');
            $table->dropColumn('email');
        });
        Schema::table('vendorcontacts', function (Blueprint $table) {
            $table->dropColumn('officenumber');
            $table->dropColumn('mobilenumber');
            $table->dropColumn('homenumber');
        });
        Schema::table('employees', function (Blueprint $table) {
            $table->dropColumn('officenumber');
            $table->dropColumn('mobilenumber');
            $table->dropColumn('homenumber');
        });
    }
}
