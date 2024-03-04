<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProfileTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('profile_questions', function (Blueprint $table) {
            $table->id();
            $table->string('text');
            $table->integer('position')->unique();
            $table->timestamps();
        });

        Schema::create('profile_question_answers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('profile_question_id')->constrained('profile_questions');
            $table->string('answer_text');
            $table->foreignId('user_id')->constrained('users');
        });

        Schema::create('profile_mottoes', function (Blueprint $table) {
            $table->id();
            $table->string('text');
            $table->foreignId('user_id')->constrained('users');

            $table->unique('user_id');
        });

        Schema::create('profile_visibilities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users');
            $table->boolean('visibility_info_job')->default(true);
            $table->boolean('visibility_info_religion')->default(true);
            $table->boolean('visibility_info_children')->default(true);

            $table->unique('user_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('profile_mottoes');
        Schema::dropIfExists('profile_visibilities');
        Schema::dropIfExists('profile_questions_answers');
        Schema::dropIfExists('profile_questions');
    }
}
