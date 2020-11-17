<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddReembursementToReceiptsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('receipts', function (Blueprint $table) {
            DB::statement('ALTER TABLE `receipts` MODIFY `user_id` INTEGER UNSIGNED NULL DEFAULT NULL;');
            $table->tinyInteger('reimbursement')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('receipts', function (Blueprint $table) {
            DB::statement('ALTER TABLE `receipts` MODIFY `user_id` INTEGER UNSIGNED NOT NULL;');
            $table->dropColumn('reimbursement');
        });
    }
}
