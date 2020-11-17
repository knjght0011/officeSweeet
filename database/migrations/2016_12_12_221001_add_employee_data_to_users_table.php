<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddEmployeeDataToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('employeeid', 50)->after('id');
            $table->string('firstname', 50)->after('employeeid');
            $table->string('middlename', 50)->after('firstname');
            $table->string('lastname', 50)->after('middlename');
            $table->string('ssn', 12)->after('lastname');
            $table->string('driverslicense', 15)->after('ssn');
            $table->string('department')->after('driverslicense');
            $table->string('comments')->after('department');
            $table->tinyInteger('canlogin')->after('disabled')->default(0);
            $table->tinyInteger('accessacp')->after('canlogin')->default(0);
            $table->tinyInteger('createcustomtables')->after('usermanagement')->default(0);
            $table->tinyInteger('editcompanyinfo')->after('createcustomtables')->default(0);
            $table->integer('address_id')->unsigned()->default(1)->nullable();
            $table->foreign('address_id')->references('id')->on('address');
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
