<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCommissionToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->decimal('service_commission', 4, 2)->default(0.00);
            $table->decimal('product_commission', 4, 2)->default(0.00);
            $table->date('start_date')->nullable()->default(null);
            $table->date('end_date')->nullable()->default(null);

            $table->string('emergency_contact_name')->default("");
            $table->string('emergency_contact_relationship')->default("");
            $table->string('emergency_contact_phone_number')->default("");
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
            $table->dropColumn('service_commission');
            $table->dropColumn('product_commission');
            $table->dropColumn('start_date');
            $table->dropColumn('end_date');

            $table->dropColumn('emergency_contact_name');
            $table->dropColumn('emergency_contact_relationship');
            $table->dropColumn('emergency_contact_phone_number');
        });
    }
}
