<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChatMessagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('chat_connections', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->json('participants');
            $table->timestamps();
        });

        Schema::create('chat_messages', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('chat_id');
            $table->bigInteger('user_id');
            $table->string('message');
            $table->timestamp('send_at')->useCurrent(); //TODO: Alternatives?
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
        Schema::dropIfExists('chat_connections');
        Schema::dropIfExists('chat_messages');
    }
}
