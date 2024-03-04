<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateOnDeleteTriggerForUserData extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->dropForeign('posts_user_id_foreign');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('CASCADE');
        });

        Schema::table('post_comments', function (Blueprint $table) {
            $table->dropForeign('post_comments_user_id_foreign');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('CASCADE');
        });

        Schema::table('chat_messages', function (Blueprint $table) {
            $table->dropForeign('chat_messages_user_id_foreign');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->dropForeign('posts_user_id_foreign');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('SET NULL');
        });

        Schema::table('post_comments', function (Blueprint $table) {
            $table->dropForeign('post_comments_user_id_foreign');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('SET NULL');
        });

        Schema::table('chat_messages', function (Blueprint $table) {
            $table->dropForeign('chat_messages_user_id_foreign');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('SET NULL');
        });
    }
}
