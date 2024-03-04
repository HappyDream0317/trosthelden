<?php

namespace App\Events;

use App\UserFriend;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class FriendRequestConfirmation implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $user_id;
    public $foreign_user_id;

    /**
     * Create a new event instance.d.
     *
     * @param UserFriend $userFriend
     */
    public function __construct(UserFriend $userFriend)
    {
        $this->user_id = (int) $userFriend->user_id; // the friend
        $this->foreign_user_id = (int) $userFriend->foreign_user_id; // the logged in user
    }

    /**
     * Boradcast to Friendship Accepted Channel
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return [
            new Channel('user_'.$this->user_id.'_friendship_accepted'),
            new Channel('user_'.$this->foreign_user_id.'_friendship_accepted'),
        ];
    }
}
