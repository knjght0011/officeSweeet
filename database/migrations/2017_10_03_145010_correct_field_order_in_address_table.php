<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CorrectFieldOrderInAddressTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('address', function (Blueprint $table) {
            DB::statement("ALTER TABLE address MODIFY COLUMN number VARCHAR(255) AFTER id");
            DB::statement("ALTER TABLE address MODIFY COLUMN region VARCHAR(255) AFTER state");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('address', function (Blueprint $table) {
            DB::statement("ALTER TABLE address MODIFY COLUMN number VARCHAR(255) AFTER updated_at");
            DB::statement("ALTER TABLE address MODIFY COLUMN region VARCHAR(255) AFTER number");
        });
    }
}
