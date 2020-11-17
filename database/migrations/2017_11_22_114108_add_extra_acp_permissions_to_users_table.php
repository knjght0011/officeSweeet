<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddExtraAcpPermissionsToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('accessacp');
            $table->dropColumn('usermanagement');
            $table->dropColumn('createcustomtables');
            $table->dropColumn('editcompanyinfo');

            $table->renameColumn('subscription_permission', 'acp_subscription_permission');

            $table->tinyInteger('acp_manage_custom_tables_permission')->after('subscription_permission')->default(0);
            $table->tinyInteger('acp_company_info_permission')->after('acp_manage_custom_tables_permission')->default(0);
            $table->tinyInteger('acp_import_export_permission')->after('acp_company_info_permission')->default(0);

            $table->tinyInteger('login_management_permission')->after('employee_permission')->default(0);
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
            $table->tinyInteger('accessacp')->after('payroll_permission')->default(0);
            $table->tinyInteger('usermanagement')->after('accessacp')->default(0);
            $table->tinyInteger('createcustomtables')->after('usermanagement')->default(0);
            $table->tinyInteger('editcompanyinfo')->after('createcustomtables')->default(0);

            $table->renameColumn('acp_subscription_permission', 'subscription_permission');

            $table->dropColumn('acp_manage_custom_tables_permission');
            $table->dropColumn('acp_company_info_permission');
            $table->dropColumn('acp_import_export_permission');

            $table->dropColumn('login_management_permission');
        });
    }
}
