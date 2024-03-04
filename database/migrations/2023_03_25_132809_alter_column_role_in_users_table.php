<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE users MODIFY COLUMN role enum('MOURNER', 'SUPPORTER', 'COMPANY') NOT NULL DEFAULT 'MOURNER'");
    }


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("ALTER TABLE users MODIFY COLUMN role enum('MOURNER', 'SUPPORTER') NOT NULL DEFAULT 'MOURNER'");
    }
};
