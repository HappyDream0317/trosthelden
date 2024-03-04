<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class SetUserAttributesNullable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('nickname')->nullable()->change();
            $table->string('email')->nullable()->change();
            $table->string('password')->nullable()->change();
            $table->string('matching_step')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('nickname')->nullable(false)->change();
            $table->string('email')->nullable(false)->change();
            $table->string('password')->nullable(false)->change();
            $table->string('matching_step')->nullable(false)->change();
        });
    }
}
