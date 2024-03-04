<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTooltipTable extends Migration
{
    public function up()
    {
        Schema::create('tooltips', function (Blueprint $table) {
            $table->id();
            $table->string('text')->default('');
            $table->string('component')->default('');
            $table->string('page')->default('');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('tooltips');
    }
}
