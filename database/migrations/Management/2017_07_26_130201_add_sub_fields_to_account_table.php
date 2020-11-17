<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSubFieldsToAccountTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('management')->table('account', function (Blueprint $table) {
            $table->renameColumn('licenseduseres', 'licensedusers');
            $table->string('plan_name')->default("");
            $table->string('subscription_id')->default("");
            $table->string('transection_id')->default("");
            $table->string('company_name')->default("");
            $table->string('business_type')->default("");
            $table->string('name')->default("");
            $table->string('email')->default("");
            $table->string('tel_no')->default("");
            $table->string('address')->default("");
            $table->string('buisness_address')->default("");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('management')->table('account', function (Blueprint $table) {
            $table->renameColumn('licensedusers', 'licenseduseres');
            $table->dropColumn('plan_name');
            $table->dropColumn('subscription_id');
            $table->dropColumn('transection_id');
            $table->dropColumn('company_name');
            $table->dropColumn('business_type');
            $table->dropColumn('name');
            $table->dropColumn('email');
            $table->dropColumn('tel_no');
        });
    }
}
