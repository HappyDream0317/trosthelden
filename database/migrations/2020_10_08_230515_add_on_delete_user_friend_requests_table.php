<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddOnDeleteUserFriendRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_friend_requests', function (Blueprint $table) {
            $table->dropForeign('user_friend_requests_user_id_foreign');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('CASCADE');

            $table->dropForeign('user_friend_requests_added_user_id_foreign');
            $table->foreign('added_user_id')->references('id')->on('users')->onDelete('CASCADE');
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
            $table->dropForeign('user_friend_requests_user_id_foreign');
            $table->foreign('user_id')->references('id')->on('users');

            $table->dropForeign('user_friend_requests_added_user_id_foreign');
            $table->foreign('added_user_id')->references('id')->on('users');
        });
    }
}
