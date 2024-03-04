<?php

namespace App\Listeners;

use App\Events\PostDelete;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class DeleteFrontendPost
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
     * @param  PostDelete  $event
     * @return void
     */
    public function handle(PostDelete $event)
    {
        //
    }
}
