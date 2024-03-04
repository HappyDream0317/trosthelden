<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGroupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('groups', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('category_id');
            $table->string('name')->nullable();
            $table->boolean('open')->default(true);
            $table->timestamps();

            $table->foreign('category_id')->references('id')->on('group_categories')->onDelete('cascade');
        });

        Schema::create('group_user', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('group_id');
            $table->unsignedBigInteger('user_id');
            $table->timestamps();

            $table->foreign('group_id')->references('id')->on('groups')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('group_users');
        Schema::dropIfExists('groups');
    }
}
