<?php

namespace App\Events;

use App\UserFriendRequest;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class FriendRequestNew implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $requesting_user_id;
    public $target_user_id;

    /**
     * Create a new event instance.d.
     *
     * @param PostComment $postComment
     */
    public function __construct(UserFriendRequest $friendRequest)
    {
        $this->requesting_user_id = $friendRequest->user_id;
        $this->target_user_id = $friendRequest->added_user_id;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new Channel('user_'.$this->requesting_user_id.'_friend_requests');
    }

    /**
     * @return array
     */
    public function broadcastWith()
    {
        return ['id' => $this->requesting_user_id];
    }
}
