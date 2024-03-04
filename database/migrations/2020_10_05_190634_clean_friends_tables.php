<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CleanFriendsTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_friend_requests', function (Blueprint $table) {
            $table->dropRememberToken();
        });
        Schema::table('user_friends', function (Blueprint $table) {
            $table->dropRememberToken();
        });
        Schema::table('user_watchlist', function (Blueprint $table) {
            $table->dropRememberToken();
        });
        Schema::table('user_blocklist', function (Blueprint $table) {
            $table->dropRememberToken();
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
            $table->rememberToken();
        });
        Schema::table('user_friends', function (Blueprint $table) {
            $table->rememberToken();
        });
        Schema::table('user_watchlist', function (Blueprint $table) {
            $table->rememberToken();
        });
        Schema::table('user_blocklist', function (Blueprint $table) {
            $table->rememberToken();
        });
    }
}
