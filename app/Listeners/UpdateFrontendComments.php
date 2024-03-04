<?php

namespace App\Listeners;

use App\Events\CommentNew;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class UpdateFrontendComments
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
     * @param  CommentNew  $event
     * @return void
     */
    public function handle(CommentNew $event)
    {
        //
    }
}
