<?php

namespace trosthelden\NovaImpersonate\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class LeaveImpersonation
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return  void
     */
    public function __construct() {}
}
