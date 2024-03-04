<?php

namespace App\Observers;

use App\Events\PostCommentDelete;
use App\PostComment;

class PostCommentObserver
{
    /**
     * Handle the post comment "created" event.
     *
     * @param PostComment $postComment
     * @return void
     */
    public function created(PostComment $postComment)
    {
        //
    }

    /**
     * Handle the post comment "updated" event.
     *
     * @param PostComment $postComment
     * @return void
     */
    public function updated(PostComment $postComment)
    {
        //
    }

    /**
     * Handle the post comment "deleting" event.
     *
     * @param PostComment $postComment
     * @return void
     */
    public function deleting(PostComment $postComment)
    {
        broadcast(new PostCommentDelete($postComment));
    }

    /**
     * Handle the post comment "restored" event.
     *
     * @param PostComment $postComment
     * @return void
     */
    public function restored(PostComment $postComment)
    {
        //
    }

    /**
     * Handle the post comment "force deleted" event.
     *
     * @param PostComment $postComment
     * @return void
     */
    public function forceDeleted(PostComment $postComment)
    {
        //
    }
}
