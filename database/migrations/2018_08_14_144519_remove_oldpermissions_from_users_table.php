<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RemoveOldpermissionsFromUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('acp_subscription_permission');
            $table->dropColumn('acp_manage_custom_tables_permission');
            $table->dropColumn('acp_company_info_permission');
            $table->dropColumn('acp_import_export_permission');
            $table->dropColumn('acp_permission');
            $table->dropColumn('client_permission');
            $table->dropColumn('vendor_permission');
            $table->dropColumn('employee_permission');
            $table->dropColumn('login_management_permission');
            $table->dropColumn('reporting_permission');
            $table->dropColumn('journal_permission');
            $table->dropColumn('deposits_permission');
            $table->dropColumn('checks_permission');
            $table->dropColumn('reciepts_permission');
            $table->dropColumn('payroll_permission');
            $table->dropColumn('chat_permission');
            $table->dropColumn('scheduler_permission');
            $table->dropColumn('tasks_permission');
            $table->dropColumn('templates_permission');
            $table->dropColumn('assets_permission');
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
            //
        });
    }
}
