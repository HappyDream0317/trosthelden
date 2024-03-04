<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRankingTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('matching_cluster_ranking_def', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('rank');
            $table->unsignedInteger('dim1');
            $table->unsignedInteger('dim2');
            $table->unsignedInteger('dim3');
            $table->unsignedInteger('dim4');
            $table->unsignedInteger('dim5');
        });

        Schema::create('matching_cluster_dimensions_def', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->unsignedInteger('dimension')->nullable();
            $table->foreignId('answer_id_own');
            $table->foreignId('answer_id_partner');
            $table->tinyInteger('valuecode')->nullable();
            $table->smallInteger('weight')->nullable();
            $table->tinyInteger('exclusion')->nullable();
        });

        Schema::create('matching_user_partner_ranking', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->foreignId('user_id');
            $table->foreignId('partner_id');
            $table->unsignedInteger('rank');
            $table->unsignedSmallInteger('weight');
            $table->timestamp('first_display')->nullable();
            $table->timestamp('last_display')->nullable();
            $table->unsignedSmallInteger('display_number')->nullable();
            $table->timestamp('first_visit')->nullable();
            $table->timestamp('last_visit')->nullable();
            $table->unsignedSmallInteger('visit_number')->nullable();

            $table->unique(['user_id', 'partner_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('matching_cluster_ranking_def');
        Schema::dropIfExists('matching_cluster_dimensions_def');
        Schema::dropIfExists('matching_user_partner_ranking');
    }
}
