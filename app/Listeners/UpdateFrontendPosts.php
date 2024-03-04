<?php

namespace App\Listeners;

use App\Events\PostNew;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class UpdateFrontendPosts
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  PostNew  $event
     * @return void
     */
    public function handle(PostNew $event)
    {
        //
    }
}
