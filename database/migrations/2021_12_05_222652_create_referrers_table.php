<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReferrersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('referrers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id');
            $table->string('referrer')->nullable();
            $table->string('referring_domain')->nullable();
            $table->string('content')->nullable();
            $table->string('campaign')->nullable();
            $table->string('medium')->nullable();
            $table->string('source')->nullable();
            $table->string('keyword')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('referrers');
    }
}
