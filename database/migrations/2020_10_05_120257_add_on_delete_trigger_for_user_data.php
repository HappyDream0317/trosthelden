<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddOnDeleteTriggerForUserData extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        /*
         * Hello 👋 This migration adds an onDelete trigger for almost all user related tables
         * and if necessary creates the foreign key, if it wasn't already set.
         */
        Schema::table('user_watchlist', function (Blueprint $table) {
            $table->dropForeign('user_watchlist_user_id_foreign');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('CASCADE');

            $table->dropForeign('user_watchlist_watched_user_id_foreign');
            $table->foreign('watched_user_id')->references('id')->on('users')->onDelete('CASCADE');
        });

        Schema::table('user_blocklist', function (Blueprint $table) {
            $table->dropForeign('user_blocklist_blocked_user_id_foreign');
            $table->foreign('blocked_user_id')->references('id')->on('users')->onDelete('CASCADE');

            $table->dropForeign('user_blocklist_user_id_foreign');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('CASCADE');
        });

        Schema::table('user_watchlist', function (Blueprint $table) {
            $table->dropForeign('user_watchlist_user_id_foreign');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('CASCADE');

            $table->dropForeign('user_watchlist_watched_user_id_foreign');
            $table->foreign('watched_user_id')->references('id')->on('users')->onDelete('CASCADE');
        });

        Schema::table('user_friends', function (Blueprint $table) {
            $table->dropForeign('user_friends_user_id_foreign');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('CASCADE');

            $table->dropForeign('user_friends_foreign_user_id_foreign');
            $table->foreign('foreign_user_id')->references('id')->on('users')->onDelete('CASCADE');
        });

        Schema::table('action_events', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users')->onDelete('CASCADE');
        });

        Schema::table('profile_mottoes', function (Blueprint $table) {
            $table->dropForeign('profile_mottoes_user_id_foreign');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('CASCADE');
        });

        Schema::table('profile_progress', function (Blueprint $table) {
            $table->dropForeign('profile_progress_user_id_foreign');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('CASCADE');
        });

        Schema::table('profile_visibilities', function (Blueprint $table) {
            $table->dropForeign('profile_visibilities_user_id_foreign');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('CASCADE');
        });

        Schema::table('profile_question_answers', function (Blueprint $table) {
            $table->dropForeign('profile_question_answers_user_id_foreign');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('CASCADE');
        });

        Schema::table('reports', function (Blueprint $table) {
            $table->bigInteger('user_id')->unsigned()->change();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('CASCADE');
        });

        Schema::table('matching_user_answers', function (Blueprint $table) {
            $table->dropForeign('matching_user_answers_user_id_foreign');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('CASCADE');
        });

        Schema::table('oauth_auth_codes', function (Blueprint $table) {
            $table->bigInteger('user_id')->unsigned()->change();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('CASCADE');
        });

        Schema::table('oauth_access_tokens', function (Blueprint $table) {
            $table->bigInteger('user_id')->unsigned()->change();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('CASCADE');
        });

        Schema::table('posts', function (Blueprint $table) {
            $table->dropForeign('posts_user_id_foreign');
            $table->bigInteger('user_id')->unsigned()->nullable()->change();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('SET NULL');
        });

        Schema::table('post_comments', function (Blueprint $table) {
            $table->dropForeign('post_comments_user_id_foreign');
            $table->bigInteger('user_id')->unsigned()->nullable()->change();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('SET NULL');
        });

        Schema::table('chat_messages', function (Blueprint $table) {
            $table->bigInteger('user_id')->unsigned()->nullable()->change();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('SET NULL');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_watchlist', function (Blueprint $table) {
            $table->dropForeign('user_watchlist_user_id_foreign');
            $table->foreign('user_id')->references('id')->on('users');

            $table->dropForeign('user_watchlist_watched_user_id_foreign');
            $table->foreign('watched_user_id')->references('id')->on('users');
        });

        Schema::table('user_blocklist', function (Blueprint $table) {
            $table->dropForeign('user_blocklist_blocked_user_id_foreign');
            $table->foreign('blocked_user_id')->references('id')->on('users');

            $table->dropForeign('user_blocklist_user_id_foreign');
            $table->foreign('user_id')->references('id')->on('users');
        });

        Schema::table('user_watchlist', function (Blueprint $table) {
            $table->dropForeign('user_watchlist_user_id_foreign');
            $table->foreign('user_id')->references('id')->on('users');

            $table->dropForeign('user_watchlist_watched_user_id_foreign');
            $table->foreign('watched_user_id')->references('id')->on('users');
        });

        Schema::table('user_friends', function (Blueprint $table) {
            $table->dropForeign('user_friends_user_id_foreign');
            $table->foreign('user_id')->references('id')->on('users');

            $table->dropForeign('user_friends_foreign_user_id_foreign');
            $table->foreign('foreign_user_id')->references('id')->on('users');
        });

        Schema::table('action_events', function (Blueprint $table) {
            $table->dropForeign('action_events_user_id_foreign');
        });

        Schema::table('profile_mottoes', function (Blueprint $table) {
            $table->dropForeign('profile_mottoes_user_id_foreign');
            $table->foreign('user_id')->references('id')->on('users');
        });

        Schema::table('profile_progress', function (Blueprint $table) {
            $table->dropForeign('profile_progress_user_id_foreign');
            $table->foreign('user_id')->references('id')->on('users');
        });

        Schema::table('profile_visibilities', function (Blueprint $table) {
            $table->dropForeign('profile_visibilities_user_id_foreign');
            $table->foreign('user_id')->references('id')->on('users');
        });

        Schema::table('profile_question_answers', function (Blueprint $table) {
            $table->dropForeign('profile_question_answers_user_id_foreign');
            $table->foreign('user_id')->references('id')->on('users');
        });

        Schema::table('reports', function (Blueprint $table) {
            $table->dropForeign('reports_user_id_foreign');
        });

        Schema::table('matching_user_answers', function (Blueprint $table) {
            $table->dropForeign('matching_user_answers_user_id_foreign');
            $table->foreign('user_id')->references('id')->on('users');
        });

        Schema::table('oauth_auth_codes', function (Blueprint $table) {
            $table->dropForeign('oauth_auth_codes_user_id_foreign');
        });

        Schema::table('oauth_access_tokens', function (Blueprint $table) {
            $table->dropForeign('oauth_access_tokens_user_id_foreign');
        });

        Schema::table('posts', function (Blueprint $table) {
            $table->dropForeign('posts_user_id_foreign');
            $table->foreign('user_id')->references('id')->on('users');
        });

        Schema::table('post_comments', function (Blueprint $table) {
            $table->dropForeign('post_comments_user_id_foreign');
            $table->foreign('user_id')->references('id')->on('users');
        });

        Schema::table('chat_messages', function (Blueprint $table) {
            $table->dropForeign('chat_messages_user_id_foreign');
        });
    }
}
