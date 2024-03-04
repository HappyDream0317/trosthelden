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
        Schema::create('b2b_coupons', function (Blueprint $table) {
            $table->id();
            $table->foreignId('b2b_user_id')->constrained('b2b_users');
            $table->string('billwerk_id');
            $table->string('contract_id');
            $table->timestamp('expired_at')->nullable();
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
        Schema::dropIfExists('b2b_coupons');
    }
};
