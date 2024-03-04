<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBillwerkCouponsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('billwerk_coupons', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->timestamp('sent_at')->nullable();
            $table->string('period');
            $table->string('file');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('billwerk_coupons');
    }
}
