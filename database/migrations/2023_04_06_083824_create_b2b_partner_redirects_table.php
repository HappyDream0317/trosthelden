<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('b2b_partner_redirects', function (Blueprint $table) {
            $table->id();
            $table->foreignId('b2b_partner_id')->constrained('b2b_partners');
            $table->string('slug')->unique();
            $table->string('target', 4096);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('b2b_partner_redirects');
    }
};
