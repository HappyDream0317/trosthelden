<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeinKeyToOAuthTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('oauth_personal_access_clients', function (Blueprint $table) {
            $table->integer('client_id')->unsigned()->change();
            $table->foreign('client_id')->references('id')->on('oauth_clients')->onDelete("CASCADE");
        });

        Schema::table('oauth_access_tokens', function (Blueprint $table) {
            $table->integer('client_id')->unsigned()->change();
            $table->foreign('client_id')->references('id')->on('oauth_clients')->onDelete("CASCADE");
        });

        Schema::table('oauth_auth_codes', function (Blueprint $table) {
            $table->integer('client_id')->unsigned()->change();
            $table->foreign('client_id')->references('id')->on('oauth_clients')->onDelete("CASCADE");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('oauth_personal_access_clients', function (Blueprint $table) {
            $table->dropForeign("oauth_personal_access_clients_client_id_foreign");
        });

        Schema::table('oauth_access_tokens', function (Blueprint $table) {
            $table->dropForeign("oauth_access_tokens_client_id_foreign");
        });

        Schema::table('oauth_auth_codes', function (Blueprint $table) {
            $table->dropForeign("oauth_auth_codes_client_id_foreign");
        });
    }
}
