<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class TweaksForPartnerDisplay extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('matching_user_partner_ranking', function (Blueprint $table) {
            $table->unsignedSmallInteger('display_number')->default(0)->change();
            $table->unsignedSmallInteger('visit_number')->default(0)->change();
        });
        Schema::table('user_friend_requests', function (Blueprint $table) {
            $table->timestamp('first_displayed_at')->nullable();
            $table->timestamp('last_displayed_at')->nullable();
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
            $table->unsignedSmallInteger('display_number')->nullabel()->change();
            $table->unsignedSmallInteger('visit_number')->nullable()->change();
        });
        Schema::table('user_friend_requests', function (Blueprint $table) {
            $table->dropColumn('first_displayed_at');
            $table->dropColumn('last_displayed_at');
        });
    }
}
