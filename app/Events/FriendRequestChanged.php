<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class FriendRequestChanged implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    
    public $requesting_user_id;
    public $target_user_id;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($userId, $foreignUserId)
    {
        $this->requesting_user_id = (int) $userId;
        $this->target_user_id = (int) $foreignUserId;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return [
            new Channel('user_'.$this->requesting_user_id.'_friend_requests_changed'),
            new Channel('user_'.$this->target_user_id.'_friend_requests_changed'),
        ];
    }
}
