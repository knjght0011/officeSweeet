<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPermissionsToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->tinyInteger('acp_permission')->after('canlogin')->default(0);
            $table->tinyInteger('client_permission')->after('acp_permission')->default(0);
            $table->tinyInteger('vendor_permission')->after('client_permission')->default(0);
            $table->tinyInteger('employee_permission')->after('vendor_permission')->default(0);
            $table->tinyInteger('reporting_permission')->after('employee_permission')->default(0);
            $table->tinyInteger('journal_permission')->after('reporting_permission')->default(0);
            $table->tinyInteger('deposits_permission')->after('journal_permission')->default(0);
            $table->tinyInteger('checks_permission')->after('deposits_permission')->default(0);
            $table->tinyInteger('reciepts_permission')->after('checks_permission')->default(0);
        });
    }
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('acp_permission');
            $table->dropColumn('client_permission');
            $table->dropColumn('vendor_permission');
            $table->dropColumn('employee_permission');
            $table->dropColumn('reporting_permission');
            $table->dropColumn('journal_permission');
            $table->dropColumn('deposits_permission');
            $table->dropColumn('checks_permission');
            $table->dropColumn('reciepts_permission');
        });
    }
}
