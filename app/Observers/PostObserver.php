<?php

namespace App\Observers;

use App\Events\PostDelete;
use App\Events\PostNew;
use App\Post;
use Illuminate\Support\Facades\Log;

class PostObserver
{
    /**
     * Handle the post "created" event.
     *
     * @param Post $post
     * @return void
     */
    public function created(Post $post)
    {
        //TODO: Move broadcast from Controller to here?
        //broadcast(new PostNew($post))->toOthers();
    }

    /**
     * Handle the post "updated" event.
     *
     * @param Post $post
     * @return void
     */
    public function updated(Post $post)
    {
        //
    }

    /**
     * Handle the post "deleted" event.
     *
     * @param Post $post
     * @return void
     */
    public function deleting(Post $post)
    {
        broadcast(new PostDelete($post));
    }

    /**
     * Handle the post "restored" event.
     *
     * @param Post $post
     * @return void
     */
    public function restored(Post $post)
    {
        //
    }

    /**
     * Handle the post "force deleted" event.
     *
     * @param Post $post
     * @return void
     */
    public function forceDeleted(Post $post)
    {
        //
    }
}
