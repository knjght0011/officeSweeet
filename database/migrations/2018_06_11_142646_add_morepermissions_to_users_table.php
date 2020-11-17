<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddMorepermissionsToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->tinyInteger('chat_permission')->after('payroll_permission')->default(1);
            $table->tinyInteger('scheduler_permission')->after('chat_permission')->default(1);
            $table->tinyInteger('tasks_permission')->after('scheduler_permission')->default(1);
            $table->tinyInteger('templates_permission')->after('tasks_permission')->default(1);
            $table->tinyInteger('assets_permission')->after('templates_permission')->default(1);
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
            $table->dropColumn('chat_permission');
            $table->dropColumn('scheduler_permission');
            $table->dropColumn('tasks_permission');
            $table->dropColumn('templates_permission');
            $table->dropColumn('assets_permission');
        });
    }
}
