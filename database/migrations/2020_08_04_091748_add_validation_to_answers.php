<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddValidationToAnswers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('matching_answers', function (Blueprint $table) {
            $table->unsignedInteger('matching_answer_validation_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (
            Schema::hasTable('matching_answers')
            && Schema::hasColumn('matching_answers', 'matching_answer_validation_id')
        ) {
            Schema::table('matching_answers', function (Blueprint $table) {
                $table->dropColumn('matching_answer_validation_id');
            });
        }
    }
}
