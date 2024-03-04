<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddUserRelationTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_watchlist', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users');
            $table->foreignId('watched_user_id')->constrained('users');
            $table->rememberToken();
            $table->timestamps();
        });

        Schema::create('user_blocklist', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users');
            $table->foreignId('blocked_user_id')->constrained('users');
            $table->rememberToken();
            $table->timestamps();
        });

        Schema::create('user_friends', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users');
            $table->foreignId('foreign_user_id')->constrained('users');
            $table->rememberToken();
            $table->timestamps();
        });

        Schema::create('user_friend_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users');
            $table->foreignId('added_user_id')->constrained('users');
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_watchlist');
        Schema::dropIfExists('user_blocklist');
        Schema::dropIfExists('user_friends');
        Schema::dropIfExists('user_friend_requests');
    }
}
