<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddNewDetailsFieldsToClientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('clients', function (Blueprint $table) {
            $table->string('custom_field_label')->default("")->after("comments");
            $table->string('custom_field_text')->default("")->after("custom_field_label");
            $table->date('follow_up_date')->nullable()->default(null)->after("custom_field_text");
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
            $table->dropColumn('custom_field_label');
            $table->dropColumn('custom_field_text');
            $table->dropColumn('follow_up_date');
        });
    }
}
