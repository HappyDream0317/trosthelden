<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTooltipsToAnswers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('matching_answers', function (Blueprint $table) {
            $table->string('tooltip_title')->nullable();
            $table->string('tooltip_content', 500)->nullable();
        });
        Schema::table('matching_questions', function (Blueprint $table) {
            $table->string('tooltip_title')->nullable();
            $table->string('tooltip_content', 500)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('matching_answers', function (Blueprint $table) {
            $table->dropColumn('tooltip_title');
            $table->dropColumn('tooltip_content');
        });
        Schema::table('matching_questions', function (Blueprint $table) {
            $table->dropColumn('tooltip_title');
            $table->dropColumn('tooltip_content');
        });
    }
}
