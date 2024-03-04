<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateQuestionnaire extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('matching_questions', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->unsignedBigInteger('parent_id')->nullable(true);
            $table->unsignedBigInteger('depending_on_answer_id')->nullable(true);
            $table->char('code', 10)->nullable(true);
            $table->text('question')->nullable(true);
            $table->string('label')->nullable(true);
            $table->unsignedBigInteger('answer_type_id')->default(1);
            $table->boolean('obligatory')->nullable(true)->default(false);
            $table->unsignedBigInteger('conditions_relation_id')->nullable(true);
            $table->unsignedBigInteger('position')->default(1);

            $table->index(['depending_on_answer_id'], 'depending_on_answer_id_answer_id');
            $table->index(['parent_id'], 'parent_id');
        });

        Schema::create('matching_answers', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->unsignedBigInteger('question_id')->nullable(true);
            $table->unsignedBigInteger('parent_id')->nullable(true);
            $table->unsignedBigInteger('depending_on_answer_id')->nullable(true);
            $table->char('code', 10)->nullable(true);
            $table->string('answer')->nullable(true);
            $table->string('label')->nullable(true);
            $table->boolean('additional_text')->nullable(true)->default(false);
            $table->string('description')->nullable(true);
            $table->unsignedBigInteger('different_answer_type_id')->nullable(true);
            $table->boolean('obligatory')->nullable(true)->default(false);
            $table->integer('numeric_min')->nullable(true)->default(null);
            $table->integer('numeric_max')->nullable(true)->default(null);
            $table->boolean('termination_condition')->nullable(true)->default(false);
            $table->unsignedBigInteger('next_question_id')->nullable(true);
            $table->unsignedBigInteger('conditions_relation_id')->nullable(true);
            $table->integer('position')->default(1);

            $table->index(['parent_id'], 'parent_id');
            $table->index(['depending_on_answer_id'], 'depending_on_answer_id');
            $table->index(['different_answer_type_id'], 'different_type_id');
        });

        Schema::create('matching_answer_types', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->char('name')->unique();
            $table->string('description')->nullable(true);
        });

        Schema::create('matching_user_answers', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('answer_id');
            $table->string('answer_text')->nullable(true);
        });

        Schema::create('matching_question_answer_conditions', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->unsignedBigInteger('relation_id')->nullable(true); // TODO: Nullable?
            $table->unsignedBigInteger('related_question_id')->nullable(true);
            $table->unsignedBigInteger('related_answer_id')->nullable(true);
            $table->integer('visible_if_true');
            $table->char('before')->nullable(true);
            $table->char('operator_several_conditions')->nullable(true);
            $table->unsignedBigInteger('answer_id')->nullable(true); //TODO: vgl related_answer_id?
            $table->char('operator')->nullable(true)->default('=');
            $table->string('answer_content')->nullable(true)->default('1');
            $table->char('after')->nullable(true);
            $table->unsignedBigInteger('position')->nullable(true);
            $table->unsignedBigInteger('next_question_id_if_false')->nullable(true);
        });

        Schema::create('matching_question_step_contents', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->unsignedBigInteger('question_id')->unique(); // TODO: Unique?
            $table->integer('step_no');
            $table->string('content_before')->nullable(true);
            $table->string('content_after')->nullable(true);
        });

        Schema::table('matching_question_step_contents', function (Blueprint $table) {
            $table->foreign('question_id')->references('id')->on('matching_questions')->onUpdate('cascade')->onDelete('cascade');
        });

        Schema::table('matching_questions', function (Blueprint $table) {
            $table->foreign('parent_id')->references('id')->on('matching_questions')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('depending_on_answer_id')->references('id')->on('matching_answers')->onUpdate('cascade')->onDelete('restrict');
            $table->foreign('answer_type_id')->references('id')->on('matching_answer_types')->onUpdate('cascade')->onDelete('restrict');
            $table->foreign('conditions_relation_id')->references('id')->on('matching_question_answer_conditions')->onUpdate('cascade')->onDelete('cascade');
        });

        Schema::table('matching_answers', function (Blueprint $table) {
            $table->foreign('question_id')->references('id')->on('matching_questions')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('parent_id')->references('id')->on('matching_answers')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('depending_on_answer_id')->references('id')->on('matching_answers')->onUpdate('cascade')->onDelete('restrict');
            $table->foreign('different_answer_type_id')->references('id')->on('matching_answer_types')->onUpdate('cascade')->onDelete('restrict');
            $table->foreign('next_question_id')->references('id')->on('matching_questions')->onUpdate('cascade')->onDelete('set null');
            $table->foreign('conditions_relation_id')->references('id')->on('matching_question_answer_conditions')->onUpdate('cascade')->onDelete('cascade');
        });

        Schema::table('matching_user_answers', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('answer_id')->references('id')->on('matching_answers')->onUpdate('cascade')->onDelete('cascade');
        });

        Schema::table('matching_question_answer_conditions', function (Blueprint $table) {
            $table->foreign('related_question_id')->references('id')->on('matching_questions')->onUpdate('cascade')->onDelete('restrict');
            $table->foreign('related_answer_id')->references('id')->on('matching_answers')->onUpdate('cascade')->onDelete('restrict');
            $table->foreign('answer_id')->references('id')->on('matching_answers')->onUpdate('cascade')->onDelete('restrict');
            $table->foreign('next_question_id_if_false', 'next_question_if_false')->references('id')->on('matching_questions')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Schema::dropIfExists('matching_question_answer_conditions');
        Schema::dropIfExists('matching_user_answers');
        Schema::dropIfExists('matching_answer_types');
        Schema::dropIfExists('matching_answers');
        Schema::dropIfExists('matching_questions');
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
