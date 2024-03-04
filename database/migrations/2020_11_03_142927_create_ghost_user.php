<?php

use App\ChatMessage;
use App\Post;
use App\PostComment;
use App\User;
use Carbon\Carbon;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGhostUser extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $ghostUser = new User();
        $ghostUser->deleted_at = Carbon::now();
        $ghostUser->save();

        Log::info('Ghost user id ' . $ghostUser->id);

        $orphanChatMessages = ChatMessage::whereNull('user_id')->get();
        foreach ($orphanChatMessages as $orphanChatMessage) {
            /**
             * @var $orphanChatMessage ChatMessage
            */

            $orphanChatMessage->user_id = $ghostUser->id;
            $orphanChatMessage->save();
        }

        $orphanPosts = Post::whereNull('user_id')->get();
        foreach ($orphanPosts as $orphanPost) {
            /**
             * @var $orphanPost Post
             */

            $orphanPost->user_id = $ghostUser->id;
            $orphanPost->save();
        }

        $orphanComments = PostComment::whereNull('user_id')->get();
        foreach ($orphanComments as $orphanComment) {
            /**
             * @var $orphanComment PostComment
             */

            $orphanComment->user_id = $ghostUser->id;
            $orphanComment->save();
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
    }
}
