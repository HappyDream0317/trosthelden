<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeProfileTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('profile_question_answers', function (Blueprint $table) {
            $table->string('answer_text')->default('')->change();
            $table->timestamps();
        });
        Schema::table('profile_mottoes', function (Blueprint $table) {
            $table->string('text')->default('')->change();
            $table->timestamps();
        });
        Schema::table('profile_visibilities', function (Blueprint $table) {
            $table->timestamps();
        });
        Schema::create('profile_progress', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users');
            $table->boolean('image')->default(false);
            $table->boolean('motto')->default(false);
            $table->integer('questions')->default(0);

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
        Schema::table('profile_question_answers', function (Blueprint $table) {
            $table->dropTimestamps();
        });
        Schema::table('profile_mottoes', function (Blueprint $table) {
            $table->dropTimestamps();
        });
        Schema::table('profile_visibilities', function (Blueprint $table) {
            $table->dropTimestamps();
        });
        Schema::dropIfExists('profile_progress');
    }
}
