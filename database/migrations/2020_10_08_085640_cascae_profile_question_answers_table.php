<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CascaeProfileQuestionAnswersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('profile_question_answers', function (Blueprint $table) {
            $table->dropForeign('profile_question_answers_profile_question_id_foreign');
            $table->foreign('profile_question_id')->references('id')->on('profile_questions')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('profile_question_answers', function (Blueprint $table) {
            $table->dropForeign('profile_question_answers_profile_question_id_foreign');
            $table->foreign('profile_question_id')->references('id')->on('profile_questions');
        });
    }
}
