<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMessageColumnToUserFriendRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_friend_requests', function (Blueprint $table) {
            $table->foreignId('chat_message_id')->nullable();
            $table->foreign('chat_message_id')->references('id')->on('chat_messages')->onDelete("SET NULL");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_friend_requests', function (Blueprint $table) {
            $table->dropForeign("user_friend_requests_chat_message_id_foreign");
            $table->dropColumn("chat_message_id");
        });
    }
}
