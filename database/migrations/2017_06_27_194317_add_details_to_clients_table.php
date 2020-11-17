<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDetailsToClientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('clients', function (Blueprint $table) {
            $table->date('date_of_introduction')->nullable()->default(null);
            $table->string('current_solution')->default("");
            $table->string('budget')->default("");
            $table->string('decision_maker')->default("");
            $table->string('referral_source')->default("");
            $table->integer('assigned_to')->unsigned()->nullable()->default(null);#
            $table->foreign('assigned_to')->references('id')->on('users');#
            $table->string('problem_pain')->default("");#
            $table->string('resistance_to_change')->default("");#
            $table->string('priorities')->default("");#
            $table->string('comments')->default("");#
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
            $table->dropColumn('date_of_introduction');
            $table->dropColumn('current_solution');
            $table->dropColumn('budget');
            $table->dropColumn('decision_maker');
            $table->dropColumn('referral_source');
            $table->dropColumn('assigned_to');
            $table->dropColumn('problem_pain');
            $table->dropColumn('resistance_to_change');
            $table->dropColumn('priorities');
            $table->dropColumn('comments');
        });
    }
}
