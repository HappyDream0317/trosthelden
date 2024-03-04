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
        Schema::table('b2b_codes', function (Blueprint $table) {
            $table->text('description')->nullable();
            $table->boolean('is_assigned')->default(false);
            $table->timestamp('assigned_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('b2b_codes', function (Blueprint $table) {
            $table->dropColumn('description');
            $table->dropColumn('is_assigned');
            $table->dropColumn('assigned_at');
        });
    }
};
