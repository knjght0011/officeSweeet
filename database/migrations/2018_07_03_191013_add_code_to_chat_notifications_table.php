<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCodeToChatNotificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('chat_notifications', function (Blueprint $table) {
            DB::statement('ALTER TABLE `chat_notifications` MODIFY `user_id` INTEGER UNSIGNED NULL;');
            $table->string('link')->nullable()->default(null)->change();
            $table->string('code')->nullable()->default(null)->after('link');
            $table->text('readby');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('chat_notifications', function (Blueprint $table) {
            DB::statement('ALTER TABLE `chat_notifications` MODIFY `user_id` INTEGER UNSIGNED NOT NULL;');
            $table->dropColumn('code');
            $table->dropColumn('readby');
        });
    }
}
