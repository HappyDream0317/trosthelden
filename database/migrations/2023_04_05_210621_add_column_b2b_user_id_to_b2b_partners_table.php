<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('b2b_partners', function (Blueprint $table) {
            $table->foreignId('b2b_user_id')->nullable()->constrained('b2b_users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('b2b_partners', function (Blueprint $table) {
            $table->dropColumn('b2b_user_id');
        });
    }
};
