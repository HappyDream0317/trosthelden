<?php

namespace App\Listeners;

use App\Events\ChatMessageNew;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class UpdateFrontendChatMessage
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
     * @param  ChatMessageNew  $event
     * @return void
     */
    public function handle(ChatMessageNew $event)
    {
        //
    }
}
