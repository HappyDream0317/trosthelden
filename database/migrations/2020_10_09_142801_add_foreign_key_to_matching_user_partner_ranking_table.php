<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeyToMatchingUserPartnerRankingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('matching_user_partner_ranking', function (Blueprint $table) {
            $table->bigInteger('partner_id')->unsigned()->change();
            $table->bigInteger('user_id')->unsigned()->change();

            // FIXME
            //table->foreign('partner_id')->references('id')->on('users')->onDelete('CASCADE');
            //$table->foreign('user_id')->references('id')->on('users')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('matching_user_partner_ranking', function (Blueprint $table) {
            // nope. That should not happen.
        });
    }
}
